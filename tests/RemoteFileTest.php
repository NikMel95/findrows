<?php
use PHPUnit\Framework\TestCase;
use FindRows\RemoteFile;

class RemoteFileTestCase extends TestCase
{   
    public function testErrorUrl()
    {
      $path = getcwd().'/files/remote';
      try {
        new RemoteFile('aaaaaaaaaa', $path);
      } catch(\Exception $e) {
        $this->assertTrue(true);
      }
    }

    public function testGetStatusFile()
    {
      $url = 'https://raw.githubusercontent.com/laravel/laravel/master/app/User.php';
      $path = getcwd().'/files/remote';
      $status = 200;
      try {
        $rf = new RemoteFile($url, $path);
        $this->assertEquals($rf->getStatus(), $status);
      } catch(\Exception $e) {
        $this->assertTrue(false);
      }
    }

    public function testGetSizeFile()
    {
      $url = 'https://raw.githubusercontent.com/laravel/laravel/master/app/User.php';
      $path = getcwd().'/files/remote';
      $size = 558;
      try {
        $rf = new RemoteFile($url, $path);
        $this->assertEquals($rf->getSize(), $size);
      } catch(\Exception $e) {
        $this->assertTrue(false);
      }
    }

    public function testDownloadFile()
    {
      $url = 'https://raw.githubusercontent.com/laravel/laravel/master/app/User.php';
      $path = getcwd().'/files/remote';
      $filename = 'User.php';
      try {
        $rf = new RemoteFile($url, $path);
        $rf->downloadFile();
        if(file_exists($path.'/'.$filename) && filesize($path.'/'.$filename) > 0) {
          $this->assertTrue(true);
        } else {
          $this->assertTrue(false);
        }
      } catch(\Exception $e) {
        $this->assertTrue(false);
      }
    }

    public function testDeleteFile()
    {
      $url = 'https://raw.githubusercontent.com/laravel/laravel/master/app/User.php';
      $path = getcwd().'/files/remote';
      $filename = 'User.php';
      try {
        $rf = new RemoteFile($url, $path);
        $rf->downloadFile();
        $rf->deleteFile();
        if(!file_exists($path.'/'.$filename)) {
          $this->assertTrue(true);
        }
      } catch(\Exception $e) {
        $this->assertTrue(false);
      }
    }
}
