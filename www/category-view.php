<!DOCTYPE html>
<html lang="en">
<head>


	<?php include 'layout/head.php'; ?>
    <?php

        $import  	= new import();
        $render  	= new render();

        $account    = ($_GET['account']) ? $account = $_GET['account'] : $account = '1,2,3,4';

        $categoryId             = $_GET['category'];
        $categoryName           = $render->getCategoryName($categoryId );
        // GET JSON FOR CURRENT YEAR
        $jsonCategory           = $render->yearSpendingPerMonthPerCategory($account, $categoryId ,'2018');
        // GET JSON FOR LAST YEAR
        $jsonCategory2          = $render->yearSpendingPerMonthPerCategory($account, $categoryId ,'2017');
        // GET JSON FOR EVERY INFORMATION FROM THIS CATEGORY AT ONCE
        $generalJsonCategory    = $render->jSonCategory($account, $categoryId,'2018-01-01', '2018-12-31');
        // GET ALL CURRENT YEAR TRANSACTIONS
        $reponse                = $render->getTransactionPerCategory($account, $categoryId,'2017-01-01', '2018-12-31');
        // GET LIST OF CATEGORY TRIGGER
        $categoryTrigger        = $render->getTriggerPerCategory($categoryId);
        // APPLY CATEGORY ID TO ALL TRANSACTIONS FOUND WITH ONCE OF THE CATEGORY TRIGGER
        foreach($categoryTrigger as $trigger){
            $import->setTrigger($trigger['category_id'], $trigger['trigger']);
        }

    ?>

    <script>
        window.account = <?php ($_GET['account']) ? print($_GET['account']) : print("'all'"); ?>;
    </script>

    <title>Category - <?php echo $categoryName; ?></title>

</head>

<body>

<?php include 'layout/header.php'; ?>

    <div class="wrapper">
        <h1><?php echo $categoryName; ?></h1>
    </div>

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
    categoryJson2 = <?php print($jsonCategory2); ?>;
    generalCategoryJson = <?php print($generalJsonCategory); ?>;
</script>

</html>