<?php
namespace PdfTool;
/**
 * Created by PhpStorm.
 * User: embryb
 * Date: 4/1/2016
 * Time: 6:59 AM
 */

class Error
{
    
    public static function message($text)
    {
        $arr = array(
            "ERROR" => $text
        );
        return json_encode($arr);
    }
}