<?php

 session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // request 10 hours ago
    session_destroy();
    session_unset();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time

?>

<!DOCTYPE html>
<html>

<head>
<?php

//If the value is empty, it's because you hadn't log in, so redirect to login
	if(!isset($_SESSION['login_user'])){ //if login in session is not set
		header("Location: index.html");
	}
?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>statistics</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/mdb.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <img id="IskraLogo" src="assets/img/logo.jpg">
    </div>
    <div id="Error"></div>
    <div class="container">
        <canvas id="lineChart"></canvas>

        <form onsubmit="prepareImg()" action="CreatePDF.php" method="POST" target="_blank">

            <p class="help-block">Od </p>
            <input class="form-control input-lg" type="date" id="From">
            <p class="help-block">Do </p>
            <input class="form-control input-lg" type="date" id="To" min="1970-01-01" max="2000-13-13">

            <p class="help-block">Filter obdobja</p>
            <select id="FilterDate">
                <option value="Auto">Avtomatsko</option>
                <option value="Years">Leta</option>
                <option value="Months">Meseci</option>
                <option value="Days">Dnevi</option>
            </select><br><br>

            <!--
                The value of this fields are the ID for each departments
            -->
            <p class="help-block">Filter by department</p>
            <select id="FilterDept" onchange="resetFilter('FilterDept')">
                    <option value="AutoDept">Avtomatsko</option>
                    <option value="1">IT Podpora</option>
                    <option value="2">CRM Podpora</option>
            </select><br><br>

            <p class="help-block">Filter by location</p>
            <select id="FilterLocation" onchange="resetFilter('FilterLocation')">
                <option value="AutoLocation">Avtomatsko</option>
                <option value="1">PE Semic</option>
                <option value="2">PE Ljubljana</option>
                <option value="3">PE Otoce</option>
                <option value="4">PE Kranj</option>
                <option value="5">PE Sentvid</option>
                <option value="6">PE Glinek</option>
            </select><br><br>

            <p class="help-block">Filter by agent</p>
            <select id="FilterAgents" onchange="resetFilter('FilterAgents')">
                <option value="AutoAgents">Avtomatsko</option>
                <option value="1">Sašo Dorniž</option>
                <option value="3">Barbara SELAKOVIĆ</option>
                <option value="4">Damir BAČIČ</option>
                <option value="5">Luka DEU</option>
                <option value="7">Franci Mubi</option>
                <option value="8">Jakob SNOJ</option>
                <option value="9">Igor KALIZAN</option>
                <option value="10">Andraž ROT</option>
                <option value="11">Ksenija ŠKRLJ</option>
            </select><br><br>

            <p class="help-block">Filter by topic</p>
            <select id="FilterTopics" onchange="resetFilter('FilterTopics')">
                <option value="AutoTopics">Avtomatsko</option>
                <option value="17">THEREFORE</option>
                <option value="18">INFOR LN</option>
                <option value="19">E-POŠTA</option>
                <option value="20">Splošna odprava napak - PC</option>
                <option value="21">Telefonija</option>
                <option value="22">Omrežje</option>
                <option value="23">Tiskanje</option>
                <option value="24">Intranet - Iskranet</option>
                <option value="25">Internet - www</option>
                <option value="27">Nabava računalniške opreme</option>
                <option value="28">Nalepke</option>
                <option value="29">Meritve</option>
                <option value="30">Terminali</option>
                <option value="31">Office</option>
                <option value="32">Aplikacije</option>
                <option value="34">Intrix CRM tehnične pos.</option>
                <option value="35">Intrix CRM poslovne pos.</option>
                <option value="36">Nameščanje sistema</option>
            </select><br><br>

            <button class="btn btn-primary" type="button" onclick="filterByDate()">Osveži</button>
            <input id="inp_img" name="img" type="hidden" value="">
            <input id="inp_img_total" name="imgTotal" type="hidden" value="">
            <input id="inp_location_table" name="locationPDF" type="hidden" value="">
            <input id="inp_department_table" name="departmentPDF" type="hidden" value="">
            <input id="inp_topic_table" name="topicPDF" type="hidden" value="">
            <input id="inp_agent_table" name="agentPDF" type="hidden" value="">
            <button class="btn btn-primary" type="submit">Ustvari PDF</button>

        </form>

        <canvas id="lineChartTotal"></canvas>
    </div>

