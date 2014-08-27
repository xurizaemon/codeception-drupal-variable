#Drupal Variable

Codeception module to allow testing of drupal variables. There are three ways of accessing the variable values.

* Bootstrapped - bootstrap the locally installed drupal instance and use variable_get/set().
* DirectConnection - use PDO to query the database directly.
* Drush - use drush to get/set variables.

#Install

Install using composer, using git repository (for now).

```
"repositories": [
      {
          "type": "vcs",
          "url": "git@bitbucket.org:dopey/codeception-drupal-variable.git"
      },
"require": {
     "ixisandyr/codeception-drupal-variable": "@dev",
   },
```
#Configure

Add 'DrupalVariable' module to the suite configuration.

```
class_name: AcceptanceTester
modules:
    enabled:
        - AcceptanceHelper
```

##Bootstrapped

Required config variables:

* 'drupal_root'. This is the path the local instance of the drupal site you wish to set/get variables on.
  * e.g. /home/sites/www.example.com

##Direct connection

Required config variables:

* dsn - dsn to the instance of the drupal site db.
  * e.g. mysql:host=localhost;dbname=drupal
* user - the db user
* password - the db password

##Drush

Required config variables:

* drush_alias - the drush alias to the site under test.
  * e.g. @mysite.local
