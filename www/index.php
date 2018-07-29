<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body>

	<?php 
		$import  	= new import();

		$render  	= new render(); 
//		$reponse  	= $render->getAllTransactions();
        $reponse  	= $render->getTransactions('1', '2018-01-01', '2018-07-31');
		$accounts  	= $render->getAllAccounts();
		$categories = $render->getAllCategories();

		$today      = $render->getMonthSpending("3", "12", "2017");

		$months     = array( "9", "10", "11", "12");
		$maxSpent   = $render->getMonthSpendingRange("1", $months, "2017");

	?>


	<section class="Dashboard">
		<h2> YEAR SPENDING PER MONTH </h2>
		<canvas id="myChart" width="1200" height="400"></canvas>grun

		<h2> SPENDING MONTH TO DATE </h2>
        <select id="mtd-type" onchange="changeType()">
            <option value="line">Line</option>
            <option value="Bar">Bar</option>
            <option value="radar">Radar</option>
            <option value="polar area">Polar Area</option>
            <option value="doughnut and pie">Doughnut and Pie</option>
            <option value="bubble">Bubble</option>
        </select>
		<canvas id="monthToDate" width="1200" height="400"></canvas>


		<h2> SPENDING MONTH TO DATE PER CATEGORY </h2>
        <form id="spendingCategory" action="index.php">
            <input type="text" id="rangeDate" class="daterangepicker">
            <button type="submit">Submit</button>
        </form>
		<canvas id="monthSpentCategory" width="375" height="400"></canvas>

		<br>
		<br>
		<br>
		<br>
		<br>
		<br>

	</section>

	<br>
	<br>
	<br>
	<br>
	<br>
	<br>

	<section class="category">

		<table id="category_table">
			<thead>
				<tr>
					<th>
						ID
					</th>

					<th>
						Name
					</th>

					<th>
						Trigger
					</th>

                    <th>
                        Action
                    </th>
				</tr>
			</thead>

			<tbody>
				<?php 

					foreach( $categories as $category)
					{

				?>

						<tr>
							<td>
								<?php echo $category['id']; ?>
							</td>

							<td>
								<?php echo $category['name']; ?>
							</td>

							<td>
								<?php echo $category['category_trigger']; ?>
							</td>

                            <td>
                                <form method="post" action="block/setTrigger.php">
                                    <input type="hidden" id="categoryId" name="categoryId" value="<?php echo $category['id']; ?>">
                                    <input type="hidden" id="categoryTrigger" name="categoryTrigger" value="<?php echo $category['category_trigger']; ?>">
                                    <button type="submit">Set Category</button>
                                </form>
                            </td>
						</tr>

				<?php 

					}

				?>
			</tbody>
		</table>

		<form method="post" action="block/addCategory.php">

			<label for="category_name">Name</label><br>
			<input type="text" name="category_name"><br>

			<label for="category_trigger">Trigger</label><br>
			<input type="text" name="category_trigger"><br>

			<br>

			<button type="submit">Add Trigger</button>
		</form>
	</section>

	<section class="transactions">
		<table id="transaction_table">
			<thead>
				<tr>
					<th width="20%">
						Day
					</th>

					<th width="20%">
						Amount
					</th>

					<th width="20%">
						Name
					</th>

					<th width="20%">
						Account Id
					</th>

					<th width="20%">
						Category Id
					</th>
				</tr>
			</thead>

			<tbody>
				<?php

					while ($donnees = $reponse->fetch())
					{
				
				?>
				
						<tr>
							<td>
								<?php echo $donnees['day']; ?>
							</td>

							<td>
								<?php echo $donnees['amount']; ?>
							</td>

							<td>
								<?php echo $donnees['name']; ?>
							</td>

							<td>
								<?php 
								
									$account_id = str_replace('0', '' ,$donnees['account_id']);
									echo $accounts[$account_id]['account_number']; 

								?>
							</td>

							<td>
								<?php echo $donnees['category_id']; ?>
							</td>
						</tr>
				
				<?php
				
					}

					$reponse->closeCursor();
				
				?>
			</tbody>
		</table>

	</section>

	<br>
	<br>
	<br>
	<br>
	<br>
	<br>

	<section class="account">
		<table id="account_table">
			<thead>
				<tr>
					<th>
						ID
					</th>

					<th>
						Name
					</th>

					<th>
						Type
					</th>

					<th>
						Number
					</th>
				</tr>
			</thead>

			<tbody>
				<?php 

					foreach( $accounts as $account)
					{

				?>

						<tr>
							<td>
								<?php echo $account['id']; ?>
							</td>

							<td>
								<?php echo $account['name']; ?>
							</td>

							<td>
								<?php echo $account['type']; ?>
							</td>

							<td>
								<?php echo $account['account_number']; ?>
							</td>
						</tr>

				<?php 

					}

				?>
			</tbody>
		</table>
		<form method="post" action="block/addAccount.php">

			<label for="account_name">Account Name</label><br>
			<input type="text" name="account_name"><br>

			<label for="account_type">Account Type</label><br>
			<select name="account_type">
				<option value="personal">Personal</option>
				<option value="business">Business</option>
			</select><br>

			<label for="account_number">Account Number</label><br>
			<input type="text" name="account_number"><br>

			<br>

			<button type="submit">Add Account</button>
		</form>
	</section>

	

</body>

<?php include 'layout/after-body.php'; ?>

</html>