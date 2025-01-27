## About project

Stake limit service processes ticket messages for devices according to stake limit configuration by which device status is declared as OK, HOT, BLOCKED(don't accept tickets anymore). Rules of stake limit being applied are **stakeLimit** - limit acceptance of stakes, **timeDuration** - period in which tickets stake sum is counted on device, **restrictionExpires** - time period after which device unblocks and **hotPercentage** - value at which device status is declared as HOT.

- Simple to use
- Open and secure controllers(in case that validation moves from Terminal Device in future)
- Services and Repositories
- Eloquent and Query builder
- In-memory stake config store
- Postgresql database
- Unit tested
- Custom validation rules
- Custom in-memory valuestore methods
- Dockerized app

## Install && use

the following steps show how to install the service

- Pull project
- Copy ```.env.example``` to ```.env``` and configure
- Set up database and fill up ```.env``` config
- Setup docker with ```@docker-compose build && docker-compose up -d```
- Run ```composer i``` to install dependencies
- Run ```@php artisan key::generate``` to generate app key
- Run ```@php artisan migrate``` to run database migrations
- Run ```@php artisan serve``` to launch app on PHP development server or run it with docker containers

The additional commands to use the service
- Run ```@php artisan test``` to run unit tests

following steps for creating db:
- ```docker exec -it postgres psql -U postgres```
- ```CREATE ROLE user123 WITH SUPERUSER CREATEDB CREATEROLE LOGIN ENCRYPTED PASSWORD 'pass';```
- ```CREATE DATABASE "stake-limit-service";```

NOTE: to run any command in docker with artisan or composer in laravel use prefix command:
- ```docker-compose exec php```

NOTE: If you have any permission errors use chmod and chown to add permissions and ownership on project root dir recursively


## Endpoints

The application has four endpoints and swagger docs endpoint:

    - {API_HOST}/api/open/tickets  POST  ->  'status': {{status}} // status: OK || HOT || BLOCKED
    - {API_HOST}/api/open/config  PUT  ->  'response': {...} // timeDuration: 200 && stakeLimit: 3000 && hotValue: 2300 && restrictionExpires: 60
   
    - {API_HOST}/api/secure/tickets  POST  ->  'status': {{status}} // status: OK || HOT || BLOCKED
    - {API_HOST}/api/secure/config  PUT  ->  'response': {...} // timeDuration: 200 && stakeLimit: 3000 && hotValue: 2300 && restrictionExpires: 60

    - {API_HOST}/api/v1/docs  GET -> Swagger docs

Open and secure endpoints serve the same purpose except secure endpoints apply validation rules that were noticed from context of task requirements, while open endpoints don't apply any rule.

Description of tickets and config endpoints
- /tickets  POST -> accepts ticket messages, process them according to stake limit configuration and returns status of device on which ticket refers.
- /config  PUT -> accepts stake limit, stores it in storage as json to be used as configuration for accepting tickets.

## Project sketch
Basicly mapping only dirs edited by me and explainin some of them to code reviewers to be more readable and accessible.

- app
  - Http
    - Controllers (open/secured controller with/without validation)
    - Requests (defined validation for requests)
  - Infrastructure
    - DTO 
    - Enum
    - Models (Defined Ticket model)
    - Repositories
      - Eloquent (use eloquent and return Model based instances)
      - Interfaces 
      - Queries (use query-builder and reutrns raw std class instances)
    - traits
  - Providers 
  - Services
    - Interfaces
    - Implementations (Stake limit service)
    - Utills (helper services like ValidatorExtender and StakeLimit)
- config
- database
  - factories (defined Ticket factory used in testing to generate instances)
  - migrations 
- resources > lang > en (localization) 
- routes (api.php)
- storage
  - app
    - stake-limit (dir where stake limit config.json is stored)  
- tests
  - data 
  - Unit
    
## Swagger (OpenAPI 3)

An important part for developers is the rest api testing and playground. Stake limit service uses swagger to generate html for backend users to test it.

The application uses a package to generate the page [https://github.com/DarkaOnLine/L5-Swagger]
The package is a wrapper for the php swagger implementation created for laravel.

The html is generated using the php command ```@php artisan l5-swagger:generate``` or by setting the env variable ```L5_SWAGGER_GENERATE_ALWAYS ``` to true. In order to access the api run the command (not needed if env variable is set to true) and run the application. Once the application starts you can access the api with the following link : http://{API_HOST}/api/docs 

An alternative to swagger is postman. 
