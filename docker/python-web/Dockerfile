FROM python:3
LABEL maintainer="Alex Neo <me@alexneo.net>"
WORKDIR /usr/src/app

RUN pip install --no-cache-dir flask jinja2

COPY . .

CMD [ "python", "./server.py" ]
