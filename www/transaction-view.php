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

		$transactionId = $_GET['id'];

        $categories = $render->getAllCategories();

        $transaction = $render->getTransactionPerId($transactionId);
        $transactionName_array = explode( ' ON ', $transaction['name']);
        $transactionName = $transactionName_array[0];
        $nbrTest     = $render->nbrTransactionTrigger('1', '2017-01-01', '2018-09-31', $transactionName);

        $reponse     = $render->similarTransactions('1', '2017-01-01', '2018-09-31', $transactionName);

	?>

    <section>
        <span class="date"><?php echo $transaction['day']; ?></span>
        <h1><?php echo $transactionName; ?></h1>
        <p>Nbr of transactions: <?php echo $nbrTest[0]; ?></p>

        <span class="amount"><?php echo $transaction['amount']; ?></span>

        <form id="transaction-category" action="category.php">

            <input class="transactionId" type="hidden" name="transactionId" value="<?php echo $transaction['id']; ?>"/>

            <select class="categories" name="category">
                <?php
                    foreach($categories as $category){
                ?>
                        <option value="<?php echo $category['id']; ?>" <?php if($category['id'] == $transaction['category_id']){ ?> selected <?php } ?> ><?php echo $category['name']; ?></option>
                <?php
                    }
                ?>
            </select>

            <input type="checkbox" value="all" id="apply-to-all"/>
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