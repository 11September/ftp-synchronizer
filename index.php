<?php

require_once 'meekrodb.2.3.class.php';
require_once 'config.php';
require_once 'Ftp.php';

$data = new Ftp(URL_DERECTORY);
$data->saveAndPushFileAndFolderToFtp();
