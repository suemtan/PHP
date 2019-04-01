<?php  //sqltest_SecureCoding.php
	require_once 'login.php';
	
	$conn = new mysqli($hn, $un, $pw, $db);
	if($conn->connect_error) die($conn->connect_error);
	
	//delete
	if (isset($_POST['delete']) && isset($_POST['email']))
	{
		$email = get_post($conn,'email');
		$query = "DELETE FROM users_2 WHERE email='$email'";
		$result = $conn->query($query);
		if (!$result) echo "DELETE failed: $query<br>" . 
			$conn->error . "<br><br>";
	}
	//insertion
	if (isset($_POST['name']) && isset($_POST['email']) &&
	isset($_POST['role']) && isset($_POST['pass']))
	{
		$name = get_post($conn, 'name');
		$email = get_post($conn, 'email');
		$role = get_post($conn, 'role');
		$pass = get_post($conn, 'pass');
		
		$query = "INSERT INTO users_2 (name, email, role, pass) VALUES" .
			"('$name', '$email', '$role', '$pass')";
		$result = $conn->query($query);

		if (!$result) echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
	}
	//form
	echo <<<_END
	<form action="sqltest_SecureCoding.php" method="post">
	<pre>
		Name <input type="text" name="name">
		Email <input type="text" name="email">
		Role <input type="text" name="role">
		Pass <input type="text" name="pass">
			
		<input type="submit" value="ADD RECORD">
	</pre>
	</form>
	_END;

	//displaying records from the database
	$query = "SELECT * FROM users_2";
	$result = $conn->query($query);
	if (!$result) die ("Database access failed: " . $conn->error);

	$rows = $result->num_rows;

	for ($j = 0 ; $j < $rows ; ++$j)
	{
		$result->data_seek($j);
		$row = $result->fetch_array(MYSQLI_NUM);
		echo <<<_END
	<pre>
	Name $row[0]
	Email $row[1]
	Role $row[2]
	Pass $row[3]
	id $row[4]
	</pre>
	
	<form action="sqltest_SecureCoding.php" method="post">
	<input type="hidden" name="delete" value="yes">
	<input type="hidden" name="email" value="$row[1]">
	<input type="submit" value="DELETE RECORD"></form>
	_END;
	}

	$result->close();

	$conn->close();

	/*	real_escape_string method of the connection object to 
	strip out any characters that a hacker may have inserted in order 
	to break into or alter your database */
	
	function get_post($conn, $var)
	{	
		if(get_magic_quotes_gpc()) $var = stripslashes($string);
		return $conn->real_escape_string($_POST[$var]);
	}


?>