<div id="tables">
    <div id="locationDiv" class="container">
        <div class="table-responsive">
            <table class="table"  id="myTable">
                <thead>
                <tr>
                    <th onclick='sortTable(0, "myTable")'>Locations </th>
                    <th onclick='sortTable(1, "myTable")'>Open </th>
                    <th onclick='sortTable(2, "myTable")'>Closed </th>
                    <th onclick='sortTable(3, "myTable")'>Total </th>
                </tr>
                </thead>
                <tbody id="location">

                </tbody>
            </table>
        </div>
    </div>

    <div id="departmentDiv" class="container">
        <div class="table-responsive">
            <table class="table"  id="myTable2">
                <thead>
                    <tr>
                        <th onclick='sortTable(0, "myTable2")'>Departments</th>
                        <th onclick='sortTable(1, "myTable2")'>Open</th>
                        <th onclick='sortTable(2, "myTable2")'>Closed</th>
                        <th onclick='sortTable(3, "myTable2")'>Total</th>
                    </tr>
                </thead>
                <tbody id="dept">

                </tbody>
            </table>
        </div>
    </div>

    <div id="topicDiv" class="container">
        <div class="table-responsive">
            <table class="table"  id="myTable3">
                <thead>
                <tr>
                    <th onclick='sortTable(0, "myTable3")'>Topic </th>
                    <th onclick='sortTable(1, "myTable3")'>Open </th>
                    <th onclick='sortTable(2, "myTable3")'>Closed </th>
                    <th onclick='sortTable(3, "myTable3")'>Total </th>
                </tr>
                </thead>
                <tbody id="topic">

                </tbody>
            </table>
        </div>
    </div>

    <div id="agentDiv" class="container">
        <div class="table-responsive">
            <table class="table"  id="myTable4">
                <thead>
                <tr>
                    <th onclick='sortTable(0, "myTable4")'>Agents </th>
                    <th onclick='sortTable(1, "myTable4")'>Open </th>
                    <th onclick='sortTable(2, "myTable4")'>Closed </th>
                    <th onclick='sortTable(3, "myTable4")'>Total </th>
                </tr>
                </thead>
                <tbody id="agent">

                </tbody>
            </table>
        </div>
    </div>

</div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/canvas.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/mdb.js"></script>
    <script src="assets/js/jquery.easing.js"></script>

    <!--these are the js who work with the chart, the json and to sort the tables-->
    <script src="assets/js/chart.js"></script>
    <script src="assets/js/json.js"></script>
    <script src="assets/js/sortTable.js"></script>

    <script>

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd
        }
        if(mm<10){
            mm='0'+mm
        }

        today = yyyy+'-'+mm+'-'+dd;

        document.getElementById("To").setAttribute("max", today);
        document.getElementById("From").setAttribute("max", today);

    </script>

    <script>

        function prepareImg() {

            var canvas = document.getElementById('lineChart');
            var canvasTotal = document.getElementById('lineChartTotal');
            var locationTable = document.getElementById('locationDiv');
            var departmentTable = document.getElementById('departmentDiv');
            var topicTable = document.getElementById('topicDiv');
            var agentTable = document.getElementById('agentDiv');
            document.getElementById('inp_img').value = canvas.toDataURL();
            document.getElementById('inp_img_total').value = canvasTotal.toDataURL();
            document.getElementById('inp_location_table').value = locationTable.innerHTML;
            document.getElementById('inp_department_table').value = departmentTable.innerHTML;
            document.getElementById('inp_topic_table').value = topicTable.innerHTML;
            document.getElementById('inp_agent_table').value = agentTable.innerHTML;
        }

    </script>

    <script>

        function resetFilter(filter){

            var department= document.getElementById("FilterDept");
            var location= document.getElementById("FilterLocation");
            var agents= document.getElementById("FilterAgents");
            var topics= document.getElementById("FilterTopics");

            if(filter=="FilterDept"){

                location.selectedIndex=0;
                agents.selectedIndex=0;
                topics.selectedIndex=0;

            }else if(filter=="FilterLocation"){

                department.selectedIndex=0;
                agents.selectedIndex=0;
                topics.selectedIndex=0;

            }else if(filter=="FilterAgents"){

                location.selectedIndex=0;
                department.selectedIndex=0;
                topics.selectedIndex=0;

            }else if(filter=="FilterTopics"){

                location.selectedIndex=0;
                agents.selectedIndex=0;
                department.selectedIndex=0;

            }

        }

    </script>

</body>

</html>