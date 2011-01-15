SmushIt
==========

SmushIt is a PHP client for the popular Yahoo! image compression web service [Smush.it](http://www.smushit.com/ysmush.it/)

Basic Usage
--------------------

	// Require the SmushIt source file
	require_once 'smushit.php';

	// Initialise the Smushit client
	$smushit = new SmushIt();

	// Compress remote image and return result object
	$remote_result = $smushit->compress('http://www.example.com/some/path/to/an/image.jpg');
	print_r($remote_result);
	//	stdClass Object
	//	(
	//		[src] => http://www.example.com/some/path/to/an/image.jpg
	//		[src_size] => 22957
	//		[dest] => http://smushit.zenfs.com/results/50c57e58/smush/image.jpg
	//		[dest_size] => 22519
	//		[percent] => 1.91
	//	)

	// Compress local image and return result object
	$local_result = $smushit->compress('/some/path/to/an/image.jpg');
	print_r($local_result);
	//	stdClass Object
	//	(
	//		[src] => image.jpg
	//		[src_size] => 22957
	//		[dest] => http://smushit.zenfs.com/results/50c57e58/smush/image.jpg
	//		[dest_size] => 22519
	//		[percent] => 1.91
	//	)



Requirements
--------------------

 - PHP 5.2+
 - PHP JSON extension
 - PHP cURL extension

License
--------------------

SmushIt is free and licensed under the [MIT license](http://davgothic.com/mit-license/)