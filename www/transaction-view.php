<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

    <?php

        $import  	= new import();
        $render  	= new render();

        $transactionId          = $_GET['id'];
        $transaction            = $render->getTransactionPerId($transactionId);
        $transactionName_array  = explode( ' ON ', $transaction['name']);
        $transactionName        = $transactionName_array[0];
        $nbrTransactions        = $render->nbrTransactionTrigger('1', '2017-01-01', '2019-09-31', $transactionName);
        $reponse                = $render->getAllSimilarTransactions('1', $transactionName);

    ?>

    <title>Transaction -  <?php echo $transactionName; ?>  </title>

</head>

<body>

<?php include 'layout/header.php'; ?>

    <div class="wrapper">
        <span class="date"><?php echo $transaction['day']; ?></span>
        <h1><?php echo $transactionName; ?></h1>
        <p>Nbr of transactions: <?php echo $nbrTransactions[0]; ?></p>
    </div>

    <section>


        <span class="amount"><?php echo $transaction['amount']; ?></span>

        <form id="transaction-category" action="category.php">

            <input class="transactionId" type="hidden" name="transactionId" value="<?php echo $transaction['id']; ?>"/>

            <select class="categories" name="category">
                <?php
                    $type       = $render->getAccountType($transaction['account_id']);
                    var_dump($type);
                    $categories = $render->getCategoryByType($type);

                    foreach($categories as $category){
                ?>
                        <option value="<?php echo $category['id']; ?>" <?php if($category['id'] == $transaction['category_id']){ ?> selected <?php } ?> ><?php echo $category['name']; ?></option>
                <?php
                    }
                ?>
            </select>

            <input type="checkbox" name="allTransaction" value="all" id="apply-to-all"/>
            <label for="apply-to-all">Apply to All</label>

            <button type="submit">Submit</button>
        </form>
    </section>

    <div class="wrapper">
        <?php include 'layout/transaction_table.php'; ?>
    </div>

</body>

<?php include 'layout/after-body.php'; ?>

</html>