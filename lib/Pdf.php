<?php

/**
 * User: Bryce Embry
 * Date: 3/28/2016
 * Time: 1:06 PM
 */
class Pdf
{
    private $bin = "pdftk";

    /**
     * Set path to PDFtk Server binary
     * @param $path
     * @param $bin
     */
    public function setBin($path, $bin)
    {
        $this->bin = $path . $bin;
    }
    
    public function getFields($file, $format = null)
    {
        $f = new File();
        if ($f->verifyPdf($file)) {
            $tmpfile = $f->createRandomTempFile("tmp");

            $cmd = $this->bin . " " . $file . " ";
            $cmd .= "dump_data_fields > $tmpfile";
            system($cmd);

            $result = file_get_contents($tmpfile);
            $f->cleanUp($tmpfile);

            if (!empty($result) && !empty($format)) {
                return $this->convertFieldList($result, $format);
            } else {
                return $result;
            }
        }
        throw new Exception("Unable to get fields from $file.");
    }

    public function fillForm($pdf, array $data)
    {
        // Convert data to xml file
        $f = new File();
        $xmlFile = $f->createRandomTempFile(".xml");
        $pdfOut = $f->createRandomTempFile("pdf");
        $this->createXfdf($data, $xmlFile);

        // return pdf form
        $cmd = $this->bin . " " . $pdf . " ";
        $cmd .= "fill_form " . $xmlFile . " ";
        $cmd .= "output " . $pdfOut;
        
        system($cmd);
        
        return $pdfOut;
    }

    public function groupFiles()
    {

    }

    /**
     *
     * @param $list - Original dump_data_fields output from pdftk
     * @param null $format - 'json' or 'php'
     * @return array|string
     */
    public function convertFieldList($list, $format = null)
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

        $format = strtolower($format);
        if ($format == 'json') {
            return json_encode($data);
        } else {
            return $data;
        }
    }

    /**
     * @param array $data - associative array of field keys & values
     * @param string $file - path & name for saving output file
     * @return string
     */
    public function createXfdf(array $data, $file = null)
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

        if (empty($file)) {
            return $xml;
        } else {
            return file_put_contents($file, $xml);
        }
    }
}
