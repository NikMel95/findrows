<?php
namespace FindRows;

class RemoteFile {
	private $url;
	private $path;
	private $name;

	public function __construct($url, $path) 
	{
		if(!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new \Exception("$url is a valid URL");
		}
		if(!is_dir($path)) {
			mkdir($path);
		}
		$this->url = $url;
		$this->path = $path;
	}

	public function getStatus() 
	{
		try {
			if (preg_match('/^HTTP\/1\.[01] (\d\d\d)/', get_headers($this->url, true)[0], $matches)) {
		  		return $matches[1];
			} else {
				return 0;
			}
		} catch(\Exception $e) {
			echo $e->getMessage();
			return 0;
		}
	}

	public function getSize() 
	{
		try {
			return get_headers($this->url, true)['Content-Length'];
		} catch(\Exception $e) {
			return 0;
		}
	}

	public function downloadFile() 
	{
		$url = $this->url;
		$filename = basename($url);
		$this->name = $filename;
		file_put_contents($this->path.'/'.$filename, file_get_contents($url));
		return $filename;
	}

	public function deleteFile() 
	{
		unlink($this->path.'/'.$this->name);
	}
}