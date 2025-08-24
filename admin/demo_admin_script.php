<?php

require_once "dbConfig.php";

//Demo Admin credentials

$username = "araman";
$password = "123456";

$sql = "SELECT * FROM admins WHERE username = ?";

$stmt = $DB_con->prepare($sql);
$stmt->execute([$username]);

if($stmt->rowCount() > 0)
{
	echo "<h3>Admin user already exists</h3>";	
}

else
{
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	$insert_sql = "INSERT INTO admins(username, password) VALUES(?,?)";

	 $insert_stmt = $DB_con->prepare($insert_sql);

	 if($insert_stmt->execute([$username, $hashed_password]))
	 {
	 	echo "<h3>Demo Admin uiser created successfully</h3>";
	 	echo "<p><b>Username:</b>$username</p>";
	 	echo "<p><b>Password:</b>$password</p>";
	 	echo "<a href='login.php'>Go to Login</a>";
	 }

	 else
	 {
	 	echo "<h3>Failed to create admin user</h3>";
	 }
}

?>