<?php
//This php file work is to fill the chart

include "Connection.php";

//this session value reset the time to autologout
$_SESSION['LAST_ACTIVITY'] = time();

//initialize the variables
$jsonData= [""];
$From= '';
$FromTotal= '';
$To= '';
$json = '';
$dateDifference = '';
$To2 = '';
$datetime1 = '';
$datetime2 = '';
$interval = '';

$newYear = '';
$newMonth = '';
$newDay = '';

$changeYear = '';
$changeMonth = '';
$changeDay = '';

$insertNewYear = '';
$insertNewMonth = '';
$insertNewDay = '';
$insertEmptyData = '';

$toYear = '';
$toMonth = '';
$toMonthTotal = '';
$toDay = '';
$toDayTotal = '';

$jsonYear = '';
$jsonMonth = '';
$jsonDay = '';

$FilterDate = $_GET['FilterDate'];
$FilterDept = $_GET['FilterDept'];
$FilterLocation = $_GET['FilterLocation'];
$FilterAgents = $_GET['FilterAgents'];
$FilterTopics = $_GET['FilterTopics'];

//Check if get From is empty
if(!$_GET['From']==null) {

    $From = $_GET['From'];
    $FromTotal = $_GET['From'];

}
//Check if get To is empty
if(!$_GET['To']==null){

    $To = $_GET['To'];

}

//If From is empty, it fill be autofilled with the date 6 months before the actual date

if($From==''){

    $sixMonthBefore = strtotime(date("Y-m-d") . '-6 months');
    $From = date("Y-m-d", $sixMonthBefore);
    $FromTotal = date("Y-m-d", $sixMonthBefore);;

}
//If To is empty, it will be autofilled with the actual date
if($To==''){

    $To = date("Y-m-d");

}

$days = (abs(strtotime($From) - strtotime($To))) / (60 * 60 * 24);
//this if elseif block is used to autofilter the chart

if ($FilterDate == 'Years') {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Years FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y\")";

} elseif ($FilterDate == 'Months') {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Month FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y-%m\")";

} elseif ($FilterDate == 'Days') {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Days FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y-%m-%d\")";

} elseif ($days >= 1095) {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Years FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y\")";

} elseif ($days >= 90) {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Month FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y-%m\")";

} elseif ($days < 90) {

    $sql = "SELECT DATE_FORMAT(created, \"%Y-%m-%d\") AS Days FROM ost_ticket WHERE created BETWEEN '$From' AND '$To' GROUP BY DATE_FORMAT(created, \"%Y-%m-%d\")";

}

$result = mysqli_query($con, $sql) or die(mysqli_error($con));

//This variable is going to be used in the $jsonData to iterateKoper, 6000
$j = 1;
//This variable is used to save the values for the chart
$i = 0;

