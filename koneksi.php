<?php
$serverName = "ACER\\SQLEXPRESS";
$connectionOptions = [
    "Database" => "DbPerpus",
    "Uid" => "sa",
    "PWD" => "perpus123",
    "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

?>