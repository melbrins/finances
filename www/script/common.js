var days = [
    '1',
    '2',
    '3',
    '4',
    '5',
    '6',
    '7',
    '8',
    '9',
    '10',
    '11',
    '12',
    '13',
    '14',
    '15',
    '16',
    '17',
    '18',
    '19',
    '20',
    '21',
    '22',
    '23',
    '24',
    '25',
    '26',
    '27',
    '28',
    '29',
    '30',
    '31'
];

var months = [
    'January',
    'February',
    'March',
    'April',
    'March',
    'May',
    'June',
    'July',
    'September',
    'October',
    'November',
    'December'
];

var ctx = document.getElementById("myChart").getContext('2d');
// /block/yearSpendingPerMonth.php
$.ajax({

    url: '/block/yearSpendingPerMonth.php',

    data: {
        function2call 	: 'getEmployeesList',
        year 			: '2018'
    },
    type: 'post',
    dataType: "json",

    success: function(output){

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: '£ Spent',
                    data: output,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive:false,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:false
                        }
                    }]
                }
            }
        });

    }

});
function changeType(){
    alert('test');

}

// ====================
// MONTH TO DATE
// ====================

var mtd = document.getElementById("monthToDate").getContext('2d');

var monthToDate = new Chart(mtd, {
    type: 'line',
    data: {
        labels: days,
        datasets: [{
            label: '£ Spent',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive:false,
        maintainAspectRatio: false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:false
                }
            }]
        }
    }
});


// MONTH TO DATE
// Ajax Call - Update
// ------------------
$.ajax({

    url: '/block/monthToDate.php',

    data: { function2call 	: 'getEmployeesList' },
    type: 'post',
    dataType: "json",

    success: function(output){



    }

});

jQuery(document).ready(function($) {

    var mtdC = document.getElementById("monthSpentCategory").getContext('2d');

    var spendingCategory = new Chart(mtdC, {
        type: 'bar',
        data: {
            datasets: [{
                data: [0,0,0,0,0,0,0,0,0,0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive:false,
            maintainAspectRatio: false
        }
    });

    setCharts();

    $(".daterangepicker").daterangepicker();

    $( ".datepicker" ).datepicker();
    $( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );


    function setCharts (){

        $options = { function2call 	: 'spendingMonthToDate' };

        updateChart(spendingCategory, $options);

    }


    function updateChart (chart, options){

        $.ajax({

            url: '/block/updateChart.php',

            data: $options,
            type: 'post',
            dataType: "json",

            success: function(output){

                chart.data.labels             = Object.keys(output);
                chart.data.datasets[0].data   = Object.values(output);
                chart.update();

            }

        });
    }

    $( "#spendingCategory" ).submit(function(event){
        event.preventDefault();

        var rangeDate = JSON.parse($("#rangeDate").val());

        $options = {
            function2call 	: 'spendingMonthToDate',
            start           : rangeDate['start'],
            end             : rangeDate['end']
        };

        updateChart(spendingCategory, $options);
    });

} );