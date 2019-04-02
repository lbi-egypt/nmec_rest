<?php
include("connection.php");
$db = new dbObj();
$connection =  $db->getConnstring();

$request_method=$_SERVER["REQUEST_METHOD"];

switch($request_method)
{
	case 'GET':
		// Retrive record
		if(!empty($_GET["id"]))
		{
			$id=intval($_GET["id"]);
			get_catalogue($id);
		}
		else
		{
			get_catalogues();
		}
		break;
	case 'POST':
		// Insert new
		//insert_catalogue();
		//break;
	case 'PUT':
		// Update record
		$id=intval($_GET["id"]);
		//update_catalogue($id);
		//break;
	case 'DELETE':
		// Delete record
		$id=intval($_GET["id"]);
		delete_catalogue($id);
		break;
	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
}

function get_catalogues()
{
	global $connection;
	$query="SELECT * FROM catalogue";
	$response=array();
	$result=mysqli_query($connection, $query);
	while($row=mysqli_fetch_array($result))
	{
		$response[]=$row;
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function get_catalogue($id=0)
{
	global $connection;
	$query="SELECT * FROM catalogue";
	if($id != 0)
	{
		$query.=" WHERE id=".$id." LIMIT 1";
	}
	$response=array();
	$result=mysqli_query($connection, $query);
	while($row=mysqli_fetch_array($result))
	{
		$response[]=$row;
	}
	header('Content-Type: application/json');
	echo json_encode($response);
	
	
}

function insert_catalogue()
{
	global $connection;

	$data = json_decode(file_get_contents('php://input'), true);
	$sr=$data["sr"];
	$en_description=$data["en_description"];
	$materialdetails=$data["materialdetails"];
	echo $query="INSERT INTO catalogue SET sr='".$sr."', en_description='".$en_description."', materialdetails='".$materialdetails."'";
	if(mysqli_query($connection, $query))
	{
		$response=array(
			'status' => 1,
			'status_message' =>'catalogue Added Successfully.'
		);
	}
	else
	{
		$response=array(
			'status' => 0,
			'status_message' =>'catalogue Addition Failed.'
		);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function update_catalogue($id)
{
	global $connection;
	$post_vars = json_decode(file_get_contents("php://input"),true);
	$sr=$data["sr"];
	$en_description=$data["en_description"];
	$materialdetails=$data["materialdetails"];
	$query="UPDATE catalogue SET sr='".$sr."', en_description='".$en_description."', materialdetails='".$materialdetails."' WHERE id=".$id;
	if(mysqli_query($connection, $query))
	{
		$response=array(
			'status' => 1,
			'status_message' =>'catalogue Updated Successfully.'
		);
	}
	else
	{
		$response=array(
			'status' => 0,
			'status_message' =>'catalogue Updation Failed.'
		);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}

function delete_catalogue($id)
{
	global $connection;
	$query="DELETE FROM catalogue WHERE id=".$id;
	if(mysqli_query($connection, $query))
	{
		$response=array(
			'status' => 1,
			'status_message' =>'catalogue Deleted Successfully.'
		);
	}
	else
	{
		$response=array(
			'status' => 0,
			'status_message' =>'catalogue Deletion Failed.'
		);
	}
	header('Content-Type: application/json');
	echo json_encode($response);
}
?>