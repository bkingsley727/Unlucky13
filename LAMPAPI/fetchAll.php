<?php
	
	$inData = getRequestInfo();
	
    $userID = $inData["userID"];

	$id = 0;
	$firstName = "";
    $lastName = "";
    $allContacts = "";
    $currentContact = "";

	$conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
	//server connection error
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        //check that userID exists
        $sql = "SELECT * FROM `Users` WHERE `ID` = " . $userID;
		$result = $conn->query($sql);
		if ($result->num_rows < 0)
		{
			returnWithError( "No such user Found" );
        }
        
        //get ID, and names of all contacts belonging to this user ID
        $sql = "SELECT ID,FirstName,LastName FROM `Contacts` WHERE `UserID` = " . $userID;
        $result = $conn->query($sql);
        
        $contactCount = $result->num_rows;
		//if user exists
		if ($contactCount == 0){
            //no user, not sure how this is possible
            returnWithError("No records found");
        }else{
            //yes user
            while ($contactCount > 0){
                //get next row and format it into json
                $row = $result->fetch_assoc();
                $currentContact = '{"ID": ' . $row["ID"] . ',"firstName": "' . $row["FirstName"] . '","lastName": "' . $row["LastName"] . '"}';
                
                //add current to array of all
                $allContacts .= $currentContact;
    
                //add comma unless last contact
                if ($contactCount != 1){
                    $allContacts .= ",";
                }
    
                $contactCount--;
            }

            returnWithInfo($allContacts);
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
   		$retValue = '{"error":"' . $err . '"}';
   		sendResultInfoAsJson( $retValue );
   	}

   	function returnWithInfo( $allContacts )
   	{
   		$retValue = '{"results":[' . $allContacts . '],"error":""}';
   		sendResultInfoAsJson( $retValue );
   	}
	
?>