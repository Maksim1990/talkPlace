<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());

$name=$_POST['name'];
$message=$_POST['message'];
$query = "INSERT INTO users (name,message)VALUES('".$name."','".$message."')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

header('Content-Type: application/json');
echo json_encode(array(
'name'=>$name,
    'message'=>$message
));

// Closing connection
pg_close($dbconn);