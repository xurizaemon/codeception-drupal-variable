#Drupal Variable

Codeception module to allow testing of drupal variables.

e.g.

```php
// Assert that the target site has variable "clean_url" set to 1
$I->seeVariable("clean_url", 1);

// Set a variable.
$I->haveVariable("clean_url", 0);

// Delete a variable.
$I->dontHaveVariable("clean_url");

// Retrieve a variable value.
$value = $I->getVariable("clean_url");
```

There are three ways of accessing the variable values.

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
        - DrupalVariable
```

##Bootstrapped

```yaml
DrupalVariable:
  class: Codeception\Module\Drupal\Variable\VariableStorage\Bootstrapped
```

Required config variables:

* 'drupal_root'. This is the path the local instance of the drupal site you wish to set/get variables on.
  * e.g. `/home/sites/www.example.com`

##Direct connection

```yaml
DrupalVariable:
  class: Codeception\Module\Drupal\Variable\VariableStorage\DirectConnection
```

Required config variables:

* dsn - dsn to the instance of the drupal site db.
  * e.g. `mysql:host=localhost;dbname=drupal`
* user - the db user
* password - the db password

##Drush

```yaml
DrupalVariable:
  class: Codeception\Module\Drupal\Variable\VariableStorage\Drush
```

Required config variables:

* drush_alias - the drush alias to the site under test.
  * e.g. `@mysite.local`
