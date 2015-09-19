[![Latest Stable Version](https://poser.pugx.org/phalcana/userguide/v/stable)](https://packagist.org/packages/phalcana/userguide)
[![Build Status](https://travis-ci.org/braf/phalcana-userguide.svg?branch=master)](https://travis-ci.org/braf/phalcana-userguide)
[![Total Downloads](https://poser.pugx.org/phalcana/userguide/downloads)](https://packagist.org/packages/phalcana/userguide)
[![License](https://poser.pugx.org/phalcana/userguide/license)](https://packagist.org/packages/phalcana/userguide)

# Phalcon Userguide Module

Userguide module for the [Phalcana project](http://github.com/braf/phalcana-project) based on the one for Kohana.

## Installation

This module is installed by default with the Phalcana project by composer when dev modules are included for more
information see the [Phalcana Project](http://github.com/braf/phalcana-project).

In order for the module to be loaded into Phalcana the module needs to be added into the modules config.
This can be found in the `app/config/static.php` or in the local version `app/config/setup.php`. For example.

```php
'modules' => array(
  'email' => MODPATH.'email/',
  'userguide' => MODPATH.'userguide/',
),
```

## Basic Usage

Once the module is sucessfully installed you can access it in your project by accessing the URL at `/guide`.

## Adding To The Guide

Files for the guide are added inside each module and the system folder in `guide/module-name`. Files added are
added in the Markdown syntax. See below about Markdown for more info.

The homepage for each module should be name `index.md` and the menu structure to connect the files together
should be added in a `menu.md` file.

## API Browser

The user guide also contains an API browser assembled from DOC blocks within the code. This works much the
same as PHPDocumentor and is organised by using the main class DOC blocks sorting first by `@package` then
by `@category`. Code blocks use Markdown to format documentation for the class and each individual function.
For more details on the Markdown syntax please see below.

## Markdown

Most of the markdown parsing is handled by [PHP Markdown](https://michelf.ca/projects/php-markdown/). For
detailed information please visit the documentation. There are modifications that allow rewriting of local
links
