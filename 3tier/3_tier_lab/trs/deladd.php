<html>
 <head>
  <title>TRS</title>
 </head>
 <body>


<?php include 'config.php' ?>


<?php
// start a session, if we don't have one
if ( ! session_id() ) @ session_start();

echo "server=". gethostname();

// check if we are logged on
if ( !isset($_SESSION['user_name']) ){
	echo "You cheeky monkey - log in!";
	echo " </body> </html>";
	exit;
}

// connect to the DB
$mysqli = new mysqli(
		$trs_db_host,
		$trs_db_uid,
		$trs_db_pw,
		$trs_db_db);
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
    	exit();
}

//  Lookup the System ID
//
$DeleteKey =  htmlspecialchars($_GET['key']); // key in format YYYY-MM-DD:<SystemName>
list ($ResDate, $SystemName ) = explode (":",$DeleteKey);

$QS = "SELECT id FROM systems WHERE name = '" . $mysqli->real_escape_string($SystemName) . "'";

if( ! $result = $mysqli->query($QS)){
	echo "DB ERR:" .  $QS ;
}
if( $result->num_rows == 1){
	// winner, winner - record it in the session
	$result->data_seek(0);
	$row = $result->fetch_assoc();
	$SystemID = $row['id'] ;
}
else{
	echo  "Unexpected error, " . $QS;
	exit;
}

// Do the delete or the add 
//
if(htmlspecialchars($_GET['action']) == "del"){
$QS= sprintf("DELETE FROM reservations WHERE res_date = '%s' AND user_id = '%s' AND system_id = '%s'",
		$ResDate,
		$_SESSION['uid'],
		$SystemID);

if( ! $result = $mysqli->query($QS)){
	echo "DB ERR:" .  $QS ;
}

echo "<h2>You just gave up your reservation for ". $SystemName . " on " . $ResDate . " <h2>";
echo "<h3><em>Thanks for sharing </em></h3>";
}
elseif ( htmlspecialchars($_GET['action'])== 'add'){
	$QS=sprintf("INSERT INTO reservations (res_date,user_id,system_id) VALUES ('%s','%d','%d')",
			$mysqli->real_escape_string($ResDate),
			$_SESSION['uid'],
			$SystemID);
	if( ! $result = $mysqli->query($QS)){
		echo "DB ERR:" .  $QS ;
	}
	echo "<h2>Congratulations, you have booked ". $SystemName . " for " . $ResDate ."</h2>";
}
else{
	echo "unexpect error, bad or missing action";
}

echo '<a href="trs.php">Back to Reservations</a>';
?>
</body>
</html>
