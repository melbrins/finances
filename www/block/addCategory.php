<?php

include '../block/bddConnexion.php';



if(isset($_POST['category_name']) AND isset($_POST['category_trigger']))
{

	try
	{

		$info = array (
			'name'  	 	=> $_POST['category_name'],
			'trigger'  	 	=> $_POST['category_trigger']
		);

		$import = new import();
		$import->addCategory($info);
	
	}
	
	catch(Exception $e)
	{
		die('Erreur : ' .$e->getMessage());
	}

}	