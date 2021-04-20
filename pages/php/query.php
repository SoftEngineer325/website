<?php
//Get database credentials
require 'dbc.php';

//check to see if a query is posted to the api
if (isset($_GET["query"])) {
	//Initizile data variable
	$data;
	//switch based on what query is needed
	switch($_GET["query"]) {
		case "best_professors":
			$data = best_professors($conn);
			break;
		case "all_courses":
			$data = all_courses($conn);
			break;
		case "electives":
			$data = get_electives($conn);
			break;
		case "get_books":
			$data = get_books($conn);
			break;
		case "meal_plan":
			$data = get_meal_plans($conn);
			break;
		default:
			$data = array();
			break;
	}
	//organize the data returned from a query
	$json_data = array(
		"recordsTotal"    => intval(sizeof($data)),  // total number of records
		"data"            => $data   // total data array
	);
	
	//Using heading application/json so DataTables and other frameworks know it only contains json data
	header('Content-Type: application/json');
	//encode the json data and display it, using prety print to easily read data from a user perspective
	echo json_encode($json_data, JSON_PRETTY_PRINT);  // send data as json format

//if its not a query, treat it like a normal website
} else {
echo 
"<style>
	* {
		background-color: #c00030;
	}
	
	div {
		color: white;
		text-align: center;
		font-weight: bold;
		position: absolute;
		top:  50%;
		left: 50%;
		transform: translate(-50%,-50%);
	}
</style>

<div>
	<h1><u>Website API</u></h1>
	<p>Made for a Software Eningeering project at Frostburg State University</p>
	<br>
	<p>Made By</p>
	<p>Rohan More, Megan Murphy, Nathalie Nyanga, David Morton</p>
	<img src='../../images/paw.png'>
</div>";
}

/**
 * Function that returns the best professors to every course in our database
 * Courses are from all majors/minors
 */
function best_professors($conn) {
	$sql = "SELECT p.professor_id, p.rank, p.f_name, p.l_name, t.department, t.course_id 
			FROM professors p, teaches t, professors pp 
			WHERE p.professor_id = t.professor_id AND p.rank >= pp.rank ". //AND t.department = $_GET[best_professors]
			"GROUP BY course_id, department";
	$query = mysqli_query($conn, $sql) or die("query.php: get best professors");

	$data = array();
	while($row = mysqli_fetch_array($query)) {  // preparing an array
		$nestedData = array();
		$nestedData[] = $row["department"];
		$nestedData[] = $row["course_id"];
		$nestedData[] = $row["f_name"];
		$nestedData[] = $row["l_name"];
		$nestedData[] = $row["rank"];

		$data[] = $nestedData;
	}
	return $data;
}

/**
 * Function that returns all the courses and every professor who teaches them
 * Courses are for all majors/minors
 */
function all_courses($conn) {
	$sql = "SELECT * FROM professors INNER JOIN teaches ON professors.professor_id = teaches.professor_id";
	$query = mysqli_query($conn, $sql) or die("query.php: get all courses");
	
	$data = array();
	while($row = mysqli_fetch_array($query)) {  // preparing an array
		$nestedData = array();
		$nestedData[] = $row["department"];
		$nestedData[] = $row["course_id"];
		$nestedData[] = $row["f_name"];
		$nestedData[] = $row["l_name"];
		$nestedData[] = $row["rank"];
	
		$data[] = $nestedData;
	}
	return $data;
}

/**
 * Function that returns the best electives for a certain minor
 */
function get_electives($conn) {
	$sql = "SELECT * FROM courses WHERE best_for LIKE '%".$_GET["major"]."%'";
	$query = mysqli_query($conn, $sql) or die("query.php: get electives for major");
	
	$data = array();
	while($row = mysqli_fetch_array($query)) {  // preparing an array
		$nestedData = array();
		$nestedData[] = $row["department"];
		$nestedData[] = $row["course_name"];
		$nestedData[] = $row["course_id"];
		$nestedData[] = $row["description"];
		$nestedData[] = $row["offered"];
		$nestedData[] = $row["prerequisites"];

		$data[] = $nestedData;
	}
	return $data;
}

/**
 * Function that returns a set of books based on isbn type and numbers
 */
function get_books($conn) {
	$data = array();
	$sql = ($_GET["isbn"] == 10 ? "SELECT * FROM books WHERE `isbn-10` IN (".$_GET["books"].")" : 
								  "SELECT * FROM books WHERE `isbn-13` IN (".$_GET["books"].")" );
	$query = mysqli_query($conn, $sql) or die("query.php: get books for isbn");
		
	while($row = mysqli_fetch_array($query)) {  // preparing an array
		$nestedData = array();
		$nestedData[] = ($_GET["isbn"] == 10 ? $row["isbn-10"] : $row["isbn-13"]);
		$nestedData[] = $row["book_name"];
		$nestedData[] = $row["price"];
		$nestedData[] = $row["company"];

		$data[] = $nestedData;
	}
	return $data;
}

function get_meal_plans($conn) {
	$sql = "SELECT * FROM mealplans WHERE student_type = '".$_GET["plan"]."'";
	$query = mysqli_query($conn, $sql) or die("query.php: get electives for major");
	
	$data = array();
	while($row = mysqli_fetch_array($query)) {  // preparing an array
		$nestedData = array();
		$nestedData[] = $row["student_type"];
		$nestedData[] = $row["meal_plan_name"];
		$nestedData[] = $row["description"];
		$nestedData[] = $row["description"];
		$nestedData[] = $row["cost"];

		$data[] = $nestedData;
	}
	return $data;
}
?>