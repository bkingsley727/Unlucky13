<?php

	$inData = getRequestInfo();

	$contact = array(array());
	$searchCount = 0;

   // Create connection
   $conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
   // Check connection
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
   else {
      $sql =  "SELECT FirstName, LastName, ID FROM `Contacts` WHERE ( FirstName LIKE '%" . $inData["search"] . "%'
               OR LastName LIKE '%" . $inData["search"] . "%' OR Email LIKE '%" . $inData["search"] . "%'
               OR Phone LIKE '%" . $inData["search"] . "%' ) AND UserID LIKE '%" . $inData["userID"] . "%'";

		$result = $conn->query($sql);
		$searchCount = $result->num_rows;

      if ($searchCount == 0)
		{
			returnWithError( "No Records Found" );
		}

		while ($searchCount > 0)
		{
			$row = $result->fetch_assoc();
		    $contact = '{"firstName":"' . $row["FirstName"] . '","lastName":"' . $row["LastName"] . '","contactID":"' . $row[ID] . '"}';


			$searchResults .= $contact;

			if($searchCount != 1)
			{
				$searchResults .= ",";
			}

			$searchCount--;
		}
		$conn->close();
   }

   	returnWithInfo( $searchResults );

   	function getRequestInfo()
   	{
   		return json_decode(file_get_contents('php://input'), true);
   	}

   	function sendResultInfoAsJson( $obj )
   	{
   		header('Content-type: application/json');
   		echo $obj;
   	}

   	function returnWithError( $err )
   	{
   		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
   		sendResultInfoAsJson( $retValue );
   	}

   	function returnWithInfo( $searchResults )
   	{
   		$retValue = '{"results":[' . $searchResults . '],"error":""}';
   		sendResultInfoAsJson( $retValue );
   	}

 ?>
