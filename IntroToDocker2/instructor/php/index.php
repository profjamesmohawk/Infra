<html>
 <head>
  <title>Favourite Animal</title>
 </head>
 <body>
<style>
table, th, td {
    border: 1px solid black;
    border-collapse: collapse;
}
table {	
	margin-left: 50px;
}

</style>

<h2>Which do you like more...</h2>

<?php

// connect to the DB to get animals and votes
// password should be stored more safely !!!

$mysqli = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), "animals");


if ($mysqli->connect_errno) {
	printf("Error: DB Connect failed: %s\n", $mysqli->connect_error);
    	exit();
}
else {
	printf("Debug: Connected to db\n");
}

// cast a vote, if 'animal' is set in the GET data
//
if ( isset($_GET['animal'])){
	$my_fav = $_GET['animal'];

	// note: open to SQL injection !!!
	$QS=sprintf("UPDATE votes SET votes = votes + 1 WHERE animal = '%s'", $my_fav);
	if( ! $result = $mysqli->query($QS)){
		echo "DB ERR voting:" .  $QS ;
	}
}

// get the vote counts from the DB
//
$QS="SELECT animal,votes FROM votes";
if( ! $result = $mysqli->query($QS)){
	echo "DB ERR:" . "$QS";
	exit();
}

// walk the results set, spitting one button per animal
//
printf("<br>\n");
printf("<br>\n");
printf("<table>\n");
printf("<tr><td>Animal</td><td>Votes</td></tr>\n");

while($row = $result->fetch_assoc()){
	printf("<tr>\n");
	printf("<td>%s</td>",$row['animal']);
	printf("<td>%s</td>",$row['votes']);
	printf("<td><a href=.?animal=%s >Vote: %s</td>",$row['animal'],$row['animal']);
	printf("</tr>\n");
}
printf("</table>\n");

?>




</body>
</html>
