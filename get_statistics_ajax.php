<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());


$query = "SELECT COUNT(id) as post_quantity FROM posts";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$post_quantity = pg_fetch_array($result, null, PGSQL_ASSOC);

$query = "SELECT COUNT(id) as users_quantity FROM users";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$users_quantity = pg_fetch_array($result, null, PGSQL_ASSOC);

header('Content-Type: application/json');
echo json_encode(array(
'post_quantity'=>$post_quantity,
'users_quantity'=>$users_quantity
));

// Closing connection
pg_close($dbconn);