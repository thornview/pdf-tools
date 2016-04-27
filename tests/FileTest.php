<?php
include("../bootstrap.php");
include("../lib/File.php");

/**
 * Author: Bryce Embry
 * Date: 3/28/2016
 */
class FileTest extends PHPUnit_Framework_TestCase
{

    public function testCreateRandomTempFileDefault()
    {
        $file = new PdfTool\File();
        $result = $file->createRandomTempFile("tmp");
        $this->assertSame("fred", $result);
        $this->assertFileExists($result);
        unlink($result);
        $this->assertFileNotExists($result);

    }

    public function testVerifyPdf()
    {
        $f = new PdfTool\File();
        $result = $f->verifyPdf("./3a.pdf");
        $this->assertTrue($result);
    }
}
