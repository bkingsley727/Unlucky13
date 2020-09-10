<?php
	
	$inData = getRequestInfo();
	
    //load all the data into variables
    $contactID = $inData["contactID"];
    $currentFirst = $inData["firstName"];
    $currentLast = $inData["lastName"];
    $currentEmail = $inData["email"];
    $currentPhone = $inData["phone"];

	
	

	$conn = new mysqli("localhost", "group13_DB_manage", "Z}^p.B@4d2E&", "group13_ContactManager_DB");
    //check server connection
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
        //make sure the contact exists
        $sql = "SELECT * FROM `Contacts` WHERE `ID` = " . $contactID;
		$result = $conn->query($sql);
		if ($result->num_rows < 0)
		{
			returnWithError( "No such contact Found" );
        }
        
        //SQL string tested in myPHP
        $sql =  "UPDATE `Contacts` SET `FirstName`= '" . $currentFirst . "', `LastName`= '" . $currentLast . "', `Email`= '" . $currentEmail . "', `Phone`= '" . $currentPhone . "' WHERE `ID`= " . $contactID;
        
		if( $result = $conn->query($sql) != TRUE )
   		{
   			returnWithError( $conn->error );
   		}else{
            $conn->close();
            returnWithSuccess($currentFirst, $currentLast);
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
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}, "updated": '. FALSE;
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithSuccess( $firstName, $lastName)
	{
        $retValue = '"contact": {"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}, 
                    "updated": ' . TRUE;
		sendResultInfoAsJson( $retValue );
	}
	
?>