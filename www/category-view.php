<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body>

<?php include 'layout/header.php'; ?>
	<?php 
		$import  	= new import();
		$render  	= new render();

		$categoryId = $_GET['category'];

		$reponse            = $render->getTransactionPerCategory('1', $categoryId,'2018-01-01', '2018-12-31');
        $categoryTrigger    = $render->getTriggerPerCategory($categoryId);

        foreach($categoryTrigger as $trigger){
            $import->setTrigger($trigger['category_id'], $trigger['trigger']);
        }
	?>

    <h2> YEAR SPENDING PER MONTH </h2>
    <canvas id="categorySpending" width="1200" height="400"></canvas>

    <ul>
        <?php
        foreach($categoryTrigger as $trigger){
            ?>
                <li>
                    <p><?php echo $trigger['trigger']; ?></p>
                </li>
            <?php
        }
        ?>
    </ul>

    <div class="wrapper">
        <?php include 'layout/transaction_table.php'; ?>
    </div>
</body>

<?php include 'layout/after-body.php'; ?>

<script type="text/babel">

    var cty = document.getElementById("categorySpending").getContext('2d');

    // /block/yearSpendingPerMonth.php
    $.ajax({

        url: '/block/updateChart.php',

        data: {
            function2call 	: 'yearSpendingPerMonthPerCategory',
            year            : '2018',
            category 		: <?php echo $categoryId; ?>
        },
        type: 'post',
        dataType: "json",

        success: function(output){

            var categorySpending = new Chart(cty, {
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
</script>

</html>