<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());
$offset=$_POST['numberOfPosts'];
$limit=$_POST['offsetPosts'];


$query = "SELECT name,message FROM users order BY id DESC LIMIT ".$limit." OFFSET ".$offset;
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {

			$arrPosts[] = $row;	
							}
header('Content-Type: application/json');
echo json_encode($arrPosts);

// Closing connection
pg_close($dbconn);