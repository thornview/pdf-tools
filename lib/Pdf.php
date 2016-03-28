<?php

/**
 * Created by PhpStorm.
 * User: embryb
 * Date: 3/28/2016
 * Time: 1:06 PM
 */
class Pdf
{

    private $bin;

    /**
     * Set path to PDFtk Server binary
     * @param $path
     * @param $bin
     */
    public function setBin($path, $bin)
    {
        $this->bin = $path . $bin;
    }

    
    public function getFields($file)
    {
        $f = new File();
        if ($f->isPdf($file)) {
            $tmpfile = $f->createRandomTempFile("tmp");

            $cmd = $this->bin . " " . $file . " ";
            $cmd .= "dump_data_fields > $tmpfile";
            shell_exec($cmd);

            $result = file_get_contents($tmpfile);
            $f->cleanUp($tmpfile);

            if (!empty($result)) {
                return $result;
            }
        }
        throw new Exception("Unable to get fields from $file.");
    }

    public function fillForm()
    {

    }

    public function groupFiles()
    {

    }
}