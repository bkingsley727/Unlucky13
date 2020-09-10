<?php
   $inData = getRequestInfo();

   $user = $inData["login"];
   $password = $inData["password"];
   $firstName = $inData["firstName"];
   $lastName = $inData["lastName"];
   $email = $inData["email"];

   // Create connection
   $conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
   // Check connection
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
   else
	{
      //add data to database
      // check if database already contains info
		$sql = "SELECT * FROM `Users` WHERE `UserName`='" . $user . "'";
		$result = $conn->query($sql);
      // database has username
		if ($result->num_rows > 0)
		{
			returnWithError( "Username already taken, please choose another" );
		}
      // make entry in database
		else
		{
         $sql = "INSERT into Users (UserName,Password,FirstName,LastName,Email) VALUES ('" . $user . "','" . $password . "','" . $firstName . "','" . $lastName . "','" . $email . "')";

   		if( $result = $conn->query($sql) != TRUE )
   		{
   			returnWithError( $conn->error );
   		}
         else
         {
            returnWithInfo( $firstName, $lastName, "User created successfully");
         }

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
      echo $obj;
   }

   function returnWithError( $err )
   {
      $retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
      sendResultInfoAsJson( $retValue );
   }
   function returnWithInfo( $firstName, $lastName, $mssg)
	{
		$retValue = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '","message":"' . $mssg . '"}';
		sendResultInfoAsJson( $retValue );
	}

 ?>
