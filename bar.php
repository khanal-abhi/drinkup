<?

require('config.php');
if (isset($_POST['type'])) {
	$type = $_POST['type'];
	$response;
	$zip;
	$name;
	$long;
	$lat;
	$icon;

	$db = new mysqli(localhost, $user, $password, $dbase);


	if(strcasecmp($type, "search") == 0)
	{
		$zip = $_POST['zip'];

		
		$query = "SELECT * FROM `bars` WHERE `zip` = {$zip}";
		$resultset = $db->query($query);
		$response['match'] = false;

		if($resultset)
		{
			$i=0;
			while($row = $resultset->fetch_object())
			{
				$response['match'] = true;
				$response[$i]['id'] = $row->id;
				$response[$i]['name'] = $row->name;
				$response[$i]['zip'] = $row->zip;
				$response[$i]['icon'] = $row->icon;

				$i++;
			}

			$response['results'] = $i;

			$resultset->close();
			$db->next_result();
		}

		
	}



	if(strcasecmp($type, "create") == 0)
	{
		
		$zip = $db->real_escape_string($_POST['zip']);
		$name = $db->real_escape_string($_POST['name']);
		$long = $db->real_escape_string($_POST['long']);
		$lat = $db->real_escape_string($_POST['lat']);
		$icon = $db->real_escape_string($_POST['icon']);
				

		$query = "INSERT INTO `bars` (`name`, `zip`, `long`, `lat`, `icon` ) VALUES ('{$name}', '{$zip}', '{$long}', '{$lat}', '{$icon}')";
		if ($db->query($query)) {
			$response['succes'] = true;
		}
		else
		{
			$response['success'] = false;
		}
		
		


	}

	$db->close();


		response.header("Content-Type: text/html");
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
				<span class="sub_title">Search Zip Code:</span>
				<br />
				<form action="bar.php" method="post">
					<input type="hidden" name="type" value="search" />
					<span class="label">Zip Code:</span>
					<input type="text" name="zip" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Search" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Create a Bar:</span>
				<br />
				<form action="bar.php" method="post">
					<input type="hidden" name="type" value="create" />
					<span class="label">Name:</span>
					<input type="text" name="name" class="response"/>
					<br />
					<span class="label">Zip Code:</span>
					<input type="text" name="zip" class="response"/>
					<br />
					<span class="label">Longitude:</span>
					<input type="text" name="long" class="response"/>
					<br />
					<span class="label">Latitude:</span>
					<input type="text" name="lat" class="response"/>
					<br />
					<span class="label">Icon:</span>
					<input type="text" name="icon" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Create" class="response"/>
					<br />

				</form>

			</div>
		</div>
	</body>

</html>



	<?
}

?>
