<?php

/**
 * SmushIt - A PHP client for the Yahoo! Smush.it web service.
 *
 * @author   David Hancock <davgothic@gmail.com>
 * @author   Elan Ruusamäe <glen@delfi.ee>
 * @license  http://davgothic.com/mit-license/
 * @link     http://github.com/davgothic/SmushIt
 */
class SmushIt {

	// URL to the Smush.it web service.
	const SMUSH_URL = 'http://www.smushit.com/ysmush.it/ws.php?';

	// User agent string to set for the request.
	const USER_AGENT = 'ShushIt PHP Client (+http://github.com/davgothic/SmushIt)';

	/**
	 * @var  string  location of the image
	 */
	private $image_location;

	/**
	 * @var  resource  cURL handle
	 */
	private $curl;

	/**
	 * @var  int	Time of last request
	 */
	private $request_time;

	/**
	 * @var  int	How often it is allowed to send requests. in microseconds
	 */
	public $request_interval = 1000000;

	/**
	 * Create a new SmushIt instance.
	 *
	 * @throws  RuntimeException
	 */
	public function __construct()
	{
		if ( ! extension_loaded('json')) {
			throw new RuntimeException('The json extension was not found.');
		}

		if ( ! extension_loaded('curl')) {
			throw new RuntimeException('The cURL extension was not found.');
		}

		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($this->curl, CURLOPT_USERAGENT, self::USER_AGENT);
	}

	/**
	 * @param   string  location of the image
	 * @return  object
	 *
	 *  src       = source location of the input image
	 *  src_size  = size of the source image in bytes
	 *  dest      = temporary location of the compressed image
	 *  dest_size = size of the compressed image in bytes
	 *  percent   = how much smaller the compressed image is
	 */
	public function compress($image_location)
	{
		// Limit requests not often than request_interval microseconds
		if (!empty($this->request_time)) {
			$since_last = (microtime(1) - $this->request_time) * 1000000;
			if ($since_last < $this->request_interval) {
				// sleep it off
				usleep($this->request_interval - $since_last);
			}
		}
		$this->request_time = microtime(1);

		$this->image_location = $image_location;

		if (preg_match('/https?:\/\//', $this->image_location) == 1)
		{
			$result = $this->smush_url();
		}
		else
		{
			$result = $this->smush_file();
		}

		return $result;
	}

	/**
	 * Compress a remote image using the Smush.it web service.
	 *
	 * @return  object
	 */
	private function smush_url()
	{
		curl_setopt($this->curl, CURLOPT_URL, self::SMUSH_URL . http_build_query(array('img' => $this->image_location)));
		$json_str = curl_exec($this->curl);

		return $this->parse_response($json_str);
	}

	/**
	 * Compress a local image using the Smush.it web service.
	 *
	 * @throws  SmushItException
	 * @return  object
	 */
	private function smush_file()
	{
		if (!is_file($this->image_location) || ! is_readable($this->image_location))
		{
			throw new SmushItException('Could not read file', $this->image_location);
		}

		curl_setopt($this->curl, CURLOPT_URL, self::SMUSH_URL);
		curl_setopt($this->curl, CURLOPT_POST, TRUE);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, array('files' => '@' . $this->image_location));
		$json_str = curl_exec($this->curl);

		return $this->parse_response($json_str);
	}

	/**
	 * Parse the response from the Smush.it web service.
	 *
	 * @param   string  JSON string response from the Smush.it web service
	 * @throws  SmushItException
	 * @return  object
	 */
	private function parse_response($json_str)
	{
		$result = json_decode($json_str);

		if (is_null($result))
		{
			throw new SmushItException('Bad response received from the Smush.it service.', $this->image_location);
		}

		if (isset($result->error))
		{
			throw new SmushItException($result->error, $this->image_location);
		}

		return $result;
	}

} // End SmushIt

/**
 * SmushIt exception handler.
 */
class SmushItException extends Exception {

	/**
	 * @var  string  location of the image
	 */
	private $image;

	/**
	 * Creates a new exception.
	 *
	 * @param  string  error message
	 * @param  string  location of the image
	 */
	public function __construct($message, $image)
	{
		$this->image = $image;
		parent::__construct($message);
	}

	/**
	 * Location of the image.
	 *
	 * @return  string
	 */
	final public function getImage()
	{
		return $this->image;
	}

} // End SmushItException
