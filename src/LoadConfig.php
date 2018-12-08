<?php
namespace FindRows;

use Symfony\Component\Yaml\Yaml;

class LoadConfig
{
	private $config;

    public function __construct($config_path)
    {
    	try {
			$config = Yaml::parseFile($config_path);
			$this->config = $config;			
		}
		catch(\Exception $e) {
			throw new \Exception('Config not found'.$config_path);
		}    
    }

    public function getConfigValue($key) 
    {
    	return $this->config[$key];
    }
}