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

    //this query will give us the number assigned to a location

    $sql = "SELECT DISTINCT(SUBSTRING_INDEX(location,',',1)) as localization from ost_ticket__cdata";

    $result = mysqli_query($con, $sql) or die(mysqli_error($con));

    //This variable is used to save the locations
    $i = 1;

    foreach ($result as $locations){


        $sqlLocations ="SELECT ticket_id FROM ost_ticket__cdata WHERE location=$locations[localization]";

        //we have to check if the location is null to change the query
        if ($locations['localization'] == null){

            $sqlOpenPerLocation = "SELECT COUNT(*) as Open FROM `ost_ticket` WHERE status_id IN (1,6) AND created BETWEEN '$From' AND '$To' AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location IS NULL)";
            $sqlClosedPerLocation = "SELECT COUNT(*) as Closed FROM `ost_ticket` WHERE status_id IN (2,3,4,5) AND created BETWEEN '$From' AND '$To' AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location IS NULL)";

        }else {

            $sqlOpenPerLocation = "SELECT COUNT(*) as Open FROM `ost_ticket` WHERE status_id IN (1,6) AND created BETWEEN '$From' AND '$To' AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location=$locations[localization])";
            $sqlClosedPerLocation = "SELECT COUNT(*) as Closed FROM `ost_ticket` WHERE status_id IN (2,3,4,5) AND created BETWEEN '$From' AND '$To' AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location=$locations[localization])";

        }

        //These are the results of the queries
        $resultOpenPerLocation = mysqli_query($con, $sqlOpenPerLocation) or die(mysqli_error($con));
        $resultClosePerLocation = mysqli_query($con, $sqlClosedPerLocation) or die(mysqli_error($con));

        //These are the values of the results of the queries
        $OpenPerLocation = mysqli_fetch_row($resultOpenPerLocation);
        $jsonDataTables[$i][0] = $OpenPerLocation;

        $ClosedPerLocation = mysqli_fetch_row($resultClosePerLocation);
        $jsonDataTables[$i][1] = $ClosedPerLocation;

        //This is the total of all locations
        $TotalPerLocation = $OpenPerLocation[0]+$ClosedPerLocation[0];
        $jsonDataTables[$i][2] = $TotalPerLocation;

        //here we change the number of the location for it's name
        if($locations['localization'] != 1 && $locations['localization'] != 2 && $locations['localization'] != 3 && $locations['localization'] != 4 && $locations['localization'] != 5 && $locations['localization'] != 6){

            $locations['localization'] = 'Unknown';

        }
        if($locations['localization']== 1){

            $locations['localization'] = 'PE Semic';

        }
        if($locations['localization']== 2){

            $locations['localization'] = 'PE Ljubljana';

        }
        if($locations['localization'] == 3){

            $locations['localization'] = 'PE Otoce';

        }
        if($locations['localization']== 4){

            $locations['localization'] = 'PE Kranj';

        }
        if($locations['localization'] == 5){

            $locations['localization'] = 'PE Sentvid';

        }
        if($locations['localization']== 6){

            $locations['localization'] = 'PE Glinek';

        }

        $jsonDataTables[0][$i-1] = $locations['localization'];

        $i++;

    }
        //send the data to a JSON
        $jsonTables = json_encode($jsonDataTables);

        //Send the JSON to the main page
        echo $jsonTables;

?>



