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
		$sql = "SELECT ID,FirstName,LastName FROM `Contacts` WHERE `UserID` = " . $userID;
        $result = $conn->query($sql);
        $contactCount = $result->num_rows;
		//if user exists
		if ($contactCount == 0){
            returnWithError("No records found");
        }

        while ($contactCount > 0){
            $row = $result->fetch_assoc();
            $currentContact = '{"ID": ' . $row["ID"] . '"firstName": "' . $row["FirstName"] . '","lastName": "' . $row["LastName"] . '"}';
            
            $allContacts .= $currentContact;

            if ($contactCount != 1){
                $allContacts .= ",";
            }

            $contactCount--;
        }
        $conn->close();
        
        returnWithInfo($allContacts);
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
   		$retValue = '"error":"' . $err . '"';
   		sendResultInfoAsJson( $retValue );
   	}

   	function returnWithInfo( $allContacts )
   	{
   		$retValue = '{"results":[' . $allContacts . '],"error":""}';
   		sendResultInfoAsJson( $retValue );
   	}
	
?>