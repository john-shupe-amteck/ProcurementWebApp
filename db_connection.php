<?php

function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = 'Bowenbees1';
    $db = "procurementdatabase";

    $conn = new mysqli($dbhost, $dbuser,$dbpass,$db) or die("Connec failed: %s\n". $conn -> error);

    return $conn;
}