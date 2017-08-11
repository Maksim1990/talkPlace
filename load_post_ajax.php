<?php

$dbconn = pg_connect("host=localhost dbname=users user=postgres password=")
  or die('Could not connect: ' . pg_last_error());
$offset=$_POST['numberOfPosts'];
$limit=$_POST['offsetPosts'];

$query = "SELECT p.name,p.message, u.image,p.created_at FROM posts AS p
                            INNER JOIN users AS u ON u.id=p.user_id order BY p.id DESC LIMIT ".$limit." OFFSET ".$offset;
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {

			$arrPosts[] = $row;	
							}
header('Content-Type: application/json');
echo json_encode($arrPosts);

// Closing connection
pg_close($dbconn);