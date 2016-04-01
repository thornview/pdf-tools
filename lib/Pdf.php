<?php
namespace PdfTool;

/**
 * User: Bryce Embry
 * Date: 3/28/2016
 * Time: 1:06 PM
 */
class Pdf
{
    /**
     * Returns the results of the pdftk dump_data_fields in
     *  requested format
     * @param string $file
     * @return array|string
     */
    public function getFields($file)
    {
        $f = new File();
        $tmpfile = $f->createRandomTempFile("tmp");

        $cmd = PDFTK . " " . $file . " ";
        $cmd .= "dump_data_fields > $tmpfile";
        system($cmd);

        $result = file_get_contents($tmpfile);
        $f->cleanUp($tmpfile);

        if (!empty($result)) {
            return $this->convertFieldList($result);
        } else {
            return Error::message("No fields found in PDF file.");
        }
    }

    /**
     * Return PDF filling form with provided data
     * @param $pdf - The PDF form to be filled out
     * @param $json - A json file of field name / value pairs
     * @return string
     */
    public function fillForm($pdf, $json)
    {
        $f = new File();
        $xmlFile = $f->createRandomTempFile("xml");
        $pdfOut = $f->createRandomTempFile("pdf");
        $data = json_decode($json, true);
        $this->createXfdf($data, $xmlFile);

        $cmd = PDFTK . " " . $pdf . " ";
        $cmd .= "fill_form " . $xmlFile . " ";
        $cmd .= "output " . $pdfOut;
        
        system($cmd);
        $f->cleanUp($xmlFile);
        return $pdfOut;
    }

    /**
     *
     * @param $list - Original dump_data_fields output from pdftk
     * @return array|string
     */
    public function convertFieldList($list)
    {
        $text = explode(PHP_EOL, $list);
        $i = 0;
        $data = array();
        foreach ($text as $line) {
              if (substr($line, 0, 3) == '---') {
                  $i += 1;
              } else {
                  $split = strpos($line, ":");
                  $key = substr($line, 0, $split);
                  $value = substr($line, $split + 2);
                  $data[$i][$key] = $value;
              }
        }
        return json_encode($data);
    }

    /**
     * @param array $data - associative array of field keys & values
     * @param string $file - path & name for saving output file
     * @return string
     */
    public function createXfdf(array $data, $file)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<xfdf xmlns="http://ns.adobe.com/xfdf/">' . "\n";
        $xml .= '<fields>' . "\n";
        foreach ($data as $field => $value) {
            $xml .= '<field name="' . $field . '">' . "\n";
            $xml .= '<value>' . $value . '</value>' . "\n";
            $xml .= '</field>' . "\n";
        }
        $xml .= '</fields>' . "\n";
        $xml .= '</xfdf>';

        return file_put_contents($file, $xml);
    }
}
