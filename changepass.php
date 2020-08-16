<?php
session_start();
require_once('config.php');

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill the password field!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('UPDATE user set password=?')) {
	// We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$stmt->bind_param('s', $password);
	$stmt->execute();
    header('Location: logout.php');
    
    $stmt->close();
}
?>