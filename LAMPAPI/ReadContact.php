<?php

   $inData = getRequestInfo();

   // Create connection
   $conn = new mysqli("localhost", "group13_Kingsley", "dKz6k85rqV", "group13_ContactManager_DB");
   // Check connection
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
   else {
      $sql =  "SELECT * FROM `Contacts` WHERE ID LIKE '%" . $inData["contactID"] . "%'";

		$result = $conn->query($sql);
      if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$firstName = $row["FirstName"];
			$lastName = $row["LastName"];
			$id = $row["ID"];
         $email = $row["Email"];
         $phone = $row["Phone"];
         $dateCreated = $row["DateCreated"];

			returnWithInfo($firstName, $lastName, $id, $email, $phone, $dateCreated);
		}
		else
		{
			returnWithError( "how did u get here child what did u click on" );
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

   function returnWithInfo( $firstName, $lastName, $id, $email, $phone, $dateCreated)
	{
		$retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '",
         "email":"' . $email .'","phone":"' . $phone . '","dateCreated":"' . $dateCreated . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
 ?>
