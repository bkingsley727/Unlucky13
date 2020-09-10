<?php
   $inData = getRequestInfo();

   //load all the data into variables
   $userID = $inData["userID"];
   $contactID = $inData["contactID"];

	$conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
    //check server connection
    if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
      $sql =  "SELECT FirstName, LastName FROM `Contacts` WHERE ( ID LIKE '%" . $contactID . "%') AND UserID LIKE '%" . $userID . "%'";
      $result = $conn->query($sql);
      // database has username
		if ($result->num_rows == 0)
		{
			returnWithError( "No Records Found" );
		}
      else
      {
      $firstName = $row["FirstName"];
      $lastName = $row["LastName"];
      }
      $sql =  "DELETE FROM `Contacts` WHERE ( ID LIKE '%" . $contactID . "%') AND UserID LIKE '%" . $userID . "%'";
      if( $result = $conn->query($sql) != TRUE )
      {
         returnWithError( $conn->error );
      }
      else
      {
         returnWithSuccess( $firstName, $lastName, "Success");
      }

       $conn->close();
	}

	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj . "\n";
	}

	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithSuccess( $firstName, $lastName, $msg)
	{
        $retValue = '{"Deleted":' . $msg . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '", "error":""}';
		sendResultInfoAsJson( $retValue );
	}

 ?>
