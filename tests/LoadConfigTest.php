<?php
use PHPUnit\Framework\TestCase;
use FindRows\LoadConfig;

class LoadConfigTestCase extends TestCase
{   
    public function testErrorConfig()
    {
    	try {
    		new LoadConfig('/asd/asd/asd/y.yml');
    	} catch(\Exception $e) {
    		$this->assertTrue(true);
    	}
    }

    public function testConfigSize()
    {
    	$test_size = 5;
    	try {
    		$cfg = new LoadConfig(getcwd().'/fr.yml');
    		$this->assertEquals($cfg->getConfigValue('size'), $test_size);
    	} catch(\Exception $e) {
    		$this->assertTrue(false);
    	}
    }

    public function testConfigType()
    {
    	$test_type = 'text/plain text/php text/x-php text/javascript text/html text/css';
    	try {
    		$cfg = new LoadConfig(getcwd().'/fr.yml');
    		$this->assertEquals($cfg->getConfigValue('type'), $test_type);
    	} catch(\Exception $e) {
    		$this->assertTrue(false);
    	}
    }
}
