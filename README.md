# Q&A API

## Note
There were some discrepancies between the challenge description and the
sample payload. I targeted the sample payload, which has questions modeled
with tags and answers modeled with rank, rather than the other way around, 
since that made more sense to me as a typical user experience. 

## Requirements

Docker engine 18.06+

## Technology Stack
 - Symfony 4.4
 - MySQL 5.7

## Getting Started

There are currently some manual build steps.
```bash
$ docker-compose up --build -d
```

```bash
$ docker-compose exec php /usr/local/bin/composer install
```

```bash
$ docker-compose exec php bin/console doctrine:migrations:migrate
```


## Usage
Interactive swagger interface:
http://localhost:8080/api/doc

## TODO's
 * Less revealing exception payloads.
 * Standardize a validation error payload.
 * Solve bug with rank sort on DQL.
 
