<?php

include("../lib/File.php");

/**
 * Created by PhpStorm.
 * User: embryb
 * Date: 3/28/2016
 * Time: 2:18 PM
 */
class FileTest extends PHPUnit_Framework_TestCase
{

    public function testCreateRandomTempFile()
    {
        $file = new File();
        $file->setTempDir("../temp/");
        $result = $file->createRandomTempFile("tmp");
        $this->assertEquals(32, strlen($result));
        $this->assertFileExists($result);
        $file->cleanUp($result);
        $this->assertFileNotExists($result);

    }
}
