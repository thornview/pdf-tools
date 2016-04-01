<?php
namespace PdfTool;

/**
 * Manages files
 * Created by PhpStorm.
 * User: embryb
 * Date: 3/28/2016
 * Time: 1:09 PM
 */
class File
{
    /**
     * Get posted file and move from php temp dir to designated location
     * @param string $var Name of the upload field
     * @param bool $save
     * @return string
     */
    public function uploadFile($var, $save = false)
    {
        if (empty($_FILES)) {
            return Error::message("Not provided with a file to be uploaded.");
        } elseif ($this->verifyPdf($_FILES[$var]['tmp_name']) === false) {
            return Error::message("PDFTool will only allow upload of PDF files.");
        } else {
            if ($save == false) {
                return $_FILES[$var]['tmp_name'];
            } else {
                $filename = $_FILES[$var]['name'];
                $file = strtolower(str_replace(' ', '', $filename));
                $dest = FORM_PATH . $file;
                if(move_uploaded_file($_FILES[$var]['tmp_name'], $dest)) {
                    return $dest;
                } else {
                    return Error::message("Error saving uploaded file to server");
                }
            }
        }
    }

    /**
     * Create a temp file
     * @param $ext
     * @return string
     */
    public function createRandomTempFile($ext)
    {
        if (substr($ext, 0, 1) == ".") {
            $ext = substr($ext, 1);
        }
        $name = rand(10000, 999999) . "FILE" . time() . ".$ext";
        $file = TEMP_PATH . $name;
        touch($file);
        if (file_exists($file)) {
            return $file;
        } else {
            return Error::message("Unable to create new file at " . TEMP_PATH);
        }
    }

    /**
     * Erases temp file
     * @param $file
     */
    public function cleanUp($file)
    {
        unlink($file);
    }

    /**
     * Checks mime type of file to assure it's a PDF
     * @param $file
     * @return bool
     */
    public function verifyPdf($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = strtolower(finfo_file($finfo, $file));
        if (strpos($mime, "pdf") === false) {
            return false;
        } else {
            return true;
        }
    }
}