Expenditure
============
A small accounting application written using [Silex](http://silex.sensiolabs.org/) that allows you to keep track of your monthly outgoings and know exactly how much money you have left in your account after bills

### Installation

This is only currently avaialble if you self host the application. 

###### Prerequisites

- PHP 5.3
- MySQL 5

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

    Copy `app/config/dev.json.dist` to `app/config/dev.json` and configure the appropriate parameters.

6. Install third party bundles

    This application relies on [Composer](http://getcomposer.org/download/) to install the dependencies required. Once you have composer installed, at the root of the application, run `/path/to/php composer install`
    
7. Finished

    Go to [expenditure](http://expenditure) in your browser and you should be ready to go. Now go ahead and keep a track of your bills and know exactly how much money you have every month!