<?

require('config.php');

if (isset($_POST['type'])) {

	$type = $_POST['type'];
	$response['match'] = false;
	$db = new mysqli(localhost, $user, $password, $dbase);

	if (strcasecmp($type, "search") == 0) {

		$barid = $_POST['barid'];
		$query = "SELECT * FROM `menu` WHERE `barid` = $barid";
		$resultset = $db->query($query);

		if($resultset)
		{
			$i = 0;

			while ($row = $resultset->fetch_object())
			{
				$response['match'] = true;
				$response[$i]['id'] = $row->id;
				$response[$i]['name'] = $row->name;
				$response[$i]['type'] = $row->type;
				$response[$i]['price'] = $row->price;


				$i++;
			}

			$response['results'] = $i;

			$resultset->close();
			$db->next_result();

		}

	}

	if (strcasecmp($type, "create") == 0) {
		$response['success'] = false;

		$barid = $db->real_escape_string($_POST['barid']);
		$name = $db->real_escape_string($_POST['name']);
		$drinktype = $db->real_escape_string($_POST['drinktype']);
		$price = $db->real_escape_string($_POST['price']);

		$query = "INSERT INTO `menu` (`barid`, `name`, `type`, `price`) VALUES ({$barid}, '{$name}', '{$drinktype}', {$price})";

		if ($db->query($query)) {
			
			$response['success'] = true;

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
				<span class="sub_title">Browse by Bar Id:</span>
				<br />
				<form action="menu.php" method="post">
					<input type="hidden" name="type" value="search" />
					<span class="label">Bar Id:</span>
					<input type="text" name="barid" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Browse" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Add to Bar:</span>
				<br />
				<form action="menu.php" method="post">
					<input type="hidden" name="type" value="create" />
					<span class="label">Bar Id:</span>
					<input type="text" name="barid" class="response"/>
					<br />
					<span class="label">Name:</span>
					<input type="text" name="name" class="response"/>
					<br />
					<span class="label">Drink Type:</span>
					<input type="text" name="drinktype" class="response"/>
					<br />
					<span class="label">Price:</span>
					<input type="text" name="price" class="response"/>
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
