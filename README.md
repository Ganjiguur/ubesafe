# CakePHP Application Skeleton

[![Build Status](https://img.shields.io/travis/cakephp/app/master.svg?style=flat-square)](https://travis-ci.org/cakephp/app)
[![License](https://img.shields.io/packagist/l/cakephp/app.svg?style=flat-square)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.x.

The framework source code can be found here: [cakephp/cakephp](https://github.com/cakephp/cakephp).

## Installation

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run

```bash
composer create-project --prefer-dist cakephp/app [app_name]
```

You should now be able to visit the path to where you installed the app and see
the setup traffic lights.

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.



## Installation for development

Run following console command in application root directory

composer install

create MySQL database

Configure app.php (database)

bin/cake migrations migrate -p CakeDC/Users

bin/cake migrations migrate -p Batu/Version

bin/cake migrations migrate

bin/cake users addSuperuser


## Manual TODO

Users plugin dotor daraah zaswariig hiij ugnu

SocialBehavior.php dotorh _populateUser function deer full_name hadgalah hesgiig nemj ugnu.



## Creating UIKit templates

bin/cake bake template -t UikitTemplate Pages


## Working with asset_compress (Compress assets)

Requirement:
1. composer require markstory/asset_compress


Install package for compressing js files
2. 
composer require tedivm/jshrink "@stable"
OR
npm install uglify-js@2.8.29 -g


Install php package for compressing css files
3. composer require natxet/CssMin
4. Work with the asset_compress.ini
5. Also we can generate build files using CLI with command "bin/cake asset_compress build"
6. Documentation is on https://github.com/markstory/asset_compress/wiki

### MSSQL GUIDE

1. Download PHP driver and ODBC driver from https://docs.microsoft.com/en-us/sql/connect/php/system-requirements-for-the-php-sql-driver

2. edit php.ini