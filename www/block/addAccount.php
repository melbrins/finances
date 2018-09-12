<?php

include '../block/bddConnexion.php';



if(isset($_POST['account_name']) AND isset($_POST['account_type']) AND isset($_POST['account_number']))
{

	try
	{

		$account_info = array (
			'name'  	=> $_POST['account_name'],
			'type'  	=> $_POST['account_type'],
			'number'  	=> $_POST['account_number']
		);

		$import = new import();
		$import->addAccount($account_info);
	
	}
	
	catch(Exception $e)
	{
		die('Erreur : ' .$e->getMessage());
	}

}	