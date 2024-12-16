<?php
$servername = "mysql311.phy.lolipop.lan";
$username = "LAA1632250";
$password = "9vWqKeipemkaEzZ";
$dbname = "LAA1632250-pbl2";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