while ($row = mysqli_fetch_row($result)) {

    //Split the date on Year-Month-Day to work with it in the autofilter
    $row[0] = explode('-', $row[0]);
    //Change the string to date to be able to add the date to the JSON properly
    //Auto filter
    //filter by year if there are more than 3 years
    //filter by month if there are less than 3 years and more than 6 month
    //filter by week if there are less than 6 month and more than 5 weeks
    //filter by days if there are less than 5 weeks
    if ($days >= 1095 && $FilterDate != "Months" && $FilterDate != "Days" || $FilterDate == "Years") {

        $newYear = $row[0][0] . "-12-31";

        $datetime1 = date_create($From);
        $datetime2 = date_create($newYear);
        $interval = date_diff($datetime1, $datetime2);
        $interval = intval(substr($interval->format('%R%y'),1));

        if($interval>1){

            for($d=0;$d<$interval;$d++) {

                $insertNewYear = $newYear - $interval + $d;
                $jsonData[0][$i] = $insertNewYear;
                $i++;


                $insertEmptyData = [0,0];
                $jsonData[$j] = $insertEmptyData;

                $j++;

            }
        }

        //Create a new $To to every move and change it in the next query
        $To2 = $newYear;

        $newYear = substr($newYear, 0, -6);

        $jsonData[0][$i] = $newYear;

        $i++;

    } elseif ($days >= 90 && $FilterDate != "Years" && $FilterDate != "Days" || $FilterDate == "Months") {

        $newMonth = $row[0][0] . "-" . $row[0][1] . "-31";

        $datetime1 = date_create($From);
        $datetime2 = date_create($newMonth);
        $interval = date_diff($datetime1, $datetime2);
        $interval = intval(substr($interval->format('%R%M'),1));

        if($interval>1){

            for($d=0;$d<$interval;$d++) {

                $changeMonth = $row[0][1] - $interval + $d;
                $changeYear = $row[0][0];
                while($changeMonth<=0){

                    $changeMonth+=12;
                    $changeYear-=1;

                }
                if($changeMonth<10){
                    $changeMonth = "0" . $changeMonth;
                }
                $insertNewMonth = $changeYear . "-" . $changeMonth;

                $jsonData[0][$i] = $insertNewMonth;
                $i++;

                $insertEmptyData = [0,0];
                $jsonData[$j] = $insertEmptyData;

                $j++;

            }
        }

        //Create a new $To to every move and change it in the next query
        $To2 = $newMonth;

        $newMonth = substr($newMonth, 0, -3);

        $jsonData[0][$i] = $newMonth;
        $i++;

    } elseif ($days < 90 && $FilterDate != "Years" && $FilterDate != "Month" || $FilterDate == "Days") {

        $newDay = $row[0][0] . "-" . $row[0][1] . "-" . $row[0][2];

        $datetime1 = date_create($From);
        $datetime2 = date_create($newDay);
        $interval = date_diff($datetime1, $datetime2);
        $interval = intval(substr($interval->format('%R%a'),1));

        if($interval>1){

            for($d=0;$d<$interval;$d++) {

                $changeDay = $row[0][2] - $interval + $d;

                $changeMonth = $row[0][1];
                $changeYear = $row[0][0];

                while($changeDay<=0){

                    $changeMonth -= 1;

                    if($changeMonth<=0) {

                        $changeMonth+=12;
                        $changeYear -= 1;

                    }

                    if($changeMonth=="01") {

                        $changeDay+=31;

                    }elseif($changeMonth=="02" && $changeYear%4==0) {

                        $changeDay+=29;

                    }elseif($changeMonth=="02") {

                        $changeDay+=28;

                    }elseif($changeMonth=="03") {

                        $changeDay+=31;

                    }elseif($changeMonth=="04") {

                        $changeDay+=30;

                    }elseif($changeMonth=="05") {

                        $changeDay+=31;

                    }elseif($changeMonth=="06") {

                        $changeDay+=30;

                    }elseif($changeMonth=="07") {

                        $changeDay+=31;

                    }elseif($changeMonth=="08") {

                        $changeDay+=31;

                    }elseif($changeMonth=="09") {

                        $changeDay+=30;

                    }elseif($changeMonth=="10") {

                        $changeDay+=31;

                    }elseif($changeMonth=="11") {

                        $changeDay+=30;

                    }elseif($changeMonth=="12") {

                        $changeDay+=31;

                    }

                    if($changeDay<10 &&$changeDay>0){

                        $changeDay= "0" . $changeDay;

                    }
                }

                $insertNewDay = $changeYear . "-" . $changeMonth . "-" . $changeDay;

                $jsonData[0][$i] = $insertNewDay;
                $i++;


                if($FilterDept=="AutoDept") {
                    $insertEmptyData = [0, 0];
                }else{

                    $insertEmptyData = [0, 0, 0];
                }
                $jsonData[$j] = $insertEmptyData;

                $j++;

            }
        }
        //Create a new $To to every move and change it in the next query
        $To2 = $newDay;

        $jsonData[0][$i] = $newDay;
        $i++;

    }

    //Queries for the tickets count, the query is automatically choose based on what we choose on the filter
    if($FilterDept=="AutoDept" && $FilterLocation=="AutoLocation" && $FilterAgents=="AutoAgents" && $FilterTopics=="AutoTopics") {

        $sql = "SELECT 
              (SELECT count(created) FROM ost_ticket WHERE (created BETWEEN '$From' AND '$To2') AND status_id IN (1,6)) as open, 
              (SELECT count(created) FROM ost_ticket WHERE (created BETWEEN '$From' AND '$To2') AND status_id IN (2,3,4,5)) as closed";

    }elseif($FilterDept!="AutoDept"){

        $sql = "SELECT 
              (SELECT ost_department.name FROM ost_department WHERE ost_department.id='$FilterDept') as DeptName,
              (SELECT count(created) FROM ost_ticket WHERE (created BETWEEN '$From' AND '$To2') AND dept_id='$FilterDept' AND status_id IN (1,6)) as open, 
              (SELECT count(created) FROM ost_ticket WHERE (created BETWEEN '$From' AND '$To2') AND dept_id='$FilterDept' AND status_id IN (2,3,4,5)) as closed";

    }elseif($FilterLocation!="AutoLocation"){

        $sql = "SELECT 
              (SELECT value from ost_list_items where id='$FilterLocation') as Location, 
              (SELECT COUNT(created) FROM `ost_ticket` WHERE (created BETWEEN '$From' AND '$To2') AND status_id IN (1,6) AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location=$FilterLocation)) as open, 
              (SELECT COUNT(created) FROM `ost_ticket` WHERE (created BETWEEN '$From' AND '$To2') AND status_id IN (2,3,4,5) AND ticket_id IN (SELECT ticket_id FROM ost_ticket__cdata WHERE location=$FilterLocation)) as closed";

    }elseif($FilterAgents!="AutoAgents"){

        $sql = "SELECT 
              (SELECT concat(firstname, ' ', lastname) FROM `ost_staff` WHERE staff_id='$FilterAgents') as name,
              (SELECT COUNT(*) FROM `ost_ticket` where staff_id='$FilterAgents' AND created BETWEEN '$From' AND '$To2' AND status_id in (1,6)) as open,
              (SELECT COUNT(*) FROM `ost_ticket` where staff_id='$FilterAgents' AND created BETWEEN '$From' AND '$To2' AND status_id in (2,3,4,5))as closed";
    }elseif($FilterTopics!="AutoTopics") {

        $sql = "SELECT
              (SELECT topic FROM `ost_help_topic` WHERE topic_id='$FilterTopics') as name,
              (SELECT COUNT(*) FROM `ost_ticket` WHERE topic_id='$FilterTopics' AND created BETWEEN '$From' AND '$To' AND status_id IN (1,6)) as open,
              (SELECT COUNT(*) FROM `ost_ticket` WHERE topic_id='$FilterTopics' AND created BETWEEN '$From' AND '$To' AND status_id IN (2,3,4,5)) as closed";
    }
        //Take the result of the query or send an error if something goes wrong
    $result2 = mysqli_query($con, $sql) or die(mysqli_error($con));

    $From = $To2;
    //This variable is going to be used to iterate in the while

    //Iteration to fulfill the chart
    //check every row from the result
    while ($row2 = mysqli_fetch_row($result2)) {

        $jsonData[$j] = $row2;

        $j++;

    }
}

