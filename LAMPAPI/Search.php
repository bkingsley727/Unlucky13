<?php

	$inData = getRequestInfo();

	$contacts = array(array());
	$searchCount = 0;

   // Create connection
   $conn = new mysqli("localhost", "group13_Kingsley", "dKz6k85rqV", "group13_ContactManager_DB");
   // Check connection
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
   else {
      $sql =  "SELECT FirstName, LastName FROM `Contacts` WHERE ( FirstName LIKE '%" . $inData["search"] . "%'
               OR LastName LIKE '%" . $inData["search"] . "%' OR Email LIKE '%" . $inData["search"] . "%'
               OR Phone LIKE '%" . $inData["search"] . "%' ) AND UserID LIKE '%" . $inData["userID"] . "%'";


		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				$searchCount++;
				$searchResults .= '"' . $row["FirstName"] . '"';
				$searchResults .= '"' . $row["LastName"] . '"';
			}
		}
		else
		{
			returnWithError( "No Records Found" );
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
