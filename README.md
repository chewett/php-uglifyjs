php-uglifyjs
============

This library is a wrapper around the nodejs [uglifyjs](https://github.com/mishoo/UglifyJS2) script for PHP.

Usage
-----

```php
$ug = new JSUglify();
$output = $ug->uglify(["somefile.js", "secondfile.js"], "output.js");
```

Given an array of input files and an output file location it will minimise the javascript.
Options can be passed in as a third parameter

```php
$ug = new JSUglify();
$output = $ug->uglify(["somefile.js", "secondfile.js"], "output.js", ['compress' => '']);
```

Here the compress option is given and passed into the uglifyjs command line string as a flag `--compress`

Installation
------------

This can be included in your composer project by running

`composer require chewett/php-uglifyjs`

Then running `composer update` will update your composer lock file to include and download this new dependency. 

Tests
-----

Tests can be run with the phpunit test runner using the provided phpunit.xml file.

License
-------

This is licensed under the MIT license. For more information see the [LICENSE](LICENSE) file.
