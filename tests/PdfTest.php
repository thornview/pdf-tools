<?php

include "../lib/Pdf.php";
/**
 * Created by PhpStorm.
 * User: embryb
 * Date: 3/28/2016
 * Time: 1:25 PM
 */
class PdfTest extends \PHPUnit_Framework_TestCase
{
    public function testGetFields()
    {
        $pdf = new Pdf();
        $result = $pdf->getFields("3a.pdf");
        $this->assertSame("Fred", $result);
    }
}
