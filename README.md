# PHP-SRePS

## Running the Docker images

1. Download and install Docker from [the Docker website](https://www.docker.com/)
2. From the terminal, run `docker-compose up`
3. Wait until the `PHP` and `MySQL` images are running and the `Composer` image has stopped

![Successful docker-compose up output](https://raw.githubusercontent.com/DoubtAvatar-DP2/PHP-SRePS/master/wiki-media/completed_composer.png)
\
Successful docker-compose up output

## Accessing services

The webserver can be accessed via (http://localhost:8080/)

PHPMyAdmin can be accessed via (http://localhost:8081/)
\
The credentials are `admin` and `password`

The MySQL Server is accessed via the webserver or PHPMyAdmin. It does not expose any ports to be accessed by the host computer
