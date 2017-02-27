<?php

namespace Nitria;

class File
{

    /**
     * absolute path to file
     * @var string
     */
    protected $absoluteFileName;

    /**
     * @param string $absoluteFileName
     */
    public function __construct(string $absoluteFileName)
    {
        $this->absoluteFileName = rtrim($absoluteFileName, '/');
    }

    /**
     * @return string
     */
    public function getAbsoluteFileName() : string
    {
        return $this->absoluteFileName;
    }

    /**
     * @return string
     */
    public function getFileName() : string
    {
        return basename($this->absoluteFileName);
    }

    /**
     * tells if the file is a file
     * @return bool
     */
    public function isFile() : bool
    {
        return is_file($this->absoluteFileName);
    }

    /**
     * tells if the file is a directory
     * @return bool
     */
    public function isDir() : bool
    {
        return is_dir($this->absoluteFileName);
    }

    /**
     * tries to delete a file
     * @return bool
     */
    public function delete() : bool
    {
        if (!$this->exists()) {
            return false;
        }
        return unlink($this->absoluteFileName);
    }

    /**
     *
     */
    public function deleteRecursively()
    {
        if ($this->isDir()) {
            $fileList = $this->scanDir();

            foreach ($fileList as $file) {
                if ($file->isDir()) {
                    $file->deleteRecursively();
                } else {
                    $file->delete();
                }
            }
            rmdir($this->absoluteFileName);
        }

    }

    /**
     * tells if the file exists
     * @return bool
     */
    public function exists() : bool
    {
        return file_exists($this->absoluteFileName);
    }

    /**
     * creates needed directories recursively
     *
     * @param int $mode
     *
     * @return bool
     */
    public function createDir($mode = 0777) : bool
    {
        return is_dir($this->absoluteFileName) || mkdir($this->absoluteFileName, $mode, true);
    }

    /**
     * tells if the file is of type
     *
     * @param string $type
     *
     * @return bool
     */
    public function isType($type) : bool
    {
        return StringUtil::endsWith($this->absoluteFileName, $type);
    }

    /**
     * scans a directory and returns a list of Files
     * @return File[]
     */
    public function scanDir()
    {
        // only possible in directories
        if (!$this->isDir()) {
            return null;
        }

        $fileList = [];
        $fileNameList = scandir($this->absoluteFileName);
        foreach ($fileNameList as $fileName) {
            // do not add . and ..
            if ($fileName === "." or $fileName === "..") {
                continue;
            }
            $absoluteFileName = $this->absoluteFileName . "/" . $fileName;
            $fileList [] = new File ($absoluteFileName);
        }

        return $fileList;
    }

    /**
     * Finds the first occurence of the given filename
     *
     * @param string $fileName
     *
     * @return null|File
     */
    public function findFile($fileName)
    {
        // only possible in directories
        if (!$this->isDir()) {
            return null;
        }

        foreach ($this->scanDir() as $file) {
            if ($file->getFileName() === $fileName) {
                return $file;
            }

            if ($file->isDir()) {
                $result = $file->findFile($fileName);
                if ($result !== null) {
                    return $result;
                }
            }

        }
        return null;
    }

    /**
     * @param $fileSuffix
     *
     * @return File[]
     */
    public function findFileList($fileSuffix)
    {
        if (!$this->isDir()) {
            return null;
        }

        $result = [];
        foreach ($this->scanDir() as $file) {

            if ($file->isDir()) {
                $result = array_merge($result, $file->findFileList($fileSuffix));
                continue;
            }
            if ($file->isType($fileSuffix)) {
                $result[] = $file;
            }
        }
        return $result;
    }

    /**
     * loads the file as XSLT Processor
     * @return \XSLTProcessor
     */
    public function loadAsXSLTProcessor()
    {
        $xsl = new \XSLTProcessor();
        $xsl->importStylesheet($this->loadAsXML());
        return $xsl;
    }

    /**
     * loads the file as XML DOMDocument
     * @return \DomDocument
     * @throws \Exception
     */
    public function loadAsXML()
    {
        $xml = new \DomDocument ();
        libxml_use_internal_errors(true);
        $result = $xml->load($this->absoluteFileName);
        if ($result) {
            return $xml;
        }
        $e = new \Exception(libxml_get_errors());
        libxml_clear_errors();
        throw $e;
    }

    /**
     * @return array
     */
    public function loadAsJSONArray()
    {
        return json_decode($this->getContents(), true);
    }

    /**
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->absoluteFileName);
    }

    /**
     * @param $data
     *
     * @return void
     */
    public function putContents($data)
    {
        file_put_contents($this->absoluteFileName, $data);
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->absoluteFileName;
    }

}
