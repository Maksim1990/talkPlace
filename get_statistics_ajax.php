<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());


$query = "SELECT COUNT(id) as post_quantity FROM posts";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$post_quantity = pg_fetch_array($result, null, PGSQL_ASSOC);

$query = "SELECT COUNT(id) as users_quantity FROM users";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$users_quantity = pg_fetch_array($result, null, PGSQL_ASSOC);

$query = "SELECT name,created_at FROM posts ORDER BY id DESC LIMIT 1";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$row = pg_fetch_array($result, null, PGSQL_ASSOC);
$last_post_user=$row['name'];
$last_post_created_at=$row['created_at'];

header('Content-Type: application/json');
echo json_encode(array(
'post_quantity'=>$post_quantity,
'users_quantity'=>$users_quantity,
'last_post_user'=>$last_post_user,
'last_post_created_at'=>$last_post_created_at,
));

// Closing connection
pg_close($dbconn);