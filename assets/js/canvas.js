
    //initialize the variables
    var ctx = '';
    var data1 = '';
    var data2 = '';
    var totalDataset = 0;

    //This variable holds all the information which is going to show on the chart
    var config = {

        //The type of the chart, check chart.js to see the types and how them works to change it
        type: 'line',
        data: {

            //This labels will be shown under the chart
            labels: [],

            //Here goes the data for each line
            datasets:
                [
                    {

                        //Label for the line, it's colors and values
                        label: "Open",
                        fill:false,

                        //edit this values to change what you want to show
                        data: [],

                        //edit this value to change background color
                        backgroundColor:[
                            ''
                        ]
                    }, {

                        //Label for the line, it's colors and values
						label: "Close",
                        fill:false,
						data: []

               		}
                ]
        },

        //Configuration of the chart
        options: {
            //Responsive true or false
            responsive: true,
            //Display tooltip true or false, if true type the text
            title: {
                display: true,
                text: 'Statistika v obdoju'
            },

            //Display the legend and edit its size
            legend:{
                display:true,
                labels:{
                    fontSize:14
                }
            },

            //Display tooltip true or false, check different modes on chart.js
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            //Display hover true or false, check modes on chart.js (works pretty similar to tooltip)
            hover: {
                mode: 'nearest',
                intersect: true
            },

            //Configurations of the scales, display true or false, scaleLabel display true or false if true type the labelString
            scales: {

                //Scale X
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Time',
                        fontSize: 14
                    }
                }],
                //Scale Y
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Nº Tickets',
                        fontSize: 14
                    },
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        }
                    }
                }]
            }
        }
    };

    var ctxTotal = '';

    //This variable holds all the information which is going to show on the chart
    var configTotal = {

        //The type of the chart, check chart.js to see the types and how them works to change it
        type: 'line',
        data: {

            //This labels will be shown under the chart
            labels: [],

            //Here goes the data for each line
            datasets:
                [
                    {

                        label: "open",
                        fill:false,
                        data: [0]

                    },{

                        label: "close",
                        fill:false,
                        data: [0]

                    }
                ]
        },

        //Configuration of the chart
        options: {
            //Responsive true or false
            responsive: true,
            //Display tooltip true or false, if true type the text
            title: {
                display: true,
                text: 'Skupna statistika'
            },

            legend:{
                display:true,
                labels:{
                    fontSize:14
                }
            },

            //Display tooltip true or false, check different modes on chart.js
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            //Display hover true or false, check modes on chart.js (works pretty similar to tooltip)
            hover: {
                mode: 'nearest',
                intersect: true
            },

            //Configurations of the scales, display true or false, scaleLabel display true or false if true type the labelString
            scales: {

                //Scale X
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Time',
                        fontSize: 14
                    }
                }],
                //Scale Y
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Nº Tickets',
                        fontSize: 14
                    },
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        },
                    }

                }]
            }
        }
    };

    //When the page is loaded it execute this script to load the chart
    window.onload = function () {

        ctx = document.getElementById('lineChart').getContext('2d');
        window.myLine = new Chart(ctx, config);
        ctxTotal = document.getElementById('lineChartTotal').getContext('2d');
        window.myLineTotal = new Chart(ctxTotal, configTotal);
        filterByDate();

    };

    //This function insert data into the chart (create a new point for every dataset in the chart)
    function addData(chart, label, data) {

        document.getElementById("lineChart").hidden=false;

        chart.data.labels.push(label);
        var i = 0;

        if(FilterDept=="AutoDept" && FilterLocation=="AutoLocation" && FilterAgents=="AutoAgents" && FilterTopics=="AutoTopics") {

            chart.options.title.text = "Statistika v obdobju";

            chart.data.datasets[i].label = "open";
            chart.data.datasets[i].data.push(data[i]);
            chart.data.datasets[i].backgroundColor = 'rgba(0,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(0,0,0,0.5)';

            i++;

            chart.data.datasets[i].label = "close";
            chart.data.datasets[i].data.push(data[i]);
            chart.data.datasets[i].backgroundColor = 'rgba(255,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(255,0,0,0.5)';

        }else{

            if(typeof (data[i])=='string'){
                chart.options.title.text = "Statistika v obdobju od " + data[i];
            }

                chart.data.datasets[i].label = "open";
                chart.data.datasets[i].data.push(data[i+1]);
                chart.data.datasets[i].backgroundColor = 'rgba(0,0,0,0.5)';
                chart.data.datasets[i].borderColor = 'rgba(0,0,0,0.5)';

                i++;

                chart.data.datasets[i].label = "close";
                chart.data.datasets[i].data.push(data[i+1]);
                chart.data.datasets[i].backgroundColor = 'rgba(255,0,0,0.5)';
                chart.data.datasets[i].borderColor = 'rgba(255,0,0,0.5)';

        }

        chart.update();

    }

    function addDataTotal(chart, label, data) {

        document.getElementById("lineChartTotal").hidden=false;

        chart.data.labels.push(label);
        var i = 0;

        if(FilterDept=="AutoDept" && FilterLocation=="AutoLocation" && FilterAgents=="AutoAgents" && FilterTopics=="AutoTopics") {

            chart.options.title.text ="Skupna statistika";

            chart.data.datasets[i].label = "open";
            chart.data.datasets[i].data.push(data[i]);
            chart.data.datasets[i].backgroundColor = 'rgba(0,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(0,0,0,0.5)';

            i++;

            chart.data.datasets[i].label = "close";
            chart.data.datasets[i].data.push(data[i]);
            chart.data.datasets[i].backgroundColor = 'rgba(255,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(255,0,0,0.5)';

        }else{

            if(typeof (data[i])=='string'){
                chart.options.title.text = "Skupna statistika od " + data[i];
            }

            chart.data.datasets[i].label = "open";
            chart.data.datasets[i].data.push(data[i+1]);
            chart.data.datasets[i].backgroundColor = 'rgba(0,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(0,0,0,0.5)';

            i++;

            chart.data.datasets[i].label = "close";
            chart.data.datasets[i].data.push(data[i+1]);
            chart.data.datasets[i].backgroundColor = 'rgba(255,0,0,0.5)';
            chart.data.datasets[i].borderColor = 'rgba(255,0,0,0.5)';

        }

        chart.update();

    }

    //This function delete ALL the points in the chart
    function removeData(chart) {

        var i=0;
        var datasetLength = chart.config.data.datasets.length;

        for(i=0;i<datasetLength;i++) {

            if(chart.config.data.datasets[i].data.length>totalDataset){

                totalDataset = chart.config.data.datasets[i].data.length;

            }
        }

        for(i=0;i<totalDataset;i++){

            chart.data.labels.pop();
            chart.data.datasets.forEach((dataset) => {
                dataset.data.pop();
                dataset.label = "";
                dataset.backgroundColor ="";
            });
            chart.update();
        }

    }