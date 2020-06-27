FROM python:3
LABEL maintainer="Alex Neo <me@alexneo.net>"
WORKDIR /usr/src/app

RUN pip install --no-cache-dir requests psutil

COPY . .

CMD [ "python", "./client.py" ]
