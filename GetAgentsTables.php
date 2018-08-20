<?php

    include "Connection.php";

    //This php file work is to fill the chart

    //initialize the variables
    $jsonDataTables= [""];
    $From= '';
    $To= '';
    $jsonTables = '';


    //Check if get From is empty
    if(!$_GET['From']==null) {

        $From = $_GET['From'];

    }
    //Check if get To is empty
    if(!$_GET['To']==null){

        $To = $_GET['To'];

    }

//If From is empty, it fill be autofilled with the date 6 month before the actual day
    if($From==''){

        $sixMonthBefore = strtotime(date("Y-m-d") . '-6 months');
        $From = date("Y-m-d", $sixMonthBefore);
    }
    //If To is empty, it will be autofilled with the actual date
    if($To==''){

        $To = date("Y-m-d");

    }

    //this query will give us the  whole name and ID of the agents
    $sql = "SELECT firstname,lastname,staff_id FROM `ost_staff` order by staff_id";

    $result = mysqli_query($con, $sql) or die(mysqli_error($con));

    //This variable is used to save the agents
    $i = 1;

    foreach ($result as $agents){

        $jsonDataTables[0][$i-1] = $agents["firstname"] . " " . $agents["lastname"];

        //These are the queries to take the values from the database, the $i will be use on the "WHERE dept_id"
        $sqlOpenPerAgent = "SELECT COUNT(*) FROM `ost_ticket` where staff_id='$agents[staff_id]' AND created BETWEEN '$From' AND '$To' AND status_id in (1,6)";
        $sqlClosedPerAgent = "SELECT COUNT(*) FROM `ost_ticket` where staff_id='$agents[staff_id]' AND created BETWEEN '$From' AND '$To' AND status_id in (2,3,4,5)";

        //These are the results of the queries
        $resultOpenPerAgent = mysqli_query($con, $sqlOpenPerAgent) or die(mysqli_error($con));
        $resultClosePerAgent = mysqli_query($con, $sqlClosedPerAgent) or die(mysqli_error($con));

        //These are the values of the results of the queries
        $OpenPerAgent = mysqli_fetch_row($resultOpenPerAgent);
        $jsonDataTables[$i][0] = $OpenPerAgent;

        $ClosedPerAgent = mysqli_fetch_row($resultClosePerAgent);
        $jsonDataTables[$i][1] = $ClosedPerAgent;

        //This is the total of all departments
        $TotalPerAgent = $OpenPerAgent[0]+$ClosedPerAgent[0];
        $jsonDataTables[$i][2] = $TotalPerAgent;

        $i++;

    }

        //send the data to a JSON
        $jsonTables = json_encode($jsonDataTables);
        //Send the JSON to the main page
        echo $jsonTables;

?>



