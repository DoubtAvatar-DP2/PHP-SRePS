# PHP-SRePS

## Running the Docker images

1. Download and install Docker from [the Docker website](https://www.docker.com/)
2. Copy the `.env_example` file and re-ename it to `.env`
3. Change the ports in `.env` to match your desired free ports
4. From the terminal, run `docker-compose up`
5. Wait until the `PHP` and `MySQL` images are running and the `Composer` image has stopped

![Successful docker-compose up output](https://raw.githubusercontent.com/DoubtAvatar-DP2/PHP-SRePS/master/wiki-media/completed_composer.png)
\
Successful docker-compose up output

## Accessing services

**Note: ** When using Docker Toolbox / Docker with VirtualBox, you will need to obtain the IP address using the Docker Quickstart Terminal and use that IP address instead of `localhost`

The webserver can be accessed via (http://localhost:8080/)

PHPMyAdmin can be accessed via (http://localhost:8081/)
\
The credentials are `admin` and `password`

The MySQL Server is accessed via the webserver or PHPMyAdmin. It does not expose any ports to be accessed by the host computer
