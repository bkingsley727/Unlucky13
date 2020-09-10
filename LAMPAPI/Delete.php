<?php
   $inData = getRequestInfo();

   $contactIDs = array();
   $userID = $inData["userID"];

   for( $i = 0; $i < count($inData["contactID"]); $i++ ){
      $contactIDs[] = $inData["contactID"][$i];
   }
	$conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
    //check server connection
    if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
      for( $i = 0; $i < count($inData["contactID"]); $i++ ){
         $contactID = $contactIDs[$i];

         $sql =  "SELECT FirstName, LastName FROM `Contacts` WHERE ( ID LIKE '%" . $contactID . "%') AND UserID LIKE '%" . $userID . "%'";
         $result = $conn->query($sql);
         // database has username
		    if ($result->num_rows === 0)
		    {
                $retValue = '{"message":" Record Not found for chosen contact","contactID":"' . $contactID . '"}';
     			        $searchResults .= $retValue;
               continue;
		    }
            else
            {
                $row = $result->fetch_assoc();
                $firstName = $row["FirstName"];
                $lastName = $row["LastName"];
            }
            
            $sql =  "DELETE FROM `Contacts` WHERE ( ID LIKE '%" . $contactID . "%') AND UserID LIKE '%" . $userID . "%'";
            if( $result = $conn->query($sql) != TRUE )
            {
               $retValue = '{"message":"Deletion Unsuccessful for Chosen Contact","contactID":"' . $contactID . '"}';
                   $searchResults .= $retValue;
              continue;
            }
            else
            {
               $retValue = '{"message":"Deletion Successful for Chosen Contact","contactID":"' . $contactID . '",
                              "firstName":"' . $firstName . '", "lastName":"' . $lastName . '"  }';
                   $searchResults .= $retValue;
              continue;
            }
         }
      returnWithInfo( $searchResults );
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

   function returnWithInfo( $searchResults )
   {
      $returnArray = '{"results":[' . $searchResults . '],"error":""}';
      sendResultInfoAsJson( $returnArray );
   }

 ?>
