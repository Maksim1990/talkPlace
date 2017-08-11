<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());

$name=$_POST['name'];
$message=$_POST['message'];
$user_id=$_POST['user_id'];
$created_at=  date('Y-m-d H:iS');
$query = "INSERT INTO posts (name,message,user_id,created_at)VALUES('".$name."','".$message."','".$user_id."','".$created_at."')";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

header('Content-Type: application/json');
echo json_encode(array(
'name'=>$name,
'message'=>$message,
'created_at'=>$created_at
));

// Closing connection
pg_close($dbconn);