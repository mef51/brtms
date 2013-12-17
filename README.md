brtms
=====

An online tournament management system made for Ottawa`s Battle Royale LAN Party.

* Folder 'a': All the scripts that process AJAX requests.
* Folder 'l': Bunch of 'library' scripts, useless by themselves.
* Folder 't': Hidden administrative pages.
* Folder 'style': contains the bottom*.inc/top*.inc theme files, CSS files, theme images, and JavaScript files.
* The two script_raw.p?.js files do most of the dynamic client-side stuff (p1 for tournaments.php, p2 for everything else).  P1 is not the prettiest...
* The root contains all the main PHP files for pages.

setup
======

* Install dependencies:
  * `sudo apt-get install php5`
  * `sudo apt-get install mysql-server`

* Write a config file `config.inc.php` at the root of the repository
Example:

```php
<?php

#define('DEBUG', true);

$config['instance'] = 'someInstanceName';
$config['SALT'] = 'someSalt';
$config['ROOT'] = '';

$config['DBHOST'] = 'hostname';
$config['DBUSER'] = 'username';
$config['DBPASS'] = 'password';
$config['DBNAME'] = 'dbname';
```

* `make`

