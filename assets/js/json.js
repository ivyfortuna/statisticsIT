//This function filter the chart by date
var From= "";
var To= "";
var CheckTotal= "";
var FilterDate = "";
var FilterDept = "";
var FilterLocation = "";
var FilterAgents = "";
var FilterTopics = "";
var tablecolor = true;

function filterByDate(){

    //Take the values From the form
    From= document.getElementById("From").value;
    To= document.getElementById("To").value;
    FilterDate = document.getElementById("FilterDate").value;
    FilterDept = document.getElementById("FilterDept").value;
    FilterLocation = document.getElementById("FilterLocation").value;
    FilterAgents = document.getElementById("FilterAgents").value;
    FilterTopics = document.getElementById("FilterTopics").value;

    //Request a JSON the values of the chart
    JSONRequest.open("GET", "GetDate.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept +
        "&FilterLocation=" + FilterLocation +
        "&FilterAgents=" + FilterAgents +
        "&FilterTopics=" + FilterTopics +"", true);

    JSONRequest.send(null);

    //Request a JSON the values of the total chart
    JSONRequestTotal.open("GET", "GetDateTotal.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept +
        "&FilterLocation=" + FilterLocation +
        "&FilterAgents=" + FilterAgents +
        "&FilterTopics=" + FilterTopics +"", true);

    JSONRequestTotal.send(null);

    //Request a JSON the values of the locations table
    JSONLocationsTable.open("GET", "GetLocationsTables.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept + "", true);
    JSONLocationsTable.send(null);

    //Request a JSON the values of the departments table
    JSONDeptTable.open("GET", "GetDeptTables.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept + "", true);
    JSONDeptTable.send(null);

    //Request a JSON the values of the agents table
    JSONAgentsTable.open("GET", "GetAgentsTables.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept + "", true);
    JSONAgentsTable.send(null);

    //Request a JSON the values of the topics table
    JSONTopicsTable.open("GET", "GetTopicsTables.php?From=" + From +
        "&To=" + To +
        "&Total=" + CheckTotal +
        "&FilterDate=" + FilterDate +
        "&FilterDept=" + FilterDept + "", true);
    JSONTopicsTable.send(null);

}

//This function handle the JSON response
function handleReply(){
    //If the response is correct
    if (JSONRequest.readyState == 4) {
        //Save the response on a variable
        data1 = JSON.parse(this.responseText);
        //Call the function removeData from canvas.js
        removeData(myLine);

        //Call the function assData from canvas.js
        for (var i = 0; i < data1.length - 1; i++) {

            addData(myLine, data1[0][i], data1[i + 1]);

        }
    }
}

function handleReplyTotal() {

    //If the response is correct
    if (JSONRequestTotal.readyState == 4) {
        //Save the response on a variable
        data2 = JSON.parse(this.responseText);

        //Call the function removeData from canvas.js
        removeData(myLineTotal);
        //Call the function assData from canvas.js
        for (var i = 0; i < data2.length - 1; i++) {

            addDataTotal(myLineTotal, data2[0][i], data2[i + 1]);

        }
    }
}

function getLocationTable() {

    var locationtable = "";

    //if the JSON has a response do the code
    if (JSONLocationsTable.readyState == 4) {

        //insert the JSON data on the variable
        locationtable = JSON.parse(this.responseText);

        //empty the table to avoid duplicates
        document.getElementById("location").innerHTML = "";

        //based on the length of the variable with the JSON data iterate
        for(var i=1;i<locationtable.length+1;i++){

            //this if only is used to change the color of every two lines
            if(tablecolor==true) {

                //Insert the data of this iteration in the table
                document.getElementById("location").innerHTML +="<tr>" +
                    "<td id='departments'>" + locationtable[0][i-1] + "</td>" +
                    "<td>" + locationtable[i][0] + "</td>" +
                    "<td>" + locationtable[i][1]  + "</td>" +
                    "<td>" +  locationtable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=false;
            }else{

                //Insert the data of this iteration in the table
                document.getElementById("location").innerHTML +="<tr bgcolor='#DDDDDD'>" +
                    "<td id='departments'>" + locationtable[0][i-1] + "</td>" +
                    "<td>" + locationtable[i][0] + "</td>" +
                    "<td>" + locationtable[i][1]  + "</td>" +
                    "<td>" +  locationtable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=true;
            }
        }
    }
}

function getDeptTable() {

    var deptable = "";

    //if the JSON has a response do the code
    if (JSONDeptTable.readyState == 4) {

        //insert the JSON data on the variable
        deptable = JSON.parse(this.responseText);

        //empty the table to avoid duplicates
        document.getElementById("dept").innerHTML = "";

        //based on the length of the variable with the JSON data iterate
        for(var i=1;i<deptable.length+1;i++){

            //this if only is used to change the color of every two lines
            if(tablecolor==true) {

                //Insert the data of this iteration in the table
                document.getElementById("dept").innerHTML +="<tr>" +
                    "<td id='departments'>" + deptable[0][i-1] + "</td>" +
                    "<td>" + deptable[i][0] + "</td>" +
                    "<td>" + deptable[i][1]  + "</td>" +
                    "<td>" +  deptable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=false;
            }else{

                //Insert the data of this iteration in the table
                document.getElementById("dept").innerHTML +="<tr bgcolor='#DDDDDD'>" +
                    "<td id='departments'>" + deptable[0][i-1] + "</td>" +
                    "<td>" + deptable[i][0] + "</td>" +
                    "<td>" + deptable[i][1]  + "</td>" +
                    "<td>" +  deptable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=true;
            }
        }
    }
}

function getAgentTable() {

    var agenttable = "";

    //if the JSON has a response do the code
    if (JSONAgentsTable.readyState == 4) {

        //insert the JSON data on the variable
        agenttable = JSON.parse(this.responseText);

        //empty the table to avoid duplicates
        document.getElementById("agent").innerHTML = "";

        //based on the length of the variable with the JSON data iterate
        for(var i=1;i<agenttable.length+1;i++){

            //this if only is used to change the color of every two lines
            if(tablecolor==true) {

                //Insert the data of this iteration in the table
                document.getElementById("agent").innerHTML +="<tr>" +
                    "<td id='departments'>" + agenttable[0][i-1] + "</td>" +
                    "<td>" + agenttable[i][0] + "</td>" +
                    "<td>" + agenttable[i][1]  + "</td>" +
                    "<td>" +  agenttable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=false;
            }else{

                //Insert the data of this iteration in the table
                document.getElementById("agent").innerHTML +="<tr bgcolor='#DDDDDD'>" +
                    "<td id='departments'>" + agenttable[0][i-1] + "</td>" +
                    "<td>" + agenttable[i][0] + "</td>" +
                    "<td>" + agenttable[i][1]  + "</td>" +
                    "<td>" +  agenttable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=true;
            }
        }
    }
}

function getTopicTable() {

    var topictable = "";

    //if the JSON has a response do the code
    if (JSONTopicsTable.readyState == 4) {

        //insert the JSON data on the variable
        topictable = JSON.parse(this.responseText);

        //empty the table to avoid duplicates
        document.getElementById("topic").innerHTML = "";

        //based on the length of the variable with the JSON data iterate
        for(var i=1;i<topictable.length+1;i++){

            //this if only is used to change the color of every two lines
            if(tablecolor==true) {

                //Insert the data of this iteration in the table
                document.getElementById("topic").innerHTML +="<tr>" +
                "<td id='departments'>" + topictable[0][i-1] + "</td>" +
                "<td>" + topictable[i][0] + "</td>" +
                "<td>" + topictable[i][1]  + "</td>" +
                "<td>" +  topictable[i][2]  + "</td>" +
                "</tr>";

                tablecolor=false;
            }else{

                //Insert the data of this iteration in the table
                document.getElementById("topic").innerHTML +="<tr bgcolor='#DDDDDD'>" +
                    "<td id='departments'>" + topictable[0][i-1] + "</td>" +
                    "<td>" + topictable[i][0] + "</td>" +
                    "<td>" + topictable[i][1]  + "</td>" +
                    "<td>" +  topictable[i][2]  + "</td>" +
                    "</tr>";

                tablecolor=true;
            }
        }
    }
}

var JSONRequest=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONRequest.onreadystatechange=handleReply;

var JSONRequestTotal=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONRequestTotal.onreadystatechange=handleReplyTotal;

var JSONDeptTable=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONDeptTable.onreadystatechange=getDeptTable;

var JSONLocationsTable=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONLocationsTable.onreadystatechange=getLocationTable;

var JSONAgentsTable=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONAgentsTable.onreadystatechange=getAgentTable;

var JSONTopicsTable=new XMLHttpRequest();  // The variable that makes Ajax possible!
JSONTopicsTable.onreadystatechange=getTopicTable;