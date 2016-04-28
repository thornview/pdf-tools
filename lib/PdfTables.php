<?php
/**
 * Created by PhpStorm.
 * User: embryb
 * Date: 4/27/2016
 * Time: 1:00 PM
 */

namespace PdfTool;


class PdfTables
{
    public function extractTableDataFromPdf($file)
    {
        $tempFile = \PdfTool\File::createRandomTempFile('.txt');

        $cmd = "java -jar /usr/bin/tabula.jar $file -o $tempFile";
        system($cmd);

        $json = self::convertCsvToJson($tempFile);
        unlink($tempFile);
        
        return $json;
    }

    private function convertCsvToJson($file)
    {
        $csv = array_map('str_getcsv', file($file));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);
        return json_encode($csv);
    }
}