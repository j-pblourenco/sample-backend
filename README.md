## Run docker
```
docker-compose up -d --build
```

To install symfony dependecies first run this to run commands inside the Docker container
```
docker exec -it <redclip_backend_php CONTAINER_ID> /bin/sh
```

and then inside the docker install the dependencies with

```
composer install
```
