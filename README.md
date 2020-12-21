# SpyMaster - Create "Spies" to access the protected and private properties of a class being tested 
SpyMaster is a small library, for use in testing, that allows access to verify the values of protected and private properties in a class that is being tested, without needing to modify the class using Reflection.

[![Build Status](https://github.com/MarkBaker/SpyMaster/workflows/main/badge.svg)](https://github.com/MarkBaker/SpyMaster/actions)
[![License](https://img.shields.io/github/license/PHPOffice/PhpSpreadsheet)](https://packagist.org/packages/markbaker/spymaster)

## Requirements
 * PHP version 7.0.0 or higher


## Installation

Using composer, either

```
composer require markbaker/spymaster
```
or add the library to your existing composer.json file, and let composer's own autoloader work its magic.

Or you can download the files from github, and include the bootstrap.php fie to enable the SpyMaster autoloader


## Usage

There are a few examples of use in the `/examples` folder.

```
// Instantiate your object
$myObject = new myObject();

// Infiltrate a read-only Spy that can view the properties of $myObject 
$spy = (new SpyMaster\SpyMaster($myObject))
    ->infiltrate();

// Access the $value property of $myObject
// Any property of $myObject can be accessed, whether it is public, protected or private
echo $spy->value;
```


```
// Instantiate your object
$myObject = new myObject();

// Infiltrate a read-write spy that can both read and modify the properties of $myObject 
$spy = (new SpyMaster\SpyMaster($myObject))
    ->infiltrate(SpyMaster\SpyMaster::SPY_READ_WRITE);

// Access the $value property of $myObject
// Any property of $myObject can be accessed, whether it is public, protected or private
echo $spy->value;
// A Read-Write Spy also allows you to set new values for those properties
$spy->value = 1000;
echo $spy->value;
```

Spies cannot unset properties, nor can they access properties that are created dynamically after the Spy is infiltrated.


## License
SpyMaster is published under the [MIT](https://github.com/MarkBaker/SpyMaster/blob/master/license.md) license
