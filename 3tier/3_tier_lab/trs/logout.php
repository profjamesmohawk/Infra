<html>
 <head>
  <title>TRes</title>
 </head>
 <body>

<?php
// start a session, if we don't have one
if ( ! session_id() ) @ session_start();

echo "server=".gethostname();

// check if we are logged on
if( isset($_SESSION['uid']) ){

	// Say bye-bye
	printf ("<h3> Bye-Bye %s.  Please visit again soon. </h3>\n", $_SESSION['user_name']);  

	// blow away our logon session variables
	unset($_SESSION['uid']);
	unset($_SESSION['user_name']);

}
else{
	// not logged on
	printf ("<em>Sorry Dave I can't let you do that. You're not logged on, so I can't log you off</em>\n");
}
?>
<h2>
<a href="trs.php">Login</a>
</h2>

 </body>
</html>
