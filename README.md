### Installation:
To run project execute:
```sh
docker compose up
```
Install composer dependencies required for project.
```sh
docker compose exec app composer install
```
Migrate database schema to make database up to date.
```sh
docker compose exec app bin/console doctrine:migrations:migrate -vvv --no-interaction 
```
Load test questions to the database to be able to run test.
```sh
docker compose exec app bin/console doctrine:fixtures:load
```

### Application use case:
1) The entry point to the application is the console command "app:test:run". 
 Run console command:
   ```sh
    docker compose exec app php bin/console app:test:run
   ```
2) The application will ask for your name.
You need to provide your first name and last name respectively.

3) The program will give you questions and answers in random order.
You need to enter one or more right answers by entering a value of answers or its option number separated by a comma char.

4) After all questions you will see stats with correct and incorrect answers details.

### Project structure:
The project consists of 3 folders in src one.
1) [Domain](src/Domain) (business logic)
2) [Infrastructure](src/Infrastructure) (code related to network IO. like, database, controllers, cli-commands)
3) [UseCases](src/UseCase) (entry points to use cases)

### Dev-tools usage:
Run phpunit
```sh
docker compose exec app vendor/bin/phpunit --testdox
```
Run coding standard checker.
```sh
docker compose exec app vendor/bin/ecs
```
Run syntax analyzer.
```sh
docker compose exec app vendor/bin/phpstan
```
Check database schema validity.
```sh
docker compose exec app bin/console doctrine:schema:validate
```
Check database schema validity.
```sh
docker compose exec app composer validate
```
