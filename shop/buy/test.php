<?php

include("../db/dbconn2.php");
ini_set('display_errors', true);
error_reporting(E_ALL);

    $db = new DB_conn();





    $rs = $db->Execute("select * from new_table");


    echo "11111";