/*
 *
 * The next 2 elseif blocks generate new days/months/years if there is no data about them
 *
 */
$toYear = substr($To,0,-6);
$toMonth[0] = substr($To,0,-6);
$toMonth[1] = substr($To,5,-3);
$toMonthTotal = $toMonth[0] . "-" . $toMonth[1];
$toDay[0] = substr($To, 0,-6);
$toDay[1] = substr($To, 5,-3);
$toDay[2] = substr($To, 8);

$toDayTotal = $toMonth[0] . "-" . $toMonth[1] . "-" . $toDay[2];

if($i>0 && strlen($jsonData[0][$i-1])==4 && $jsonData[0][$i-1]<$toYear){

    $datetime1 = date_create($jsonData[0][$i-1]);
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);
    $interval = intval(substr($interval->format('%R%y'),1));

    for($d=0;$d<$interval;$d++){

        $jsonData[0][$i] = $jsonData[0][$i-1] + 1;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }
}elseif($i>0 && strlen($jsonData[0][$i-1])==7 && $jsonData[0][$i-1]<$toMonthTotal){

    $datetime1 = date_create($jsonData[0][$i-1]);
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);

    $interval = intval(substr($interval->format('%R%M'),1));

    for($d=0;$d<$interval;$d++){

        $jsonYear = substr($jsonData[0][$i-1],0,-3);
        $jsonMonth = substr($jsonData[0][$i-1],5);

        $jsonMonth +=1;

        if($jsonMonth>12){

            $jsonMonth = 1;
            $jsonYear +=1;

        }
        if($jsonMonth<10){

            $jsonMonth = "0".$jsonMonth;

        }

        $jsonData[0][$i] = $jsonYear . "-" . $jsonMonth;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }
}elseif($i>0 && strlen($jsonData[0][$i-1])==10 && $jsonData[0][$i-1]<$toDay){

    $datetime1 = date_create($jsonData[0][$i-1]);
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);
    $interval = intval(substr($interval->format('%R%a'),1));

    for($d=0;$d<$interval;$d++){

        $jsonYear = substr($jsonData[0][$i-1],0,-6);
        $jsonMonth = substr($jsonData[0][$i-1],5,-3);
        $jsonDay = substr($jsonData[0][$i-1],8);

        $jsonDay +=1;

        if($jsonMonth=="01" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="02" && $jsonDay>28 && $jsonYear%4!=0){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="02" && $jsonDay>29 && $jsonYear%4==0){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="03" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="04" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="05" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="06" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="07" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="08" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="09" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="10" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="11" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="12" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth=1;
            $jsonMonth= "0" . $jsonMonth;
            $jsonYear+=1;

        }

        if($jsonDay<10){

            $jsonDay= "0" . $jsonDay;

        }

        $jsonData[0][$i] = $jsonYear . "-" . $jsonMonth . "-" . $jsonDay;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }
}

