<?php
use PHPUnit\Framework\TestCase;
use FindRows\FindRows;

class FindRowsTestCase extends TestCase
{
 	public function testErrorFolder() 
  	{	
		try {
			new FindRows('aaaaaaa', '');
		} catch(\Exception $e) {
			$this->assertTrue(true);
		}
  	}

	public function testErrorAlg() 
  	{	
		$path = getcwd();
		$suf_files_folder = '/files';
		$cfg_name = 'fr.yml';
		$alg = 'md55';
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name, true, $alg);
		} catch(\Exception $e) {
			$this->assertTrue(true);
		}
  	}

  	public function testGetFiles() 
	{	
	  	$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$files = ['User.php', 'iso_8859-1.txt', 'test.txt'];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name);
			$this->assertEquals($fr->getFiles(), $files);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}  

	public function testFindEntryStr()
	{
		$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$str = 'The PHP development team announces the immediate availability of PHP 7.0.33. Five security-related 	issues were fixed in this release. All PHP 7.0 users are encouraged to upgrade to this version.';
	  	$find_str = 'PHP 7.0';
	  	$res = [[$find_str, 65], [$find_str, 139]];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name);
			$this->assertEquals($fr->findEntryStr($str, $find_str), $res);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}

	public function testtoFindStrNoHash()
	{
		$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$find_str = '31';
	  	$filename = 'iso_8859-1.txt';
	  	$info_row_test = [31, 0];
	  	$res_item = [
  			'number_row' => 25, 
  			'info_row' => [$info_row_test],
  			'str_row' => '31  DIGIT ONE                   B1  PLUS-MINUS SIGN'
	  	];
	  	$res = [$res_item];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name);
			$this->assertEquals($fr->toFindRows($filename, $find_str), $res);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}

	public function testtoFindStrHash()
	{
		$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$find_str = '97cd1b5177ea94b430e81611816eb290';
	  	$filename = 'test.txt';
	  	$res_item = [
  			'number_row' => 12, 
  			'str_row' => ' * The following block of code may be used to automatically register your',
  			'hash_str' => $find_str
	  	];
	  	$res = [$res_item];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name, true);
			$this->assertEquals($fr->toFindRows($filename, $find_str), $res);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}

	public function testtoFindStrNoHashRemote()
	{
		$url = 'https://raw.githubusercontent.com/laravel/laravel/master/app/User.php';
		$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$find_str = 'MustVerifyEmail';
	  	$info_row_test = [$find_str, 30];
	  	$res_item = [
  			'number_row' => 5, 
  			'info_row' => [$info_row_test],
  			'str_row' => 'use Illuminate\Contracts\Auth\MustVerifyEmail;'
	  	];
	  	$res = [$res_item];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name);
			$this->assertEquals($fr->toFindRowsRemote($url, $find_str), $res);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}

	public function testtoFindStrHashRemote()
	{
		$url = 'https://raw.githubusercontent.com/laravel/laravel/master/resources/js/app.js';
		$path = getcwd();
	  	$suf_files_folder = '/files';
	  	$cfg_name = 'fr.yml';
	  	$find_str = '97cd1b5177ea94b430e81611816eb290';
	  	$info_row_test = [$find_str, 30];
	  	$res_item = [
  			'number_row' => 12, 
  			'str_row' => ' * The following block of code may be used to automatically register your',
  			'hash_str' => $find_str
	  	];
	  	$res = [$res_item];
		try {
			$fr = new FindRows($path.$suf_files_folder, $path.'/'.$cfg_name, true);
			$this->assertEquals($fr->toFindRowsRemote($url, $find_str), $res);
		} catch(\Exception $e) {
			$this->assertTrue(false);
		}
	}
}