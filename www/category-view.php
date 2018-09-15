<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>
    <?php include 'layout/after-body.php'; ?>
</head>

<body>

<?php include 'layout/header.php'; ?>
	<?php 
		$import  	= new import();
		$render  	= new render();

		$categoryId         = $_GET['category'];
        $jsonCategory       = $render->yearSpendingPerMonthPerCategory('1', $categoryId ,'2018');
		$reponse            = $render->getTransactionPerCategory('1', $categoryId,'2018-01-01', '2018-12-31');
        $categoryTrigger    = $render->getTriggerPerCategory($categoryId);

        foreach($categoryTrigger as $trigger){
            $import->setTrigger($trigger['category_id'], $trigger['trigger']);
        }


	?>

    <h2> YEAR SPENDING PER MONTH </h2>

    <section class="canvas">
        <canvas id="categorySpending" width="1200" height="400"></canvas>
    </section>

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




<script>
    categoryJson = <?php print($jsonCategory); ?>;

    console.log('<?php echo $categoryId; ?>');

</script>
<!--<script>-->
<!--    require(['jquery'], function ($) {-->
<!--        var categorySpendingOptions = {-->
<!--            function2call: 'yearSpendingPerMonthPerCategory',-->
<!--            year: '2018',-->
<!--            category: --><?php //echo $categoryId; ?>
//        };
//
//        updateChart(categorySpending, categorySpendingOptions);
//    });
//
//</script>

</html>