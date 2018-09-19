# WMS 
### Windowed Management System
## Laravel-KendoUI (Web Desktop-Like Application Environment)
<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"><img src="https://telerikhelper.files.wordpress.com/2015/03/kendoui.png?w=440"></p>
<p align="center"><img src="http://www.simonecosci.com/storage/app/media/SS-1.jpg"></p>
[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](simone.cosci@gmail.com)

## About WMS

WMS has been built on top of the Laravel Framework by extending some feature and using kendo-ui as frontend javascript framework.
Using this software requires a commercial license of Kendo UI

- [Laravel](https://laravel.com/docs/5.6).
- [Telerik Kendo UI for jQuery](https://www.progress.com/kendo-ui).


## Licenses

The Laravel framework and the WMS are open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

The Kendo-UI framework is commercial software licensed [https://www.telerik.com/purchase/license-agreement/kendo-ui](https://www.telerik.com/purchase/license-agreement/kendo-ui).

## Installation

```
composer create-project --stability=dev simonecosci/wms <install-directory>
cd <install-directory>
npm install
```
## Database
Creata a new database
```
mysql -uroot -p
mysql> create database yourDatabaseName;
mysql> quit;
```

Then `cp .env.example .env` and update your database creds.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yourDatabaseName
DB_USERNAME=root
DB_PASSWORD=root
```

Change (if you want) the initial credential by editing the file `/database/seeds/UsersTableSeeder.php` or use these:

```
email: admin@example.com
password: admin
```

run the migrations with seed
```
php artisan migrate:fresh --seed
```

Configure a virtualhost in your web server config
```
<VirtualHost *:80>
	ServerName localhost
	DocumentRoot "/<install-directory>/public"
	<Directory  "/<install-directory>/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	</Directory>
</VirtualHost>
```

Navigate http://localhost/ and login

You have to give *write* permission to the following folders while your app is in development 
- app/Models
- app/Http/Controllers/Admin
- resources/views/admin
- database/migrations
- public/app
```
chmod -R 777 app/Models
chmod -R 777 app/Http/Controllers/Admin
chmod -R 777 resources/views/admin
chmod -R 777 database/migrations
chmod -R 777 public/app
```


To create your first window read the [Wiki](https://github.com/simonecosci/wms/wiki)

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](simone.cosci@gmail.com)
<p align="center"><img src="http://www.simonecosci.com/storage/app/media/SS-2.jpg"></p>
<p align="center"><img src="http://www.simonecosci.com/storage/app/media/SS-3.jpg"></p>
