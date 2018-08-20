<?php

    include "Connection.php";

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

    //this query will give us the name and ID of the topic, and the topic
    $sql = "SELECT topic_id, topic FROM `ost_help_topic` order by topic";

    $result = mysqli_query($con, $sql) or die(mysqli_error($con));

    //This variable is used to save the topics
    $i = 1;

    foreach ($result as $topic){

        $jsonDataTables[0][$i-1] = $topic['topic'];

        //These are the queries to take the values from the database
        $sqlOpenPerTopic = "SELECT COUNT(*) FROM `ost_ticket` WHERE topic_id=$topic[topic_id] AND created BETWEEN '$From' AND '$To' AND status_id IN (1,6)";
        $sqlClosedPerTopic = "SELECT COUNT(*) FROM `ost_ticket` WHERE topic_id=$topic[topic_id] AND created BETWEEN '$From' AND '$To' AND status_id IN (2,3,4,5)";

        //These are the results of the queries
        $resultOpenPerTopic = mysqli_query($con, $sqlOpenPerTopic) or die(mysqli_error($con));
        $resultClosePerTopic = mysqli_query($con, $sqlClosedPerTopic) or die(mysqli_error($con));

        //These are the values of the results of the queries, which are going to be assigned to a position int eh jsonDataTables array
        $OpenPerTopic = mysqli_fetch_row($resultOpenPerTopic);
        $jsonDataTables[$i][0] = $OpenPerTopic;

        $ClosedPerTopic = mysqli_fetch_row($resultClosePerTopic);
        $jsonDataTables[$i][1] = $ClosedPerTopic;

        //This is the total of all departments
        $TotalPerTopic = $OpenPerTopic[0]+$ClosedPerTopic[0];
        $jsonDataTables[$i][2] = $TotalPerTopic;

        $i++;

    }
        //send the data to a JSON
        $jsonTables = json_encode($jsonDataTables);

        //Send the JSON to the main page
        echo $jsonTables;

?>



