# OSS CONTA API

The brand new application to keep accounting of any business!


## Installation & updates

run `composer install` to get all the needed dependencies.

## Setup

Copy `env.example` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

### database

Now we have to run the next commands to setup database
```
# make all the tables
$ php spark migrate

# setup admin user, admin permisions, and basic info.
$ php spark db:seed DefaultSeeder
```

## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
