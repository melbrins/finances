if(document.getElementById("incomeVsSpending")){
    var iVsS = document.getElementById("incomeVsSpending").getContext('2d');

    $.ajax({

        url: '/block/updateChart.php',

        data: {
            function2call 	: 'incomeVsSpending',
            year 			: '2018'
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