if($i==0 && $days>1095 || $i == 0 && $FilterDate=="Years"){

    $jsonData[0][$i] = $From;
    $datetime1 = date_create($jsonData[0][$i]);

    $jsonData[0][$i] = substr($jsonData[0][$i], 0, -6);

    $i++;
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);
    $interval = intval(substr($interval->format('%R%y'),1));


    for($d=0;$d<$interval+1;$d++){

        $jsonData[0][$i] = $jsonData[0][$i-1] + $d;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }

}elseif($i==0 && $days>=90 || $i == 0 &&  $FilterDate=="Months"){

    $jsonData[0][$i] = $From;
    $datetime1 = date_create($jsonData[0][$i]);
    $i++;
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);

    $interval = intval(substr($interval->format('%R%m'),1));

    for($d=0;$d<$interval+1;$d++){

        $jsonYear = substr($jsonData[0][$i-1],0,-6);
        $jsonMonth = substr($jsonData[0][$i-1],5,-3);

        if($d>0) {
            $jsonMonth += 1;
        }
        if($jsonMonth>12){

            $jsonMonth = 1;
            $jsonYear +=1;

        }

        if($jsonMonth<10){
            $jsonMonth = "0" . $changeMonth;
        }
        $jsonData[0][$i-1] = $jsonYear . "-" . $jsonMonth;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }

}elseif($i==0 && $days<90 || $i == 0 &&  $FilterDate=="Days"){

    $jsonData[0][$i] = $From;
    $datetime1 = date_create($jsonData[0][$i]);
    $i++;
    $datetime2 = date_create($To);
    $interval = date_diff($datetime1, $datetime2);
    $interval = intval(substr($interval->format('%R%a'),1));

    for($d=0;$d<$interval+1;$d++){

        $jsonYear = substr($jsonData[0][$i-1],0,-6);
        $jsonMonth = substr($jsonData[0][$i-1],5,-3);
        $jsonDay = substr($jsonData[0][$i-1],8);

        $jsonDay +=1;

        if($jsonMonth=="01" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="02" && $jsonDay>28 && $jsonYear%4!=0){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="02" && $jsonDay>29 && $jsonYear%4==0){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="03" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="04" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="05" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="06" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="07" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="08" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;
            $jsonMonth= "0" . $jsonMonth;

        }elseif($jsonMonth=="09" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="10" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="11" && $jsonDay>30){

            $jsonDay=1;
            $jsonMonth+=1;

        }elseif($jsonMonth=="12" && $jsonDay>31){

            $jsonDay=1;
            $jsonMonth=1;
            $jsonMonth= "0" . $jsonMonth;
            $jsonYear+=1;

        }

        if($jsonDay<10){

            $jsonDay= "0" . $jsonDay;

        }

        $jsonData[0][$i] = $jsonYear . "-" . $jsonMonth . "-" . $jsonDay;
        $i++;

        $insertEmptyData = [0,0];
        $jsonData[$j] = $insertEmptyData;

        $j++;

    }

}

//send the data to a JSON
$json = json_encode($jsonData);

//Send the JSON to the main page
echo $json;

?>