<?

require('config.php');
if (isset($_POST['type']))
{
	$response;
	$type = $_POST['type'];
	if(strcasecmp($type, 'search') == 0)
	{
		$email = $_POST['email'];
		
		$db = new mysqli(localhost, $user, $password, $dbase);
		$query = "SELECT * FROM `users` WHERE `email` = '{$email}'";
		$resultset = $db->query($query);	
		$response['match'] = false;
		if($resultset)
		{
			
			$i = 0;
			while ($row = $resultset->fetch_object()) {
				$response[$i]['first'] = $row->first;
				$response[$i]['last'] = $row->last;
				$response[$i]['email'] = $row->email;
				$response['match'] = true;
				$i++;
			}

			$resultset->close();
			$db->next_result();
		}

		$db->close();


	}
	if(strcasecmp($type, 'create') == 0)
	{
		$first = $_POST['first'];
		$last = $_POST['last'];
		$email = $_POST['email'];
		$pw = crypt($_POST['password'], $salt);
		$age = $_POST['age'];
		$sex = $_POST['sex'];
		$zip = $_POST['zip'];
		
		$db = new mysqli(localhost, $user, $password, $dbase);
		$query = "SELECT * FROM `users` WHERE `email` = '{$email}'";
		$resultset = $db->query($query);
		$response['match'] = false;

		if ($resultset)
		{
			$i = 0;
			while ($row = $resultset->fetch_object())
			{
				$response['match'] = true;
				$response[$i]['first'] = $row->first;
				$i++;
			}


			$resultset->close();
			$db->next_result();
		}	

		if (!$response['match'])
		{
			$query = "INSERT INTO `users` (`first`, `last`, `email`, `password`, `age`, `sex`, `zip`) VALUES ('{$first}', '{$last}', '{$email}', '{$pw}', '{$age}', '{$sex}', '{$zip}')";
			echo $query;
			if ($db->query($query))
			{
				$response['success'] = true;
			}
			$headers = 'From: info@drinkup.com';

			if (mail($email, 'Welcome to drinkup!', 
				$first.', thank you for joining drinkup! You can sign in using your email address and the password that you just created! Please drink responsibly!', 
				$headers))
			{
				$response['email'] = 'sent';
			}
		}
		
		$db->close();


	}

	if(strcasecmp($type, 'login') == 0)
	{
		$email = $_POST['email'];
		$pw = crypt($_POST['password'], $salt);
		$db = new mysqli(localhost, $user, $password, $dbase);
		$query = "SELECT * FROM `users` WHERE `email` = '{$email}'";
		echo $query;
		$resultset = $db->query($query);	
		$response['match'] = false;
		if($resultset)
		{
			
			while ($row = $resultset->fetch_object()) {

				$response['match'] = true;
				if(strcasecmp($pw, $row->password) == 0)
				{
					$response['login'] = 'successfull';
				}

				else
				{
					$response['login'] = 'failed';
				}
			}

			$resultset->close();
			$db->next_result();
		}

		$db->close();


	}

	response.header('Content-Type: text/html');
	echo json_encode($response);
}
else 
{

?>

<html>
	<head>
		<title>json request</title>
	</head>
	<body>
		<form method='post' action='user.php'>
			<input type='hidden' name='type' value='search'>
			Email: <input type='email' name='email' />
			<input type='submit' value='Search'/> 
		</form>

		<br />

		<form method='post' action='user.php'>
			
			First: <input type='text' name='first' /> 
			<br />
			Last: <input type='text' name='last' />
			<br />
			Email: <input type='email' name='email' />
			<br />
			Password: <input type='password' name='password' />
			<br />
			Age: <input type='text' name = 'age' />
			<br />
			Sex: <input type='radio' name='sex' value='m'>Male</input>
				 <input type='radio' name='sex' value='f'>Female</input>
			<br />
			Zip: <input type='text' name='zip'/>
			<input type='hidden' name='type' value='create'/>
			<input type='submit' value='Create'/> 
		</form>
		<br />

		<form method='post' action='user.php'>
			<input type='hidden' name='type' value='login'>
			Email: <input type='email' name='email' />
			Password: <input type='password' name='password' />
			<input type='submit' value='Log In'/> 
		</form>

	</body>

</html>

<?

}

?>