var Chart   = require('chart.js'),
    $       = require('jquery'),
    drp     = require('daterangepicker');
    Odometer = require('odometer');

// import 'Components/chart_IncomeVsSpending/js/IncomceVsSpending.js';

// function openMenu(){
//     $('#account-menu').show();
// };

// ====================
// VARIABLES
// ====================
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

function getMonth(digit){
    return months[digit];
}

$(document).ready(function($) {

    var accountMenu = $('#account-menu');

    window.toggleAccount = function () {
        accountMenu.toggleClass('active');
    };

    // $( function() {
    //     $( ".tabs" ).tabs();
    // } );

    var monthToDate;

    var mtdOptions = $defaultOptions;

    var mtdData = {
        labels: days,
        datasets: [{
            label               : 'Current Month',
            backgroundColor     : [ 'rgba(255, 99, 132, 0.2)' ],
            borderColor         : [ 'rgba(255,99,132,1)' ],
            borderWidth         : 1,
            pointHitRadius      : 10,
            pointRadius         : 0
        }]
    };

    if(document.getElementById("myChart")){
        var ctx = document.getElementById("myChart").getContext('2d');

        $.ajax({

            url: '/block/updateChart.php',

            data: {
                function2call 	: 'yearOnYear',
                year 			: '2018',
                type            : 'debit',
                account         : window.account
            },
            type: 'post',
            dataType: "json",

            success: function(output){

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
    }

    // ====================
    // INCOME VS SPENDING
    // ====================
    if(document.getElementById("incomeVsSpending")){
        var iVsS = document.getElementById("incomeVsSpending").getContext('2d');
        $.ajax({

            url: '/block/updateChart.php',

            data: {
                function2call 	: 'incomeVsSpending',
                year 			: '2018',
                account         : window.account
            },
            type: 'post',
            dataType: "json",

            success: function(output){

                var incomeVsSpending = new Chart(iVsS, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets:
                            [{
                                label: 'Income',
                                borderColor: '#000',
                                fill: '-1',
                                data: output.income
                            },{
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
    }

    // ====================
    // SPENDING CATEGORY
    // ====================
    if(document.getElementById("monthSpentCategory")) {
        var mtdC = document.getElementById("monthSpentCategory").getContext('2d');

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

        // MONTH TO DATE CATEGORIES
        // ------------------
        $(".daterangepicker").daterangepicker();

        $options = { function2call 	: 'spendingMonthToDateCategory', account: window.account };

        updateChart(spendingCategory, $options);

        $( "#spendingCategory" ).submit(function(event){
            event.preventDefault();

            var rangeDate = JSON.parse($("#rangeDate").val());

            $options = {
                function2call 	: 'spendingMonthToDateCategory',
                start           : rangeDate.start,
                end             : rangeDate.end,
                account         : window.account
            };

            updateChart(spendingCategory, $options);
        });
    }




    // ====================
    // MONTH TO DATE
    // ====================
    if(document.getElementById("monthToDate")) {
        var mtd = document.getElementById("monthToDate").getContext('2d');

        monthToDate = new Chart(mtd, {
            type: 'line',
            data: mtdData,
            options: mtdOptions
        });

        // MONTH TO DATE
        // Ajax Call - Update
        // ------------------
        $options = { function2call 	: 'spendingMonthToDate', account: window.account };


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
    }


    // ====================
    // MONTH TO DATE
    // Small block - Test
    // ====================
    if(document.getElementById("monthToDate2")) {
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

        // MONTH TO DATE
        // Small block - test
        updateChart(monthToDate2, $options);
    }


    // ====================
    // CATEGORY VIEW
    // ====================
    if(document.getElementById("categorySpending")){
        var cty = document.getElementById("categorySpending").getContext('2d');

        var categorySpending = new Chart(cty, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Current Year',
                    data: [0,0,0,0,0,0,0],
                    backgroundColor: 'rgba(250, 70, 220, 0.8)'
                },{
                    label: 'Last Year',
                    data: [0,0,0,0,0,0,0],
                    backgroundColor: 'rgba(69, 199, 250, 0.8)'
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });

        if(categoryJson){
            categorySpending.data.labels             = Object.keys(categoryJson);
            categorySpending.data.datasets[0].data   = Object.values(categoryJson);
            categorySpending.data.datasets[1].data   = Object.values(categoryJson2);
            categorySpending.update();
        }

        if(generalCategoryJson){
            for(var key in generalCategoryJson[2018]){
                // console.log("Key: " + key);

                for(var key2 in generalCategoryJson[2018][key]){
                    // console.log("Key2: " + key2);

                    for(var key3 in generalCategoryJson[2018][key][key2]){
                        // console.log("Key3: " + key3);
                        // console.log("Value: " + generalCategoryJson[2018][key][key2][key3]['id']);
                    }
                }
            }

        }
    }



    // ====================
    // FUNCTIONS
    // ====================
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

    function applyCategory(transactionId, categoryId, allTransactions){

        $.ajax({

            url: '/block/updateChart.php',

            data: {
                function2call   : 'updateCategory',
                transaction     : transactionId,
                category        : categoryId,
                all             : allTransactions
            },
            type: 'post',

            success: function(output){
                console.log(output)
            }

        });

    }

    $( "#transaction-category" ).submit(function(event){
        event.preventDefault();

        categoryId      = $('.categories').val();
        transactionId   = $('.transactionId').val();
        allTransactions = $('#apply-to-all')[0].checked;

        applyCategory(transactionId, categoryId, allTransactions);
    });
} );