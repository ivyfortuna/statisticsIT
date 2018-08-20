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

    //this query will give us the name and ID of the departments
    $sql = "SELECT name,id FROM ost_department ORDER BY id";

    $result = mysqli_query($con, $sql) or die(mysqli_error($con));

    //This variable is used to save the department
    $i = 1;

    foreach ($result as $department){

        $jsonDataTables[0][$i-1] = $department['name'];

        //These are the queries to take the values from the database, the $i will be use on the "WHERE dept_id"
        $sqlOpenPerDept = "SELECT COUNT(*) as Open FROM `ost_ticket` WHERE dept_id='$department[id]' AND created BETWEEN '$From' AND '$To' AND status_id IN (1,6)";
        $sqlClosedPerDept = "SELECT COUNT(*) as Closed FROM `ost_ticket` WHERE dept_id='$department[id]' AND created BETWEEN '$From' AND '$To' AND status_id IN (2,3,4,5)";

        //These are the results of the queries
        $resultOpenPerDept = mysqli_query($con, $sqlOpenPerDept) or die(mysqli_error($con));
        $resultClosePerDept = mysqli_query($con, $sqlClosedPerDept) or die(mysqli_error($con));

        //These are the values of the results of the queries
        $OpenPerDept = mysqli_fetch_row($resultOpenPerDept);
        $jsonDataTables[$i][0] = $OpenPerDept;

        $ClosedPerDept = mysqli_fetch_row($resultClosePerDept);
        $jsonDataTables[$i][1] = $ClosedPerDept;

        //This is the total of all departments
        $TotalPerDept = $OpenPerDept[0]+$ClosedPerDept[0];
        $jsonDataTables[$i][2] = $TotalPerDept;

        $i++;

    }
        //send the data to a JSON
        $jsonTables = json_encode($jsonDataTables);

        //Send the JSON to the main page
        echo $jsonTables;

?>



