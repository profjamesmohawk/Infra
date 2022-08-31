<html>
 <head>
  <title>TRS</title>
 </head>
 <body>

<h2>Welcome to the Trivial Reservation System for COMP-10097 Systems</h2>

<?php include 'config.php' ?>

<?php
// start a session, if we don't have one
if ( ! session_id() ) @ session_start();

echo "server=".gethostname();

// do we have a username and passwd to evaluate
if ( isset($_POST['name'])){

	//check that name and passwd match

	$mysqli = new mysqli(
			$trs_db_host,
			$trs_db_uid,
			$trs_db_pw,
			$trs_db_db);
		
	if ($mysqli->connect_errno) {
    		printf("Connect failed: %s\n", $mysqli->connect_error);
    		exit();
	}
	// !!! ??? fix for sql inject
	$QS=sprintf("SELECT id FROM users WHERE name = '%s' AND passwd = '%s'",
			$mysqli->real_escape_string($_POST['name']),
			$mysqli->real_escape_string($_POST['passwd'])
			);
			
	
	if( ! $result = $mysqli->query($QS)){
		echo "DB ERR:" . "$QS";
	}
	if( $result->num_rows == 1){
		// winner, winner - record it in the session
		$result->data_seek(0);
		$row = $result->fetch_assoc();
		$_SESSION['uid']= $row['id'] ;
		$_SESSION['user_name']= $_POST['name'];
	}
	else{
		$LoginMessage = "Bad Username/Passwd Combo";
	}
	
}
else{
	$LoginMessage = "";
}


// are we logged in 
if ( !isset($_SESSION['uid']) ){
	// not logged in display login form
	echo "<form action= \"" .  $_SERVER['PHP_SELF'] . "\"" . "method=\"post\">" . "<br>";
	echo "<table>";
	echo "<tr><td>User Name:</td><td> <input type=text name=name></td></tr>\n";
	echo "<tr><td>Passwd:</td><td> <input type=password name=passwd></td></tr>\n";
	echo "<tr><td align=right colspan=2><button type=sumbit name=login>Login</button> </td></tr>";
	echo "</table>";
	echo "</form>";
	if ($LoginMessage != "") printf ("<em>%s</em>\n", $LoginMessage);
}
else{
	echo "<hr>";
	echo "<strong>You are logged on as: ". $_SESSION['user_name'] ;
	echo "</strong><em> Not ". $_SESSION['user_name']  ;
	echo '? <a href="logout.php">click here to log out</a>';
	echo "</em>";
	echo "<hr>";

	// list the things we can do
	//echo '<hr> <a href="reservations.php">Reservations</a>';
	

	// get all the reservations we have in an array, with a keys of res_date:system_name and data values of user_name
	//
	
	//connect to the DB
	$mysqli = new mysqli(
			$trs_db_host,
			$trs_db_uid,
			$trs_db_pw,
			$trs_db_db);
	if ($mysqli->connect_errno) {
    		printf("Connect failed: %s\n", $mysqli->connect_error);
    		exit();
	}

	// run the query	
	$QS = 		"SELECT reservations.res_date, systems.name as system_name, users.name as user_name ".
			"FROM reservations, systems, users " .
			"WHERE reservations.user_id = users.id and systems.id = reservations.system_id;";

	if( ! $result = $mysqli->query($QS)){
		echo "DB ERR:" . $QS;
	}
	while($row = $result->fetch_assoc()){
		$Key = $row['res_date'] .":" . $row[system_name];

		$ReservationsByDateAndSystem[$Key] = $row[user_name];
	}

	// end load reservations array ////////////////////////

	// render a table for every date in the range we are interested in

	echo "<hr>";

	$Months= array ("2021-10","2021-11","2021-12");
	$Systems = array("sut_1","sut_2","sut_3","sut_4","sut_5"); // !!! this should be populated from db


	foreach ( $Months as $Month){
		echo "<br>";
		echo "<strong>". $Month . "</strong>";
		echo "<table border=1>\n";
		// spit the heading
		echo "<tr>";
		echo "<td></td>";
		foreach ( $Systems as $System){
			echo "<td>". $System ."</td>";
		}
		echo "</tr>";

		// one row per day in the month
		list( $Year, $MonthNum ) =  explode ("-",$Month);
		$DaysInMonth = cal_days_in_month(CAL_GREGORIAN,$MonthNum,$Year);
		for($i=1;$i<=$DaysInMonth;$i++){
			echo "<tr>";
			echo "<td>";
			$Day = sprintf("%s-%02d",$Month,$i);
			echo "$Day";
			echo "</td>";
			// one cell per system 
			foreach ( $Systems as $System){
				echo "<td align=center>";
				// check for res
				$Key = $Day . ":" . $System;
				if( isset($ReservationsByDateAndSystem[$Key])){
					echo "<strong>" . $ReservationsByDateAndSystem[$Key] ."</strong>";
					// if it is current users reservations, spit a del link
						if( $ReservationsByDateAndSystem[$Key] == $_SESSION['user_name']){
						echo '<br><a href="deladd.php?action=del&key='. $Key .'"><em>Delete</em></a>';
					}
				}
				else{
					// no reservation, so spit a booking link
						echo '<a href="deladd.php?action=add&key='. $Key .'"><em>Reserve</em></a>';
				}
				echo "</td>";
			}	
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
	
}
?>





</body>
</html>
