# Thelia Mail Catcher

This module catches all mails sent from Thelia and redirect them to the admin emails (registered to receive notifications) in the store configuration.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is TheliaMailCatcher.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/thelia-mail-catcher-module:~0.1
```

## Usage

You just have to activate this module.