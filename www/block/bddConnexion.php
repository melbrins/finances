<?php
	class BDD{
		protected $host = 'localhost'; 
	    protected $user = 'root';
	    protected $password = 'password';
	    protected $database = 'app';

	    function getPdo(){
	    	try
			{
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host=localhost;dbname=app','root', 'password', $pdo_options);

					
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

		function getCategoryName($categoryId){
		    $query = $this->getPdo()->query("SELECT name FROM Category WHERE id = '$categoryId' ");
            $categoryName = $query->fetch();
		    return $categoryName[0];
        }


        function getAllTriggers() {
            $query = $this->getPdo()->query('SELECT * FROM _trigger');

            $trigger_Array = array();

            while ( $data = $query->fetch()){
                $trigger_Array[] = $data;
            }

            $query->closeCursor();


            return $trigger_Array;
        }

        function getTriggerPerCategory($categoryId){
		    $query = $this->getPdo()->prepare("SELECT * FROM _trigger WHERE category_id = :category");

		    $query->execute(array(
		        'category' => $categoryId
            ));

            return $query;
        }


        function getTransactionPerId($id){
            $transaction = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE id = :id ");


            $transaction->execute(array(
		        'id' => $id
            ));

            if( $transaction != null){

                return $transaction->fetch();

            }else{

                return 'No transactions';

            }
        }

		function getTransactions($account, $startDate, $endDate){

            $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE account_id = :account AND day BETWEEN :start AND :endd ORDER BY day DESC");

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

        function getTransactionPerCategory($account, $category, $startDate, $endDate){
            $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE account_id = :account AND category_id = :category AND day BETWEEN :start AND :endd ORDER BY day DESC");

            $transactions->execute(array(
                'account' 	=> $account,
                'category'  => $category,
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

        function getMonthSpendingPerCategory ($account, $category, $month, $year) {
            $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND category_id = :category AND day BETWEEN :start AND :end ");

            $query->execute(array(
                'account' 	=> $account,
                'category' 	=> $category,
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
                '5'  => 'May',
                '6'  => 'June',
                '7'  => 'July',
                '8'  => 'August',
                '9'  => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

            foreach( $months as $key => $value){

                $spent = $this->getMonthSpending($account, $key, $year);
                $yearSpending[] = $spent;

            }

            return $yearSpending;
        }

        function yearIncomePerMonth ($account, $year){

            $months = array(
                '1'  => 'January',
                '2'  => 'February',
                '3'  => 'March',
                '4'  => 'April',
                '5'  => 'May',
                '6'  => 'June',
                '7'  => 'July',
                '8'  => 'August',
                '9'  => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

            foreach( $months as $key => $value){

                $income = $this->getMonthIncome($account, $key, $year);
                $yearIncome[] = $income;

            }

            return $yearIncome;
        }

        function yearOnYear ($account, $year, $type){

		    if($type == 'credit') {

		        $income['currentYear']      = $this->yearIncomePerMonth($account, $year);
                $income['previousYear']     = $this->yearIncomePerMonth($account, $year - 1);

                return $income;

            }else{

		        $spending['currentYear']    = $this->yearSpendingPerMonth($account, $year);
                $spending['previousYear']   = $this->yearSpendingPerMonth($account, $year - 1);

                return $spending;

            }
        }

        function incomeVsSpending ($account, $year){

		    $amount['income']   = $this->yearIncomePerMonth($account, $year);
		    $amount['spending'] = $this->yearSpendingPerMonth($account, $year);

		    return $amount;
        }

        function yearSpendingPerMonthPerCategory ($account, $category, $year){

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

                $spent = $this->getMonthSpendingPerCategory($account, $category, $key, $year);
                $yearSpending[$value] = $spent;

            }

            return json_encode($yearSpending);
        }

        // param account id, year, month, day
        // return array days
        function spendingMonthToDate ($account, $year, $month, $day){

            $days = array();

            for($i = 1; $i <= $day; $i++){

                $days[$i] = $this->daySpending($account, $year, $month, $i);
            }

            return $days;
        }

        // param account id, year, month, day
        // return int
        function daySpending($account, $year, $month, $day){
            $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day = :day");

            $query->execute(array(
                'account' 	=> $account,
                'day'    	=> $year . '-' . $month . '-' . $day
            ));

            $amount = $this->sumSpending($query);

            return $amount;
        }


        //  param Mysql query result
        //  return int
        function sumSpending($query){
		    $amount = 0;

            while( $data = $query->fetch()){
                $amount += str_replace('-', '', $data['amount']);
            }

            return $amount;
        }

        // param account id, year, month, day
        // return int
        function nbrTransaction($account, $start, $end){
            $query = $this->getPdo()->prepare("SELECT COUNT(id) FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day BETWEEN :start AND :end");

            $query->execute(array(
                'account' 	=> $account,
                'start'    	=> $start,
                'end'       => $end
            ));

            return $query->fetch();
        }

        function nbrTransactionTrigger($account, $start, $end, $trigger){
            $query = $this->getPdo()->prepare("SELECT COUNT(id) FROM Transaction WHERE name LIKE :trigger AND account_id = :account AND day BETWEEN :start AND :end");

            $query->execute(array(
                'account' 	=> $account,
                'start'    	=> $start,
                'end'       => $end,
                'trigger'   => '%'. $trigger .'%'
            ));

            return $query->fetch();
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

        function yearAverage($account, $year){

		    $currentYear    = date("Y");
            $currentMonth  	= date("m");
            $currentMonth --;

		    if($currentYear == $year){
                $yearAverage = $this->yearSpendingToDate($account) / $currentMonth;
            }else{
                $yearAverage = $this->yearSpending($account, $year) / 12;
            }

            return $yearAverage;
        }

        function yearSpending($account, $year){
            $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day BETWEEN :start AND :end ");

            $query->execute(array(
                'account' 	=> $account,
                'start'    	=> $year . '-01-01',
                'end'    	=> $year . '-12-31'
            ));

            $amount = 0;

            while ( $data = $query->fetch()){

                $amount += str_replace('-', '', $data['amount']);

            }

            return $amount;
        }

        function yearSpendingToDate($account){
		    $year   = date("Y");
		    $month  = date("m");
            $day    = date("d");

            $query  = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = :account AND day BETWEEN :start AND :end ");

            $query->execute(array(
                'account' 	=> $account,
                'start'    	=> $year . '-01-01',
                'end'    	=> $year . '-' . $month . '-' . $day
            ));

            $amount = 0;

            while ( $data = $query->fetch()){

                $amount += str_replace('-', '', $data['amount']);

            }

            return $amount;
        }

		function averageSpend ($account) {

			// $today = date("Y-m-d");
			$currentMonth  	= date("m");
			$currentMonth --;
			$currentYear  	= date("Y");
			
			$query = $this->getPdo()->query("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND account_id = '$account' ");

			$amount = 0;

			while ( $data = $query->fetch()){
				
				$amount += str_replace('-', '', $data['amount']);
				
			}

			$average = $amount / $currentMonth;

			return $average;
		}

		function lastTransaction($account){

		    $query = $this->getPdo()->query('SELECT * FROM Transaction ORDER BY id DESC LIMIT 0, 1');

		    return $query->fetch();
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