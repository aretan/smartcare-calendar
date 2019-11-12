## Framework & Document

CodeIgniter 4 - `Lightweight Web Framework`

https://codeigniter4.github.io/userguide/

## Testing

1. cd api
2. ./vendor/phpunit/phpunit/phpunit
3. Output:

    ```
    PHPUnit 7.5.17 by Sebastian Bergmann and contributors.

    ..                                                                  2 / 2 (100%)

    Time: 95 ms, Memory: 6.00 MB

    OK (2 tests, 2 assertions)
    ```

## Api Endpoints

```
$ php ./vendor/codeigniter4/framework/spark routes

CodeIgniter CLI Tool - Version 4.0.0-rc.3 - Server-Time: 2019-11-10 21:58:34pm

+------------------------------------------+--------+------------------------------------------------+
| Route                                    | Method | Command                                        |
+------------------------------------------+--------+------------------------------------------------+
| v1/shoken/(.*)/ukeban/(.*)/result/(.*)   | get    | \App\Controllers\v1\Result::show/$1            |
| v1/shoken/(.*)/ukeban/(.*)               | get    | \App\Controllers\v1\Ukeban::show/$1            |
| v1/shoken                                | get    | \App\Controllers\v1\Shoken::index              |
| v1/shoken/(.*)                           | get    | \App\Controllers\v1\Shoken::show/$1            |
| v1/shoken/(.*)/ukeban/(.*)/shujutsu      | post   | \App\Controllers\v1\Shujutsu::create           |
| v1/shoken/(.*)/ukeban/(.*)/tsuin         | post   | \App\Controllers\v1\Tsuin::create              |
| v1/shoken/(.*)/ukeban/(.*)/nyuin         | post   | \App\Controllers\v1\Nyuin::create              |
| v1/shoken/(.*)/ukeban/(.*)/result        | post   | \App\Controllers\v1\Result::create             |
| v1/shoken/(.*)/ukeban                    | post   | \App\Controllers\v1\Ukeban::create             |
| v1/shoken                                | post   | \App\Controllers\v1\Shoken::create             |
| v1/shoken/(.*)/ukeban/(.*)/shujutsu/(.*) | patch  | \App\Controllers\v1\Shujutsu::update/$1        |
| v1/shoken/(.*)/ukeban/(.*)/tsuin/(.*)    | patch  | \App\Controllers\v1\Tsuin::update/$1           |
| v1/shoken/(.*)/ukeban/(.*)/nyuin/(.*)    | patch  | \App\Controllers\v1\Nyuin::update/$1           |
| v1/shoken/(.*)/ukeban/(.*)               | patch  | \App\Controllers\v1\Ukeban::update/$1          |
| v1/shoken/(.*)                           | patch  | \App\Controllers\v1\Shoken::update/$1          |
| v1/shoken/(.*)/ukeban/(.*)/shujutsu/(.*) | put    | \App\Controllers\v1\Shujutsu::update/$1        |
| v1/shoken/(.*)/ukeban/(.*)/tsuin/(.*)    | put    | \App\Controllers\v1\Tsuin::update/$1           |
| v1/shoken/(.*)/ukeban/(.*)/nyuin/(.*)    | put    | \App\Controllers\v1\Nyuin::update/$1           |
| v1/shoken/(.*)/ukeban/(.*)               | put    | \App\Controllers\v1\Ukeban::update/$1          |
| v1/shoken/(.*)                           | put    | \App\Controllers\v1\Shoken::update/$1          |
| v1/shoken/(.*)/ukeban/(.*)/shujutsu/(.*) | delete | \App\Controllers\v1\Shujutsu::delete/$1        |
| v1/shoken/(.*)/ukeban/(.*)/tsuin/(.*)    | delete | \App\Controllers\v1\Tsuin::delete/$1           |
| v1/shoken/(.*)/ukeban/(.*)/nyuin/(.*)    | delete | \App\Controllers\v1\Nyuin::delete/$1           |
| v1/shoken/(.*)/ukeban/(.*)               | delete | \App\Controllers\v1\Ukeban::delete/$1          |
| v1/shoken/(.*)                           | delete | \App\Controllers\v1\Shoken::delete/$1          |
| migrations/([^/]+)/([^/]+)               | cli    | \CodeIgniter\Commands\MigrationsCommand::$1/$2 |
| migrations/([^/]+)                       | cli    | \CodeIgniter\Commands\MigrationsCommand::$1    |
| migrations                               | cli    | \CodeIgniter\Commands\MigrationsCommand::index |
| ci(.*)                                   | cli    | \CodeIgniter\CLI\CommandRunner::index/$1       |
+------------------------------------------+--------+------------------------------------------------+
```
