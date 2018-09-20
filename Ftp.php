<?php

class ftp
{
    public $url_from_derectory = '';
    public $connectFtp = '';
    /**
     * @return resource
     */
    public function connectFtp()
    {
        $open = ftp_connect(FTP_HOST, 21, 30);
        if (!ftp_login($open, FTP_USERNAME, FTP_PASSWORD)) exit("Не могу соединиться");
        return $open;
    }
    

    /**
     * ftp constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->url_from_derectory = $url;
    }

    /**
     * @return mixed
     */
    public function getDataWithDB()
    {
        $data = DB::query("SELECT * FROM data_ftp");
        return $data;
    }

    /**
     * @return array
     */
    public function getFileInDirectory()
    {
        return scandir($this->url_from_derectory);
    }

    public function insertFileAndFolder($params = array())
    {
        DB::insert('data_ftp', $params);
    }

    public function saveAndPushFileAndFolderToFtp()
    {

        foreach ($this->getFileInDirectory() as $fileAndFolder) {
            if(is_file($fileAndFolder)) {
                $this->insertFileAndFolder(array(
                    'hash' => hexdec(crc32($this->url_from_derectory.'/'.$fileAndFolder)),
                    'patch' => $this->url_from_derectory.'/'.$fileAndFolder,
                    'type' => 'file',
                    'size' => filesize($fileAndFolder),
                    'last_scan' => date("Y:m:d h:m:s"),
                    'actual' => true,
                ));
            }

            if(is_dir($fileAndFolder)) {
                $this->insertFileAndFolder(array(
                    'hash' => hexdec(crc32($this->url_from_derectory.'/'.$fileAndFolder)),
                    'patch' => $this->url_from_derectory.'/'.$fileAndFolder,
                    'type' => 'folder',
                    'size' => 0,
                    'last_scan' => date("Y:m:d h:m:s"),
                    'actual' => true,
                ));
            }
        }

    }

}