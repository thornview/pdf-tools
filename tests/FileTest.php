<?php
include("../bootstrap.php");
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
        $file = new PdfTool\File();
        $result = $file->createRandomTempFile("tmp");
        $this->assertSame("fred", $result);
        $this->assertFileExists($result);
        $file->cleanUp($result);
        $this->assertFileNotExists($result);

    }

    public function testVerifyPdf()
    {
        $f = new PdfTool\File();
        $result = $f->verifyPdf("./3a.pdf");
        $this->assertTrue($result);
    }
}
