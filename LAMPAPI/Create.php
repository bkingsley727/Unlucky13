<?php
	
	$inData = getRequestInfo();
	
    //load all the data into variables
    $userID = $inData["userID"];
    $contactFirst = $inData["firstName"];
    $contactLast = $inData["lastName"];
    $contactEmail = $inData["email"];
    $contactPhone = $inData["phone"];

	
	$id = 0;
	$firstName = "";
	$lastName = "";

	$conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
    //check server connection
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        //make sure user ID is correct
        $sql = "SELECT * FROM `Users` WHERE `ID` = " . $userID;
		$result = $conn->query($sql);
		if ($result->num_rows < 0)
		{
			returnWithError( "No such user Found" );
        }
        
        //setup SQL string
        $sql = "INSERT INTO `Contacts` (`FirstName`, `LastName`, `Email`, `Phone`,`UserID`) VALUES ('" . $contactFirst . "', '" . $contactLast . "', '" . $contactEmail . "
                                        ', '" . $contactPhone . "', '" . $userID . "');";
		
        if( $result = $conn->query($sql) != TRUE )
   		{
   			returnWithError( $conn->error );
   		}else{
            $conn->close();
            returnWithSuccess($contactFirst, $contactLast);
        }
        

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
	
	function returnWithSuccess( $firstName, $lastName)
	{
        $retValue = '"contact": {"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}, 
                    "Created": ' . TRUE;
		sendResultInfoAsJson( $retValue );
	}
	
?>