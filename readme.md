
## Authors

* **LUCA SOLANO**    (bbetter.it)

## Cliapp  (Point 1 homework)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Application that: gets the list of the cities from Musement's API and for each city gets the forecast for the next 2 days using http://api.weatherapi.com and print to STDOUT "Processed city [city name] | [weather today] - [wheather tomorrow]"

## Install

This guide assumes you're using [Docker Enviroment](https://docs.docker.com/get-docker/)


- Download the master branch

- Updates the values of the env file  (./app/env)
```bash
OVH_TOKEN=[wheaterapi-token]
OVH_APIURL=http://api.weatherapi.com/v1/
MUSEUM_APIURL=https://sandbox.musement.com
```

- Build Docker Image
```bash
docker build -t [cli-test]  .  
```

### Usage

Run the Docker image ( -after build open interactive shell)
```bash
docker run -ti [cli-test]    /bin/sh
```

##### Run program
Launch command from shell:
```bash
php cli.php
```

##### Run unit test
Launch command from shell:
```bash
composer run-script testunit
```

##### Run codequality (standard PSR12)
Launch command from shell:
```bash
composer run-script phpcs
```

##### Run codecoverage
Launch command from shell:
```bash
composer run-script codecoverage
```

##### Tail logs
Launch command from shell:
```bash
tail -f ./logs/cli.log
```

### License

Cliapp is  open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)



## APi Design  (Point 2 homework)

OpenAPI specs for API are here https://github.com/elbarto83/homework/blob/main/swagger-point2.yaml
