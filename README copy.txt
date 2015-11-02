## Table of contents

- [What's included in the folder]
- [How to install the application]

### What's included in the folder

Within the download you'll find the following directories and files, logically grouping common assets and providing both compiled and minified variations. You'll see something like this:

foreignCurrencyPurchase/
|-index.php
|-assets/
	|-bootstrap
		|-dist/
			├── css/
			│   ├── bootstrap.css
			│   ├── bootstrap.css.map
			│   ├── bootstrap.min.css
			│   ├── bootstrap-theme.css
			│   ├── bootstrap-theme.css.map
			│   └── bootstrap-theme.min.css
			├── js/
			│   ├── bootstrap.js
			│   └── bootstrap.min.js
			└── fonts/
				├── glyphicons-halflings-regular.eot
				├── glyphicons-halflings-regular.svg
				├── glyphicons-halflings-regular.ttf
				├── glyphicons-halflings-regular.woff
				└── glyphicons-halflings-regular.woff2
	|-js/
		|-jquery-1.11.3.min.js/
		|-functions.js
	|-nusoap/
		|-lib/
			|-nusoap.php
		|-soap-serve.php
		|-amountFCurrency.php
		|-saveOrder.php
		|-totalAmountinZAR.php
	|-sql/
		|-foreing_currency.sql
```
### How to install the application

1) Copy the index.php file and the assets folder in the www directory of your webserver
2) Create a MYSQL database and import the tables and data from assets > sql > foreing_currency.sql
3) Open the soap-server.php (found in assets > nusoap) and configure the database connections parameters (Database server hostname, Database user name, Database user password and Database name) and an email address where the purchase order details can be emailed to.
4) Once all of the above is properly done, the application can be accessed by typing the following in your web browser - http://localhost/foreignCurrencyPurchase/
