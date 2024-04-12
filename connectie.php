<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'kassasysteem';

$mysqli = new mysqli($host, $username, $password, $dbname);

if($mysqli->connect_error) {
    echo "De connectie met de database is mislukt: " . $mysqli->connect_error;
}

?>