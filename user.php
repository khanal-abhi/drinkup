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

			$response['results'] = $i;

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
		$pw = crypt($_POST['pw'], $salt);
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
		$pw = crypt($_POST['pw'], $salt);
		$db = new mysqli(localhost, $user, $password, $dbase);
		$query = "SELECT * FROM `users` WHERE `email` = '{$email}'";
		$resultset = $db->query($query);	
		$response['match'] = false;
		if($resultset)
		{
			
			while ($row = $resultset->fetch_object()) {

				$response['match'] = true;
				$ppw = $row->password;

				if(strcasecmp($pw, $ppw) == 0)
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
		<title>Bars Admin: v1.0</title>
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	</head>
	<body>
		<div class="main_wrapper">
			<div class="main_category"><span class="main_title">Drinkup Admin Page v1.0</span></div>
			<div class="category">
				<span class="sub_title">Search by Email:</span>
				<br />
				<form action="user.php" method="post">
					<input type="hidden" name="type" value="search" />
					<span class="label">Email:</span>
					<input type="email" name="email" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Search" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Create a User:</span>
				<br />
				<form action="user.php" method="post">
					<input type="hidden" name="type" value="create" />
					<span class="label">First Name:</span>
					<input type="text" name="first" class="response"/>
					<br />
					<span class="label">Last Name:</span>
					<input type="text" name="last" class="response"/>
					<br />
					<span class="label">Email:</span>
					<input type="email" name="email" class="response"/>
					<br />
					<span class="label">Password:</span>
					<input type="password" name="pw" class="response"/>
					<br />
					<span class="label">age:</span>
					<input type="text" name="age" class="response"/>
					<br />
					<span class="label">Sex:</span>
					Male: <input type="radio" name="sex" class="response" value="Male" />
					<br />
					<span class="label"></span>
					Female: <input type="radio" name="sex" class="response" value="Female" />
					<br />
					<span class="label">Zip:</span>
					<input type="text" name="zip" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Create" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Log in:</span>
				<br />
				<form action="user.php" method="post">
					<input type="hidden" name="type" value="login" />
					<span class="label">Email:</span>
					<input type="email" name="email" class="response"/>
					<br />
					<span class="label">Password:</span>
					<input type="password" name="pw" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Sign in" class="response"/>
					<br />

				</form>

			</div>
		</div>
	</body>

</html>

<?

}

?>