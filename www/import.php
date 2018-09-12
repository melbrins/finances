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
		$triggers       = $render->getAllTriggers();


		foreach( $transactions as $transaction){
            unset($categoryId);
			// Set up date format for database
			$date  		= $transaction['Date'];
			$date_array = explode("/", $date);
			$date  		= $date_array['2'] . '-' . $date_array['1'] . '-' . $date_array['0'];
			$name       = $transaction['Memo'];
			$name_array = explode ('   ', $name);
			$merchant   = $name_array[0];

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

			foreach($triggers as $trigger){
			    if(strpos($merchant, $trigger['trigger']) !== false){
			        $categoryId = $trigger['category_id'];
                }
            }

            if(!isset($categoryId)){ $categoryId = '1'; }

			$transaction_info = array (
				'date'  	 	=> $date,
				'amount'  	 	=> $transaction['Amount'],
				'name'  	 	=> $name,
				'merchant'      => $merchant,
				'account_id' 	=> $currentAccount,
				'category_id' 	=> $categoryId
			);

			$test = $import->checkData($transaction_info);

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