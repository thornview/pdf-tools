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

    public function testCreateRandomTempFileDefault()
    {
        $file = new File();
        $file->setTempDir("../temp/");
        $result = $file->createRandomTempFile("tmp");
        $this->assertEquals(32, strlen($result));
        $this->assertFileExists($result);
        $file->cleanUp($result);
        $this->assertFileNotExists($result);

    }

    public function testGetRemoteFile()
    {
        $f = new File();
        $dest = "./remotefile.pdf";
        $url = "http://dev.thornview.com/pdftk/3a.pdf";
        $result = $f->getRemoteFile($url, $dest);
        $this->assertTrue($result);
        $this->assertFileExists("./remotefile.pdf");
        $f->cleanUp($dest);
    }

    public function testVerifyPdf()
    {
        $f = new File();
        $result = $f->verifyPdf("./3a.pdf");
        $this->assertTrue($result);
    }
}
