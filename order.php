<?

require('config.php');

if (isset($_POST['type'])) {
	
	$response['match'] = false;
	$db = new mysqli(localhost, $user, $password, $dbase);
	$type = $_POST['type'];


	if (strcasecmp($type, "searchbyuser") == 0) {

		$userid = $_POST['userid'];

		$query = "SELECT * FROM `orders` WHERE `userid` = {$userid}";

		$resultset = $db->query($query);

		
		if($resultset)
		{
			$i= 0;

			
			while ($row = $resultset->fetch_object())
			{
				$response['match'] = true;
				$response[$i]['orderid'] = $row->id;
				$response[$i]['barid'] = $row->barid;
				$response[$i]['order'] = $row->order;
				$response[$i]['price'] = $row->price;
				$response[$i]['timestamp'] = $row->timestamp;
				$response[$i]['status'] = $row->status;
				$i++;
			}


			$response['results'] = $i;

			$resultset->close();
			$db->next_result();
			
		}
		


	}


	if (strcasecmp($type, "searchbybar") == 0) {

		$barid = $_POST['barid'];

		$query = "SELECT * FROM `orders` WHERE `barid` = {$barid}";

		$resultset = $db->query($query);

		if ($resultset)
		{
			$i = 0;

			while ($row = $resultset->fetch_object())
			{
				$response['match'] = true;
				$response[$i]['orderid'] = $row->id;
				$response[$i]['userid'] = $row->userid;
				$response[$i]['order'] = $row->order;
				$response[$i]['price'] = $row->price;
				$response[$i]['timestamp'] = $row->timestamp;
				$response[$i]['status'] = $row->status;
				$i++;

			}

			$response['results'] = $i;
		}
	}


	if (strcasecmp($type, "placeorder") == 0) {

		$barid = $db->real_escape_string($_POST['barid']);
		$userid = $db->real_escape_string($_POST['userid']);
		$order = $db->real_escape_string($_POST['order']);
		$price = $db->real_escape_string($_POST['price']);
		$status = $db->real_escape_string($_POST['status']);

		$query = "INSERT INTO `orders` (`barid`, `userid`, `order`, `price`, `status`) VALUES ({$barid}, {$userid}, '{$order}', {$price}, '{$status}')";


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
		<title>Orders Admin: v1.0</title>
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
	</head>
	<body>
		<div class="main_wrapper">
			<div class="main_category"><span class="main_title">Drinkup Admin Page v1.0</span></div>
			<div class="category">
				<span class="sub_title">Search By User ID:</span>
				<br />
				<form action="order.php" method="post">
					<input type="hidden" name="type" value="searchbyuser" />
					<span class="label">User id:</span>
					<input type="text" name="userid" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Search" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Search By Bar ID:</span>
				<br />
				<form action="order.php" method="post">
					<input type="hidden" name="type" value="searchbybar" />
					<span class="label">Bar id:</span>
					<input type="text" name="barid" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Search" class="response"/>
					<br />

				</form>

			</div>

			<div class="category">
				<span class="sub_title">Place an order:</span>
				<br />
				<form action="order.php" method="post">
					<input type="hidden" name="type" value="placeorder" />
					<span class="label">User id:</span>
					<input type="text" name="userid" class="response"/>
					<br />
					<span class="label">Bar id:</span>
					<input type="text" name="barid" class="response"/>
					<br />
					<span class="label">Order:</span>
					<input type="text" name="order" class="response"/>
					<br />
					<span class="label">Price:</span>
					<input type="text" name="price" class="response"/>
					<br />
					<span class="label">Status:</span>
					<input type="text" name="status" class="response"/>
					<br />
					<span class="label"></span>
					<input type="submit" value="Place Order" class="response"/>
					<br />

				</form>

			</div>
		</div>
	</body>

</html>


	<?
}

?>
