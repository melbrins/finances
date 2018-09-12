<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body>

	<?php 
		$import  	= new import();
		$render  	= new render();

		$transactionId = $_GET['id'];

        $categories = $render->getAllCategories();

        $transaction = $render->getTransactionPerId($transactionId);
        $nbrTest = $render->nbrTransactionTrigger('1', '2017-01-01', '2018-09-31', 'TESCO');

	?>

    <section>
        <span class="date"><?php echo $transaction['day']; ?></span>
        <h1><?php echo $transaction['name']; ?></h1>
        <p>Nbr of transactions: <?php echo $nbrTest[0]; ?></p>

        <span class="amount"><?php echo $transaction['amount']; ?></span>

        <form>
            <select class="categories" name="category">
                <?php
                    foreach($categories as $category){
                ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                <?php
                    }
                ?>
            </select>

            <input type="checkbox" value="all" id="apply-to-all"/>
            <label for="apply-to-all">Apply to All</label>
        </form>
    </section>

</body>

<?php include 'layout/after-body.php'; ?>

</html>