<?php
	class BDD{
		protected $host = 'localhost'; 
	    protected $user = 'melbrins'; 
	    protected $password = 'M34br1n5'; 
	    protected $database = 'finances'; 

	    function getPdo(){
	    	try
			{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host=localhost;dbname=finances','melbrins', 'M34br1n5', $pdo_options);  

					
			}

	
			catch (Exception $e)
			{
				die('Erreur : ' . $e->getMessage());
			}

			return $bdd;  
	    }
	}	
	
	class render extends BDD{
		function getAllTransactions() {
			
			$transactions = $this->getPdo()->query('SELECT * FROM Transaction');


			if( $transactions != null){
				
				return $transactions;

			}else{

				return 'No transactions';

			}
		}

		function getAllAccounts() {
			$query = $this->getPdo()->query('SELECT * FROM Account');

			$accounts_Array = array();

			while ( $data = $query->fetch()){
				$accounts_Array[] = $data;
			}

			$query->closeCursor();


			return $accounts_Array;
		}	

		function getAllCategories() {
			$query = $this->getPdo()->query('SELECT * FROM Category');

			$categories_Array = array();

			while ( $data = $query->fetch()){
				$categories_Array[] = $data;
			}

			$query->closeCursor();


			return $categories_Array;
		}

		function getTransactions($account, $startDate, $endDate){

            $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE account_id = :account AND day BETWEEN :start AND :endd");

            $transactions->execute(array(
                'account' 	=> $account,
                'start'    	=> $startDate,
                'endd'    	=> $endDate
            ));

            if( $transactions != null){

                return $transactions;

            }else{

                return 'No transactions';

            }
        }

		function getMonthSpending ($account, $month, $year) {
			$query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day BETWEEN :start AND :end ");
		
			$query->execute(array(
				'account' 	=> $account,
				'start'    	=> $year . '-' . $month . '-01',
				'end'    	=> $year . '-' . $month . '-31'
			));

			while ( $data = $query->fetch()){
				
				$amount += str_replace('-', '', $data['amount']);
				
			}

			return $amount;
		}

		function getMonthIncome ($account, $month, $year) {
			$query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount NOT LIKE '%-%' AND account_id = :account AND day BETWEEN :start AND :end ");
		
			$query->execute(array(
				'account' 	=> $account,
				'start'    	=> $year . '-' . $month . '-01',
				'end'    	=> $year . '-' . $month . '-31'
			));

			while ( $data = $query->fetch()){
				
				$amount += $data['amount'];
				
			}

			return $amount;
		}

		function getMonthSpendingRange ($account, $months, $year) {

			foreach ($months as $month){

				$spent = $this->getMonthSpending($account, $month, $year);

				if(!isset($currentMax) OR $currentMax < $spent){
					$currentMax = $spent;
				}

				if(!isset($currentMin) OR $currentMin > $spent){
					$currentMin = $spent;
				}
				
			}

			$range = array(
				'min' => $currentMin,
				'max' => $currentMax
			);

			return $range;
		}

		function yearSpendingPerMonth ($account, $year){

		    $months = array(
		        '1'  => 'January',
                '2'  => 'February',
                '3'  => 'March',
                '4'  => 'April',
                '5'  => 'March',
                '6'  => 'May',
                '7'  => 'June',
                '8'  => 'July',
                '9'  => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

            foreach( $months as $key => $value){

                $spent = $this->getMonthSpending($account, $key, $year);
                $yearSpending[] = $spent;

            }

            return json_encode($yearSpending);
        }

        function spendingMonthToDate ($account){
            $currentMonth  	= date("m");
            $currentYear    = date("Y");

            $days = array();

            for($i = 0; $i <= 31; $i++){

                $amount = 0;
                $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day = :day");

                $query->execute(array(
                    'account' 	=> $account,
                    'day'    	=> $currentYear . '-' . $currentMonth . '-' . $i
                ));

                while( $data = $query->fetch()){
                    $amount += str_replace('-', '', $data['amount']);
                }

                $days[$i] = $amount;
            }


            return json_encode($days);
        }

        function spendingMonthToDateCategory ($account, $startDate, $endDate){

            $categories = $this->getAllCategories();

            foreach( $categories as $category){
                $amount = 0;
                $categoryId = $category['id'];
                $categoryName = $category['name'];

                $query = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE amount LIKE '%-%' AND category_id = :category AND account_id = :account AND day BETWEEN :start AND :end");

                $query->execute(array(
                    'account' 	=> $account,
                    'category'  => $categoryId,
                    'start'    	=> $startDate,
                    'end'    	=> $endDate
                ));

                while( $data = $query->fetch()){
                    $amount += str_replace('-', '', $data['amount']);
                }

                $categoriesSpent[$categoryName] += $amount;

            }

            return json_encode($categoriesSpent);
        }

		function averageSpend ($account) {

			// $today = date("Y-m-d");
			$currentMonth  	= date("m");
			$currentMonth --;
			$currentYear  	= date("Y");
			
			$query = $this->getPdo()->query("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = '$account' ");

			while ( $data = $query->fetch()){
				
				$amount += str_replace('-', '', $data['amount']);
				
			}

			$average = $amount / $currentMonth;

			return $average;
		}
	}

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
				
				$set = $this->getPdo()->prepare("UPDATE Transaction SET category_id = :category WHERE id = :id ");

				$set->execute(array(
					'category'  => $id,
					'id'        => $data['id']
				));
			}

            header("Location:/index.php");

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