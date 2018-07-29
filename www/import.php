<!DOCTYPE html>
<html lang="en">
<head>

	<?php include 'layout/head.php'; ?>

</head>

<body>

	<?php

		$render  		= new render();
		$import  		= new import();
		$transactions 	= $import->csvToArray('import/transactions.csv');
		$accounts 		= $render->getAllAccounts();

		foreach( $transactions as $transaction){

			// Set up date format for database
			$date  		= $transaction['Date'];
			$date_array = explode("/", $date);
			$date  		= $date_array['2'] . '-' . $date_array['1'] . '-' . $date_array['0'];

			// Set up account id 
			$transactionAccount 	= $transaction['Account'];

			if(!isset($currentAccount) && $currentAccount != $transactionAccount){
				foreach ( $accounts as $account){

					if( $transactionAccount == $account['account_number']){
						$currentAccount = $account['id'];

						break;
					}

				}
			}

			$transaction_info = array (
				'date'  	 	=> $date,
				'amount'  	 	=> $transaction['Amount'],
				'name'  	 	=> $transaction['Memo'],
				'account_id' 	=> $currentAccount,
				'category_id' 	=> '1'
			);

			$test = $import->checkData($transaction_info);
			// var_dump($transaction_info);
			// var_dump($test);

			if($test == false){
				$import->addTransaction($transaction_info);			 
			}else{
				$import->addTransaction($transaction_info);			 
				var_dump($test['id']);
			}

			// Set up amount
			// $finalAmount += $transaction['Amount'];
						
		}

		// var_dump($finalAmount);

		// var_dump($transactions);
	?>
	
</body>

</html>