<?php
	
	$inData = getRequestInfo();
	
    //load all the data into variables
    $userID = $inData["userID"];
    $contactFirst = $inData["firstName"];
    $contactLast = $inData["lastName"];
    $contactEmail = $inData["email"];
    $contactPhone = $inData["phone"];

    $contactFlag = 0;
	
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
        
        //Check if exact contact already exists
        $sql =  "SELECT FirstName, LastName, ID FROM `Contacts` WHERE ( FirstName LIKE '" . $contactFirst . "'
               AND LastName LIKE '" . $contactLast . "' AND Email LIKE '" . $contactEmail . "'
               AND Phone LIKE '" . $contactPhone . "' AND UserID = '" . $userID . "')";
        
        $result = $conn->query($sql);
		$searchCount = $result->num_rows;
		
		if($searchCount > 0)
		{
		   returnWithError("Contact already exists"); 
		   $contactFlag = 1;
		   
		}
		
        if(!$contactFlag)
        {
            //setup SQL string
             $sql = "INSERT INTO `Contacts` (`FirstName`, `LastName`, `Email`, `Phone`,`UserID`) VALUES ('" . $contactFirst . "', '" . $contactLast .
							"', '" . $contactEmail . "', '" . $contactPhone . "', '" . $userID . "');";
		
            if( $result = $conn->query($sql) != TRUE )
   		    {
   			    returnWithError( $conn->error );
   	    	}
   		    else
   	        {
                returnWithSuccess($contactFirst, $contactLast);
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
		echo $obj . "\n";
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"firstName":"","lastName":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithSuccess( $firstName, $lastName)
	{
        $retValue = '{"contact": {"firstName":"' . $firstName . '","lastName":"' . $lastName . '","error":""}, "Created": ' . TRUE .'}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
