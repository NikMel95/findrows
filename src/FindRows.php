<?php
namespace FindRows;

class FindRows {
	
	private $path_folder;
	private $type = 'text/plain text/php text/x-php text/javascript text/html text/css';
	private $size = 5;
	private $folder_remote = 'remote';
	private $config;
	private $hash;
	private $algorithm;

	public function __construct($files_folder, $config_path, $hash = false, $hash_alg = 'md5') 
	{

		if(is_dir($files_folder)) {			
			$this->path_folder = $files_folder;	
		} else {
			throw new \Exception('Folder not found');
		}
		try {
			$config = new LoadConfig($config_path);
			if($config->getConfigValue('size')) {
				$this->size = $config->getConfigValue('size');
			}
			if($config->getConfigValue('type')) {
				$this->type = $config->getConfigValue('type');
			}
			$this->config = true;
		} catch(\Exception $e) {
			$this->config = false;
		}
		if($hash) {
			if(in_array($hash_alg, hash_algos())) {
				$this->algorithm = $hash_alg;
				$this->hash = true;
			} else {
				throw new \Exception('Algorithm hash not found');
			}
		} else {
			$this->hash = false;
		}
	}

	public function getFiles() 
	{
		$files = [];
		$path = $this->path_folder;
		$cdir = scandir($path); 	
		foreach ($cdir as $key) { 
			if(is_file($path.'/'.$key)) {
				$files[] = $key;
			}
		}
		return $files;
	}

	public function findRows($path, $substr) 
	{
		$rows = [];
		try {
			$lines = file($path);
			foreach ($lines as $ln => $line) {
				$cur_line = preg_replace("/[\n\r]/","", $line);
				if($this->hash) {
					$hash = hash($this->algorithm, $cur_line);
					if(hash_equals($hash, $substr)) {
						$rows[] = [
							'number_row' => $ln,
							'str_row'    => $cur_line,
							'hash_str'   => $hash
						];
					}
				} else {
					$row_res = $this->findEntryStr($cur_line, $substr);
					if(!empty($row_res)) {
						$rows[] = [
							'number_row' => $ln,
							'info_row'	 => $row_res,
							'str_row'    => $cur_line
						];
					}
				}
			}	
		} catch(\Exception $e) {
			throw new \Exception('File not found'.$e->getMessage());
		}
		return $rows;
	}

	public function toFindRows($file_name, $substr, $remote = false) 
	{
		$res = [];
		$path = $this->path_folder;
		if($remote) {
			$full_path = $path.'/'.$this->folder_remote.'/'.$file_name; 
		} else {
			$full_path = $path.'/'.$file_name; 	
		}
		if(!is_file($full_path)) {
			throw new \Exception('File not found');
		}
		if(empty($substr)) {
			throw new \Exception('String empty');		
		} 
		$mime_type = mime_content_type($full_path);
		if(!in_array($mime_type, explode(' ', $this->type))) {
			throw new \Exception('Unsupported file format');
		}
		if(filesize($full_path) > $this->size * 1024 * 1024) {
			throw new \Exception('File size limit exceeded');
		}
		$res = $this->findRows($full_path, $substr);
		return $res;
	}

	public function findEntryStr($str, $substr) 
	{
		$offset = 0;
	    $res = [];
	    while (($pos = strpos($str, $substr, $offset)) !== FALSE) {
	        $offset = $pos + 1;
	        $res[]  = [$substr, $pos];
	    }
	    return $res;
	}

	public function toFindRowsRemote($url, $str) 
	{
		$rf = new RemoteFile($url, $this->path_folder.'/'.$this->folder_remote);
		$file_size = $rf->getSize();
		if($rf->getStatus() != 200)
			throw new \Exception('File not found');  	 
		if($file_size > $this->size * 1024 * 1024 && $file_size != 0)
			throw new \Exception('File size limit exceeded');  	
		$filename = $rf->downloadFile();
		try {
			$rows = $this->toFindRows($filename, $str, true);	
		} catch(ConfigNotFoundException $e) {
		}
		$rf->deleteFile();
		return $rows;
	}
}