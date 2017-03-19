SmushIt
==========

SmushIt is a PHP client for the popular Yahoo! image compression web service [Smush.it](http://www.smushit.com/ysmush.it/)

Basic Usage
-----------
    
    use DavGothic\SmushIt\SmushIt;
    
    include __DIR__ . '/vendor/autoload.php';
    
    $smushit = new SmushIt();
    
    // Compress a local/remote image and return the result object.
    $result = $smushit->compress('some/path/to/an/image.png');
    print_r($result);
    
    // stdClass Object
    // (
    //     [src] => http://static0.resmush.it/output/1262dc777d8b239cfdf5f528a4032f02/source.png
    //     [dest] => http://static1.resmush.it/output/a9ba82e7ba18e9482e085fadb126edad/output.png
    //     [src_size] => 455200
    //     [dest_size] => 158075
    //     [percent] => 65
    //     [format] => png
    //     [expires] => Sun, 19 Mar 2017 18:00:33 +0100
    //     [generator] => reSmush.it rev.1.4.22.20170224
    // )

Installation
------------

To install the most recent version via [composer](https://getcomposer.org/), run the following command:

    composer require davgothic/smushit

Requirements
------------

 - PHP 5.5.0+
 - PHP JSON extension
 - PHP cURL extension

Changelog
---------

- 2.0.0
  - Updated coding standards to follow PSR1/2
  - Refactored to make use of PSR4 autoloading
  - Increased required PHP version to 5.5.0

- 1.1
  - Added request throttling (Thanks [Elan Ruusamäe](https://github.com/glensc))

- 1.0
  - Initial Public Release

License
-------

MIT