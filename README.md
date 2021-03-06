Expenditure
============

[![Build Status](https://travis-ci.org/psamatt/Expenditure.png?branch=master)](https://travis-ci.org/psamatt/Expenditure)

A small accounting application written using [Symfony](http://symfony.com/) that allows you to keep track of your monthly outgoings and know exactly how much money you have left in your account after bills

### Installation

This is only currently avaialble if you self host the application. 

###### Prerequisites

- PHP 5.3+
- MySQL 5
- Mod Rewrite enabled

###### Steps

1. Download the repository and copy it to an appropriate public_html directory

2. Configure your vhost

    In your vhost write the following

    ```apache
    <VirtualHost *:80>
        ServerName expenditure
        DocumentRoot /path/to/web
    </VirtualHost>
    ```

3. Configure your hosts file

    In your hosts file
    
    ```apache
    127.0.0.1 expenditure
    ```

4. Create database and table structure

    Create a database called `expenditure` and run the install.sql against the database, this should create all the table structures required to run this application. 

5. Configure parameters

    Copy `app/config/parameters.yml.dist` to `app/config/parameters.yml` and configure the appropriate parameters.

6. Install third party bundles

    This application relies on [Composer](http://getcomposer.org/download/) to install the dependencies required. Once you have composer installed, at the root of the application, run `/path/to/php composer install`
    
7. Finished

    Go to [expenditure](http://expenditure) in your browser and you should be ready to go. Now go ahead and keep a track of your bills and know exactly how much money you have every month!

### Contributing

If you would like to contribute towards Expenditure, then please follow the steps below

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Make your changes
4. Add tests (if applicable) (`phpunit`)
5. Commit your changes (`git commit -am 'Added some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new Pull Request
8. Sit back and smile whilst knowing that you're making the world a better place

### Running tests

If you want to run the tests against this repository, then there are two types of tests:

#### PHPUnit

In the root of the repository, run:

`phpunit -c app/phpunit.xml.dist`

#### Behat

Some behat tests require Javascript, we therefore require a headless browser, firstly install [phantomJS](http://phantomjs.org/), then in one tab of Terminal, start phantomJS to listen to port 8643

`phantomjs --webdriver=8643`

Now in another Terminal tab, run the behat testing suite

`bin/behat`