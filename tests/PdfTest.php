<?php
require "../bootstrap.php";
include "../lib/Pdf.php";
include "../lib/File.php";
/**
 * Created by PhpStorm.
 * User: Bryce Embry
 * Date: 3/28/2016
 * Time: 1:25 PM
 */
class PdfTest extends PHPUnit_Framework_TestCase
{
    public function testGetFieldsJson()
    {
        $pdf = new PdfTool\Pdf();
        $result = $pdf->getFields("./files/3a.pdf", "json");
        $expected = file_get_contents("./files/3a_fields.json");
        $this->assertSame($expected, $result);
    }
    
//    public function testCreateXfdf()
//    {
//        $data = array(
//            "Name" => "Ralph the Mouse",
//            "Telephone" => "Yes, we have one of those",
//            "PI State" => "Very sober"
//        );
//
//        $pdf = new PdfTool\Pdf();
//        $result = $pdf->createXfdf($data);
//        $expected = file_get_contents("./files/3a_xfdf.xml");
//        $this->assertSame($expected, $result);
//    }

    public function testfillForm()
    {
        $p = new PdfTool\Pdf();
        $data = array(
            "Name" => "Ralph the Mouse",
            "Telephone" => "Yes, we have one of those",
            "PI State" => "Very sober"
        );
        $json = json_encode($data);
        $output = $p->fillForm("./files/3a.pdf", $json);
        $this->assertFileExists($output);
        unlink($output);
    }
}
