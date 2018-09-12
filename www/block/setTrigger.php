<?php

include '../block/bddConnexion.php';



if(isset($_POST['categoryId']) AND isset($_POST['categoryTrigger']))
{

	try
	{

		$info = array (
			'id'      	 	=> $_POST['categoryId'],
			'trigger'  	 	=> $_POST['categoryTrigger']
		);

		$import = new import();
		$import->setTrigger($_POST['categoryId'], $_POST['categoryTrigger']);
	
	}
	
	catch(Exception $e)
	{
		die('Erreur : ' .$e->getMessage());
	}

}	