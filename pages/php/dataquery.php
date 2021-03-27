<?php
require 'dbc.php';

$sth = mysqli_query($conn, "SELECT");
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
    $rows[] = $r;
}

echo json_encode($rows);