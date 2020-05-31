## APAN50 Kubernetes Demo <br> Setting up a LEMP stack and Pod Auto Scalers on Kubernetes
---

### DISCLAIMER
The information contain here is meant for showcasing the different pod auto scalers and to deploy a simple LEMP stack application
and is by no means production ready therefore please do not deploy it on production clusters.

---

### Requirements

1. Working kubernetes cluster
2. External load balancer
3. kube-metrics-server deployed in the cluster [(Link)](https://github.com/kubernetes-sigs/metrics-server)
4. Vertical pod auto scaler deployed in the cluster [(Link)](https://github.com/kubernetes/autoscaler/tree/master/vertical-pod-autoscaler)


### Verify kubernetes cluster is running
---

1. Run the following to check if the kubernetes service is running

    ```
    kubectl get svc kubernetes
    ```
    
    Expected output should be similar to the one shown below
    
    ```
    NAME         TYPE        CLUSTER-IP   EXTERNAL-IP   PORT(S)   AGE
    kubernetes   ClusterIP   10.96.0.1    <none>        443/TCP   52d
    ```

  
### Deploy mariadb container
---

1. Open up ``` mariadb.yaml ```
2. Find and replace __MYSQL_ROOT_PASSWORD__ value to initialize the root users password during startup of the container
3. (Optional) Find and replace the __MYSQL_DATABASE__ value with the name of the database that the website will be using later.

    ```
    env:
    - name: MYSQL_ROOT_PASSWORD
      value: "password123" # Set root password here
    - name: MYSQL_DATABASE
      value: "apan50demo" # Set database name here
    ```

4. Save the file and apply the mariadb manifest

    ```
    kubectl apply -f mariadb.yaml
    ```

 
5. Check if the pod is in the __Running__ state

    ```
    kubectl get pod --selector=app=mariadb
    ```    
   <br>
   
    __*Ensure the output of the command above is similar to the one shown below before proceeding to the next step__
    
    ```
    NAME        READY   STATUS    RESTARTS   AGE
    mariadb-0   1/1     Running   0          13s
    ```       
    
 
### Deploy nginx-php-fpm container
---

1. Open up ``` nginx.yaml ```
2. Find and replace the __DB_SELECT__ value with the __MYSQL_DATABASE__ value and __DB_PASS__ value with the __MYSQL_ROOT_PASSWORD__ value as used during the mariadb setup portion

    ```
    env:
    - name: DB_ADDR
      value: "mariadb-0.mariadb-service.default.svc.cluster.local:3306"
    - name: DB_USER
      value: "root"
    - name: DB_PASS
      value: "password123"
    - name: DB_SELECT
      value: "apan50demo"
    ```
3. Save the file and apply the mariadb manifest

    ```
    kubectl apply -f nginx.yaml
    ```

 
4. Check if the pod is in the __Running__ state

    ```
    kubectl get pod --selector=app=nginx
    ```    
   <br>
   
    __*Ensure the output of the command above is similar to the one shown below before proceeding to the next step__
    
    ```
    NAME                     READY   STATUS    RESTARTS   AGE
    nginx-69cc54b656-22zsr   1/1     Running   0          19s
    nginx-69cc54b656-7n785   1/1     Running   0          19s
    ```         
    
 ---
 
 ### Test out the LEMP stack
---

 Congratulations now our LEMP stack is deployed on the kuebrenetes cluster. Before going further let's test out our new LEMP stack.
 To access the nginx service we first need to know the IP address that is allocated by the external load balancer. To find it, 
 execute the following command and find the IP address under __EXTERNAL IP__
 
 
  ``` 
  NAME            TYPE           CLUSTER-IP       EXTERNAL-IP    PORT(S)        AGE     SELECTOR
  nginx-service   LoadBalancer   10.107.214.165   10.10.10.202   80:32098/TCP   6m16s   app=nginx
  ```
 
 
 
 
 
 
 
 
 