<?php

    class import extends BDD{

    function checkData($data) {
        $query = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE day = :date AND amount = :amount AND name = :name AND account_id = :accountId AND category_id = :categoryId ");

        $query->execute(array(
            'date'  		=> $data['date'],
            'amount'  		=> $data['amount'],
            'name' 	 		=> $data['name'],
            'accountId'  	=> $data['account_id'],
            'categoryId'  	=> $data['category_id']
        ));

        $checked = $query->fetch();

        return $checked;
    }

    // Function to use in a loop to add each transaction into the database.
    function addTransaction($data) {

        $import = $this->getPdo()->prepare("INSERT into Transaction (day, amount, name, account_id, category_id) VALUES ( :date, :amount, :name, :accountId, :categoryId )");

        $import->execute(array(
            'date'  		=> $data['date'],
            'amount'  		=> $data['amount'],
            'name' 	 		=> $data['name'],
            'accountId'  	=> $data['account_id'],
            'categoryId'  	=> $data['category_id']
        ));

        return 'done';
    }

    function addAccount($data){

        $query  = $this->getPdo()->prepare("INSERT into Account (name, type, account_number) VALUES ( :name , :type, :account_number) ");

        $query->execute(array(
            'name'  			=> $data['name'],
            'type'  			=> $data['type'],
            'account_number'  	=> $data['number']
        ));

        header("Location:/index.php");
        return $query;
    }

    function addCategory($data){

        $query = $this->getPdo()->prepare("INSERT into Category (name, category_trigger) VALUES ( :name, :trigger) ");

        $query->execute(array(
            'name' 	 	=> $data['name'],
            'trigger'  	=> $data['trigger']
        ));

        header("Location:/index.php");

        return $query;

    }

    function setTrigger($id, $trigger){

        $query = $this->getPdo()->query("SELECT * FROM Transaction WHERE name LIKE '%{$trigger}%'");

        while($data = $query->fetch()){

            $set = $this->getPdo()->prepare("UPDATE Transaction SET category_id = :category, merchant = :merchant WHERE id = :id ");

            $set->execute(array(
                'category'  => $id,
                'merchant'  => $trigger,
                'id'        => $data['id']
            ));
        }

    }

    function csvToArray($csv) {

        $csv = array_map('str_getcsv', file($csv));

        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        array_shift($csv); # remove column header

        return $csv;
    }

}

?>