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

		$categoryId         = $_GET['category'];
		$categoryName       = $render->getCategoryName($categoryId );
        $jsonCategory       = $render->yearSpendingPerMonthPerCategory('1', $categoryId ,'2018');
		$reponse            = $render->getTransactionPerCategory('1', $categoryId,'2018-01-01', '2018-12-31');
        $categoryTrigger    = $render->getTriggerPerCategory($categoryId);

        foreach($categoryTrigger as $trigger){
            $import->setTrigger($trigger['category_id'], $trigger['trigger']);
        }


	?>

    <h1><?php echo $categoryName; ?></h1>
    <h3> YEAR SPENDING PER MONTH </h3>

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

<?php include 'layout/after-body.php'; ?>



<script>
    categoryJson = <?php print($jsonCategory); ?>;
</script>

</html>