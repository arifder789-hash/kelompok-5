<?php
header('Content-Type: text/plain');
$conn = new mysqli('localhost', 'root', '', 'rameza_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$res = $conn->query("DESCRIBE pelanggan");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error querying table structure: " . $conn->error;
}
