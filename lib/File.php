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
    private $tempDir;

    public function __construct()
    {
        $this->tempDir = $_SERVER['DOCUMENT_ROOT']."/pdf-tool/temp/";
    }
    
    public function setTempDir($dir)
    {
        $this->tempDir = $dir;
    }

    /**
     * @param $url - URL to file
     * @param $dest - Path and filename for downloaded file
     * @return bool
     * @throws Exception
     */
    public function getRemoteFile($url, $dest)
    {
        // May require allow_url_fopen to be enabled in php.ini
        $remote = file_get_contents($url);
        if (!empty($remote)) {
            file_put_contents($dest, $remote);
            return true;
        } else {
            throw new \Exception("Unable to retrieve remote file.");
        }

    }

    public function uploadFile($var, $dir, $rename = false)
    {
        if (empty($_FILES)) {
            return "ERROR";
        } else {
            if ($rename == true) {
                $dest = $this->createRandomTempFile(".pdf");
            } else {
                $filename = $_FILES[$var]['name'];
                $file = str_replace(' ', '', $filename);
                $dest = $dir . $file;
            }
            if (move_uploaded_file($_FILES[$var]['tmp_name'], $dest)) {
                return $dest;
            } else {
                return "ERROR";
            }
        }
    }

    /**
     * Create a temp file
     * @param $ext
     * @return string
     * @throws Exception
     */
    public function createRandomTempFile($ext)
    {
        if (substr($ext, 0, 1) == ".") {
            $ext = substr($ext, 1);
        }
        $name = rand(10000, 999999) . "FILE" . time() . ".$ext";
        $file = $this->tempDir . $name;
        touch($file);
        if (file_exists($file)) {
            return $file;
        } else {
            throw new Exception("Unable to create new file at " . $this->tempDir);
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
     * Confirms that file exists and has PDF extension
     * @param $file
     * @return bool
     */
    public function verifyPdf($file)
    {
        $result =  true;

        if (strtolower(substr($file, -3)) != "pdf") {
            $result = false;
        }

        return $result;
    }
}