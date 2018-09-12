var Chart   = require('chart.js'),
    React   = require('react'),
    $       = require('jquery'),
    drp     = require('daterangepicker');
    Odometer = require('odometer');

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
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
];

var ctx = document.getElementById("myChart").getContext('2d');

var iVsS = document.getElementById("incomeVsSpending").getContext('2d');
// /block/yearSpendingPerMonth.php

var $defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scaleShowVerticalLines: false,
    drawBorder: false,

    elements: {
        line: {
            tension: 0
        }

    },

    scales: {
        yAxes: [{
            ticks: {
                beginAtZero:false
            }
        }],
        xAxes: [{
            display: false
        }]
    }
};

$.ajax({

    url: '/block/updateChart.php',

    data: {
        function2call 	: 'yearOnYear',
        year 			: '2018',
        type            : 'debit'
    },
    type: 'post',
    dataType: "json",

    success: function(output){

        console.log(output);

        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Current Year',
                    borderColor: '#000',
                    fill: '-1',
                    data: output.currentYear
                },
                {
                    label: 'Previous Year',
                    borderColor: '#af0000',
                    fill: '-1',
                    data: output.previousYear
                }]
            },
            options: $defaultOptions
        });

    }

});

$.ajax({

    url: '/block/updateChart.php',

    data: {
        function2call 	: 'incomeVsSpending',
        year 			: '2018'
    },
    type: 'post',
    dataType: "json",

    success: function(output){

        console.log(output);

        var incomeVsSpending = new Chart(iVsS, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Income',
                    borderColor: '#000',
                    fill: '-1',
                    data: output.income
                },
                    {
                        label: 'Spending',
                        borderColor: '#af0000',
                        fill: '-1',
                        data: output.spending
                    }]
            },
            options: $defaultOptions
        });

    }

});


$(document).ready(function($) {

    var mtdC = document.getElementById("monthSpentCategory").getContext('2d');

    // ====================
    // MONTH TO DATE
    // ====================

    var mtd = document.getElementById("monthToDate").getContext('2d');

    var monthToDate;

    var mtdOptions = $defaultOptions;

    var mtdData = {
        labels: days,
        datasets: [{
            label: 'Current Month',
            backgroundColor: [ 'rgba(255, 99, 132, 0.2)' ],
            borderColor: [ 'rgba(255,99,132,1)' ],
            borderWidth: 1,
            pointHitRadius: 10,
            pointRadius: 0
        }]
    };

    monthToDate = new Chart(mtd, {
        type: 'line',
        data: mtdData,
        options: mtdOptions
    });

    // ====================
    // MONTH TO DATE
    // Small block - Test
    // ====================
    var mtd2 = document.getElementById("monthToDate2").getContext('2d');

    var mtd2Options = {
        responsive:false,
        maintainAspectRatio: false,
        tension: 0,
        scales: {
            yAxes: [{
                display: false,
                ticks: {
                    beginAtZero:false
                }
            }],
            xAxes: [{
                display: false
            }]
        }
    };

    var monthToDate2 = new Chart(mtd2, {
        type: 'line',
        data: mtdData,
        options: mtd2Options
    });


    // ====================
    // SPENDING CATEGORY
    // ====================
    var spendingCategory = new Chart(mtdC, {
        type: 'horizontalBar',
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
                borderWidth: 1,
                pointRadius: 2
            }]
        },
        options: {
            responsive:false,
            maintainAspectRatio: false
        }
    });

    setCharts();


    function setCharts (){


        // MONTH TO DATE
        // Ajax Call - Update
        // ------------------
        $options = { function2call 	: 'spendingMonthToDate' };


        $('#MonthtoDate').submit(function(event){
            event.preventDefault();

            monthToDate.destroy();

            monthToDate = new Chart(mtd, {
                type    : $('#mtd-type').val(),
                data    : mtdData,
                options : mtdOptions
            });

        });


        updateChart(monthToDate, $options);

        // MONTH TO DATE
        // Small block - test
        updateChart(monthToDate2, $options);


        // MONTH TO DATE CATEGORIES
        // ------------------
        $(".daterangepicker").daterangepicker();

        $options = { function2call 	: 'spendingMonthToDateCategory' };

        updateChart(spendingCategory, $options);

        $( "#spendingCategory" ).submit(function(event){
            event.preventDefault();

            var rangeDate = JSON.parse($("#rangeDate").val());

            $options = {
                function2call 	: 'spendingMonthToDateCategory',
                start           : rangeDate.start,
                end             : rangeDate.end
            };

            updateChart(spendingCategory, $options);
        });

    }

    function updateType(graph, chartLabel, chartType, chartData, chartOptions){

        alert(graph);
        monthToDate.destroy();

        monthToDate = new Chart(chartLabel, {
            type    : chartType,
            data    : chartData,
            options : chartOptions
        });

    }

    function updateChart (chart, options){

        $.ajax({

            url: '/block/updateChart.php',

            data: options,
            type: 'post',
            dataType: "json",

            success: function(output){

                chart.data.labels             = Object.keys(output);
                chart.data.datasets[0].data   = Object.values(output);
                chart.update();

            }

        });
    }

} );