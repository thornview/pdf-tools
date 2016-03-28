<?php

/**
 * Manages files
 * Created by PhpStorm.
 * User: embryb
 * Date: 3/28/2016
 * Time: 1:09 PM
 */
class File
{
    private $tmpDir;
    
    public function setTempDir($dir)
    {
        $this->tempDir = $dir;
    }

    public function getRemoteFile()
    {
        
    }
    
    public function createTempFile()
    {
        
    }
    
    public function deleteFile()
    {
        
    }

    /**
     * Create a temp file
     * @param $ext
     * @return string
     * @throws Exception
     */
    public function createRandomTempFile($ext)
    {
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
    public function isPdf($file)
    {
        $result = (is_file($file)? true : false);
        $result = ((strtolower(substr($file, -3)) == "pdf") ? : false);
        return $result;
    }
}