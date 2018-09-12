<?php

include '../block/bddConnexion.php';



if(isset($_POST['date']) AND isset($_POST['amount']) AND isset($_POST['name']) AND isset($_POST['account_id']) AND isset($_POST['category_id']))
{

	try
	{

		$transaction_info = array (
			'date'  	 	=> $_POST['date'],
			'amount'  	 	=> $_POST['amount'],
			'name'  	 	=> $_POST['name'],
			'account_id' 	=> $_POST['account_id'],
			'category_id' 	=> $_POST['category_id']
		);

		$import = new import();
		$import->addTransaction($transaction_info);
	
	}
	
	catch(Exception $e)
	{
		die('Erreur : ' .$e->getMessage());
	}

}	