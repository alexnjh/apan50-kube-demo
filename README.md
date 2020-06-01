## APAN50 Kubernetes Demo Tutorial<br> Setting up a LEMP stack and Pod Auto Scalers on Kubernetes

### DISCLAIMER
The information contained here is meant for showcasing the different pod auto scalers and to deploy a simple LEMP stack application
and is by no means production ready therefore please do not deploy it on production clusters.

---

## Contents
- [Contents](#contents)
  - [Prerequisites](#prerequisites)
  - [Verify kubernetes cluster is running](#verifykube)
  - [Deploy mariadb pod](#deploymariadb)
  - [Deploy nginx-php-fpm pod](#deploynginx)
  - [Test out the LEMP stack](#testoutstack)

<br>

### Prerequisites

1. Working Kubernetes cluster

<a name="verifykube"/></a> 

<br>

### Verify Kubernetes cluster is running
---

**1. Run the following to check if the Kubernetes service is running**


    kubectl get svc kubernetes
    
    The expected output should be similar to the one shown below
    
    NAME         TYPE        CLUSTER-IP   EXTERNAL-IP   PORT(S)   AGE
    kubernetes   ClusterIP   10.96.0.1    <none>        443/TCP   52d

<br>

<a name="deploymariadb"/></a>

### Deploy MariaDB container
---

**1. Open up ``` mariadb.yaml ```**

**2. Find and replace MYSQL_ROOT_PASSWORD value to a password of your choice**

**3. (Optional) Find and replace the MYSQL_DATABASE value with the name of the database that the website will be using later.**

    env:
    - name: MYSQL_ROOT_PASSWORD
      value: "password123" # Set root password here
    - name: MYSQL_DATABASE
      value: "apan50demo" # Set database name here
      

**4. Save the file and apply the mariadb manifest**

    kubectl apply -f mariadb.yaml
 
**5. Check if the MariaDB pod is in the __Running__ state**

    kubectl get pod --selector=app=mariadb
     
<br>
   
***Ensure the output of the command above is similar to the one shown below before proceeding to the next step**
    
    NAME        READY   STATUS    RESTARTS   AGE
    mariadb-0   1/1     Running   0          13s     
 
 <br>
    
<a name="deploynginx"/></a> 
### Deploy nginx-php-fpm container
---

**1. Open up ``` nginx.yaml ```**

**2. Find and replace the __DB_SELECT value with the __MYSQL_DATABASE value and __DB_PASS value with the __MYSQL_ROOT_PASSWORD value as used during the mariadb setup portion**

    env:
    - name: DB_ADDR
      value: "mariadb-0.mariadb-service.default.svc.cluster.local:3306"
    - name: DB_USER
      value: "root"
    - name: DB_PASS
      value: "password123"
    - name: DB_SELECT
      value: "apan50demo"
      
**3. Save the file and apply the mariadb manifest**

    kubectl apply -f nginx.yaml

 
**4. Check if the pod is in the __Running__ state**

    kubectl get pod --selector=app=nginx
 
 
<br>
   
***Ensure the output of the command above is similar to the one shown below before proceeding to the next step**
    
    NAME                     READY   STATUS    RESTARTS   AGE
    nginx-69cc54b656-22zsr   1/1     Running   0          19s
    nginx-69cc54b656-7n785   1/1     Running   0          19s
      
 <br>
 
 <a name="testoutstack"/></a>
 ### Test out the LEMP stack
 ---

 Congratulations now our LEMP stack is deployed on the Kubernetes cluster. Before going further let's test out our new LEMP stack.
 If your using minikube please refer to [this](#minikube-portforward) if not follow the instructions below according to the service type of your choice
 
 #### 1. Using external load balancer
 
 1. Get nginx service external IP address
 
   ``` 
   kubectl get svc nginx-service -o wide
   
   NAME            TYPE           CLUSTER-IP       EXTERNAL-IP    PORT(S)        AGE     SELECTOR
   nginx-service   LoadBalancer   10.107.214.165   10.10.10.202   80:32098/TCP   6m16s   app=nginx

   ```
   
  2. Access the website by typing the nginx service **EXTERNAL IP** address inside a web browser. For example, if the worker node's IP address is **10.10.10.202**, to access the website I will enter **http://10.10.10.202** and the website should look like [this](#image2)
   
 #### 2. Using NodePort
 
 1. Get nginx service external IP address 
 
   ``` 
   kubectl get svc nginx-service -o wide
   
   NAME            TYPE           CLUSTER-IP       EXTERNAL-IP    PORT(S)        AGE     SELECTOR
   nginx-service   NodePort       10.107.214.165                  80:32098/TCP   6m16s   app=nginx

   ```

  2. Access the website by typing the nginx service **NODE IP:PORT NUMBER** address inside a web browser. For example, if the worker node IP address is **10.1.1.1**, to access the website I will enter **http://10.1.1.1:32098** and the website should look like [this](#image2)

 <a name="minikube-portforward"/></a>
 #### 3. Using service and portforwarding (Only for minikube)
 
  1. Open another terminal and enter the following command to port forward the port to the minikube virtual machine
 
   ``` 
   kubectl port-forward --address 0.0.0.0 svc/nginx-service 30000:80
   ```
  2. Access the website by typing the nginx service **MINIKUBE NODE IP** address inside a web browser. For example, if my node IP address is 10.1.1.1, to access the website I will enter http://10.1.1.1:30000 and the website should look like [this](#image2)


<a name="image2"/></a>

![image1](https://github.com/alexnjh/apan50-kube-demo/blob/master/images/image1.jpg "Book information webpage")
  
  
  **3. Enter information regarding a book and submit the form and if configured correctly the front-page should be updated with the new book's information.**

<br>
<br>

<a name="autoscaler"/></a>
 ### Vertical and Horizontal pod auto scaler
 ---

 Before trying out the auto scaler. Let's take a look at their differences.
 
<br>
<br>
 
| Horizontal        | Vertical           |
| :------------: |:-------------:|
| Increase number of containers based on metrics | Increase container resources based on metrics |
| Mainly for Stateless pods | Mainly for Stateful pods |
| Cannot run concurrently with VPA on the same metrics | Cannot run concurrently with HPA on the same metrics|

__VPA__ = Vertical Pod Autoscaler <br>
__HPA__ = Horizontal Pod Autoscaler

<br>
<br>

In short, the HPA creates more pods to spread out the workload among multiple different pods while the VPA increases the amount of computing power of the pod to process more requests. 
 
![image2](https://github.com/alexnjh/apan50-kube-demo/blob/master/images/image2.jpg "Autoscaler")

### Testing out the horizontal pod autoscaler
---

**1. We start by deploying the kube-metrics-server which is required by the autoscaler to get pod metrics**

Minikube : ```minikube addons enable metrics-server```
Non-Minikube : ```kubectl apply -f https://github.com/kubernetes-sigs/metrics-server/releases/download/v0.3.6/components.yaml```
    
    
**2. Ensure the kube-metrics-server is in the __Running__ state**

    kubectl get pods  -n kube-system | awk '/metrics-server/{print}'
    
    
**3. We now apply the HPA manifest**

    kubectl apply -f pod_auto_scaler/hpa.yaml
    
  
**4. Verify HPA is working. (May need to run the command multiple times)**

    kubectl get hpa nginx
    
    Expected output:
    
    NAME    REFERENCE          TARGETS   MINPODS   MAXPODS   REPLICAS   AGE
    nginx   Deployment/nginx   5%/50%    1         5         2          23s

    
**5. HPA will automatically scale the pods when the current pods experience heavy load let's generate some artificial CPU load on the pods and see the HPA in action**

  * To increase the load open the sample website and click on the benchmark on the top right-hand corner

  ![image3](https://github.com/alexnjh/apan50-kube-demo/blob/master/images/image3.jpg "Book information webpage")

  * Click on submit, this will increase the CPU load to around 100% for 1 minute

  * After around 40 seconds the HPA will create a few more pods to spread out the load as shown below
    
      ```    
      Before:

      NAME                     READY   STATUS    RESTARTS   AGE
      mariadb-0                1/1     Running   0          19h
      nginx-69cc54b656-lpbf9   2/2     Running   0          19h

      After:

      NAME                     READY   STATUS    RESTARTS   AGE
      mariadb-0                1/1     Running   0          19h
      nginx-69cc54b656-758bz   2/2     Running   0          44s
      nginx-69cc54b656-c2v7q   2/2     Running   0          45s
      nginx-69cc54b656-jllsj   2/2     Running   0          45s
      nginx-69cc54b656-lpbf9   2/2     Running   0          19h
      nginx-69cc54b656-pt5th   2/2     Running   0          45s
      ```
    

<br>
<br>

At this point, the HPA is functioning. For this example, the metric used for scaling is **CPU load** although custom metrics can also be used but will require more specific configuration which is outside the scope of this tutorial.

Before moving on to the Vertical Pod Autoscaler example please remove the HPA from the cluster.This is to ensure the HPA will not affect the VPA.
    
Run the following command to remove the HPA before proceeding

    kubectl delete -f pod_auto_scaler/hpa.yaml

 
<br> 
 
### Testing out the vertical pod autoscaler
---

**1. Deploy the vertical pod autoscaler controller manifest (Skip to step 2 if VPA controller is deployed)**

    git clone https://github.com/kubernetes/autoscaler.git
    ./autoscaler/vertical-pod-autoscaler/hack/vpa-up.sh
    
**2. Before deploying the VPA we first need to understand under which circumstances will the VPA redeploy the pod with more resources.**

The VPA will redeploy the pod when the pod requested resource is below the lower bound or the current CPU load is above the upper bound values in the VPA recommendation

    
**3. Now we take a look at the current requested resources used by the nginx pod look for label requests **

    kubectl describe pods --selector=app=nginx
    
    *--- Output omitted for brevity --*
   
        Limits:
          cpu:     100m
          memory:  50Mi
        Requests:
          cpu:        10m
          memory:     10Mi
          
    *--- Output omitted for brevity --*

    
**4. Next apply the VPA manifest file**


    kubectl apply -f pod_auto_scaler/vpa.yaml


    
**5. Let's take a look at the recommendation by the VPA**


    kubectl describe pods --selector=app=nginx
    
    *--- Output omitted for brevity --*
   
    Container Recommendations:
      Container Name:  nginx
      Lower Bound:
        Cpu:     25m
        Memory:  262144k
      Target:
        Cpu:     25m
        Memory:  262144k
      Uncapped Target:
        Cpu:     25m
        Memory:  262144k
      Upper Bound:
        Cpu:     25m
        Memory:  749230002
          
    *--- Output omitted for brevity --*

    
As we can see, the VPA recommends the nginx pod to be configured with 25 milli-cpus and therefore this will result in the VPA recreating the pods to increase the CPU resources from 10m to 25m to meet the recommendation.
  
  <br>
  
**6. We can verify this by running the describe command on the pod name and look at the current requested resources of the nginx pods. To get the pod names run "kubectl get pods --selector=app=nginx"**
  
      kubectl describe pods nginx-69cc54b656-lpbf9

      *--- Output omitted for brevity --*

          Limits:
            cpu:     2
            memory:  1Gi
          Requests:
            cpu:      100m
            memory:   128Mi

      *--- Output omitted for brevity --*


<br> 
 
### Conclusion
--- 

This concludes the demostration regarding the different pod auto scalers and the steps required to deploy a workflow in a Kubernetes cluster. 

Thank you for taking the time to read and go through the tutorial.
