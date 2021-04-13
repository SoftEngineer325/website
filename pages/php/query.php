<?php
require 'dbc.php';

//Get Highest ranked professor of a course
//SELECT p.professor_id, p.f_name, p.l_name, p.rank, t.department, t.course_id FROM professors p, teaches t, professors pp WHERE p.professor_id = t.professor_id AND p.rank >= pp.rank GROUP BY (course_id);

if (isset($_GET["best_professors"])) {
	$columns = array(
		0 =>'department',
		1 => 'course_id',
		2 => 'f_name',
		3 => 'l_name',
		4 => 'rank'
	);

	$sql = "SELECT p.professor_id, p.rank, p.f_name, p.l_name, t.department, t.course_id 
			FROM professors p, teaches t, professors pp 
			WHERE p.professor_id = t.professor_id AND p.rank >= pp.rank ". //AND t.department = $_GET[best_professors]
			"GROUP BY course_id, department";

	$query=mysqli_query($conn, $sql) or die("test.php: get courses");
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

	/*
	$sql = "SELECT * FROM professors INNER JOIN teaches ON professors.professor_id = teaches.professor_id";
	$query=mysqli_query($conn, $sql) or die("test.php: get courses");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
	$query=mysqli_query($conn, $sql) or die("test.php: get courses"); // again run query with limit
	*/

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
	$json_data = array(
				"recordsTotal"    => intval( $totalData ),  // total number of records
				//"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);

	header('Content-Type: application/json');
	echo json_encode($json_data, JSON_PRETTY_PRINT);  // send data as json format

} 
else if (isset($_GET["all_courses"])) {

	//$requestData= $_REQUEST;
	$columns = array(
	// datatable column index  => database column name
		0 =>'department',
		1 => 'course_id',
		2 => 'f_name',
		3 => 'l_name',
		4 => 'rank'
	);
	// getting total number records without any search
	$sql = "SELECT * FROM professors INNER JOIN teaches ON professors.professor_id = teaches.professor_id";
	$query=mysqli_query($conn, $sql) or die("test.php: get courses");
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
	
	/*
	$sql = "SELECT * FROM professors INNER JOIN teaches ON professors.professor_id = teaches.professor_id";
	$query=mysqli_query($conn, $sql) or die("test.php: get courses");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
	$query=mysqli_query($conn, $sql) or die("test.php: get courses"); // again run query with limit
	*/
	
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
	$json_data = array(
				"recordsTotal"    => intval( $totalData ),  // total number of records
				//"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $data   // total data array
				);
	
	header('Content-Type: application/json');
	echo json_encode($json_data, JSON_PRETTY_PRINT);  // send data as json format
} 
else if (isset($_GET["courses"])) { 
		//$requestData= $_REQUEST;
		$columns = array(
			// datatable column index  => database column name
				//0 =>'department',
				0 => 'course_name',
				1 => 'course_id',
				2 => 'description',
				3 => "offered",
				4 => "prerequisites"
			);
			// getting total number records without any search
			$sql = "SELECT * FROM courses WHERE department = '".$_GET["courses"]."'";
			$query=mysqli_query($conn, $sql) or die("test.php: get courses");
			$totalData = mysqli_num_rows($query);
			$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.
			
			$data = array();
			while($row = mysqli_fetch_array($query)) {  // preparing an array
				$nestedData = array();
				//$nestedData[] = $row["department"];
				$nestedData[] = $row["course_name"];
				$nestedData[] = $row["course_id"];
				$nestedData[] = $row["description"];
				$nestedData[] = $row["offered"];
				$nestedData[] = $row["prerequisites"];

				$data[] = $nestedData;
			}
			$json_data = array(
						"recordsTotal"    => intval( $totalData ),  // total number of records
						//"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
						"data"            => $data   // total data array
						);
			
			header('Content-Type: application/json');
			echo json_encode($json_data, JSON_PRETTY_PRINT);  // send data as json format
} else {
	echo 'Stop snooping around...';
}
?>