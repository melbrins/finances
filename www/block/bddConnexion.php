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
    // ==============
    // DAY
    // ==============
    function getDate($date){
        return date('d F Y', strtotime($date));
    }

    function getDay($day, $account){

        $day_array = array();

        $query = $this->getPdo()->prepare('SELECT * FROM Day WHERE day = :day AND account_id = :account');

        $query->execute(array(
            'day' => $day,
            'account' => $account
        ));

        while ( $data = $query->fetch()){
            $day_array = $data;
        }

        return $day_array;
    }

    function refresh(){
        $accounts = $this->getAllAccounts();

        foreach ($accounts as $account){
            $this->refreshDay($account['id']);
        }

        $this->refreshDay(0);
        $this->refreshTransaction();
    }

    function refreshDay($account){

        if ($account != 0) {
            $query = $this->getPdo()->prepare('SELECT day, ROUND(SUM(amount), 2) AS dayTotal FROM Transaction WHERE account_id = :account_id GROUP BY day');

            $query->execute(array(
                'account_id' => $account
            ));
        } else {
            $query = $this->getPdo()->query('SELECT day, ROUND(SUM(amount),2) AS dayTotal FROM Transaction GROUP BY day');
        }

        while ( $data = $query->fetch()){
            $dayofweek = date('l', strtotime($data['day']));

            $checkDay = $this->getPdo()->prepare("SELECT day FROM Day WHERE day = :day AND account_id = :account");

            $checkDay->execute(array(
                'day'       => $data['day'],
                'account'   => $account
            ));

            $dbDay = $checkDay->fetch();

            if($dbDay){
                $updateDay = $this->getPdo()->prepare("UPDATE Day SET dayTotal = :dayTotal WHERE day = :day AND account_id = :account");

                $updateDay->execute(array(
                    'day' => $data['day'],
                    'account' => $account,
                    'dayTotal' => $data['dayTotal']
                ));
            }else {
                $insertDay = $this->getPdo()->prepare("INSERT into Day (day, dayTotal,day_week, account_id) VALUES (:day, :total, :day_week, :account_id)");

                $insertDay->execute(array(
                    'day' => $data['day'],
                    'total' => $data['dayTotal'],
                    'day_week' => $dayofweek,
                    'account_id' => $account
                ));
            }

        }
    }

    function refreshTransaction(){
        $query = $this->getPdo()->query('SELECT * FROM Day');

        while ( $data = $query->fetch()){

            $updateTransaction = $this->getPdo()->prepare("UPDATE Transaction SET day_id = :day_id WHERE day = :day AND account_id = :account");

            $updateTransaction->execute(array(
                'day_id'    => $data['id'],
                'day'       => $data['day'],
                'account'   => $data['account_id']
            ));

        }
    }


    // ==============
    // Get value for Year and Month from day
    // ==============
    function populateTransactionDate(){
        $query = $this->getPdo()->query('SELECT * FROM Transaction');

        while ( $transaction = $query->fetch()){
            $date = explode("-",$transaction['day']);

            $updateTransaction = $this->getPdo()->prepare("UPDATE Transaction SET year = :year, month = :month WHERE id = :id");

            $updateTransaction->execute(array(
                'id' => $transaction['id'],
                'month' => $date[1],
                'year' => $date[0]
            ));

        }
    }

    function populateTaxYear(){

    }

    function getYearRevenue(){
        $query = $this->getPdo()->query("SELECT year, account_id, ROUND(SUM(amount),2) AS yearTotal FROM Transaction GROUP BY year, account_id");
        $revenuPerYear = array();

        while ( $year = $query->fetch()){
            $revenuPerYear[$year['account_id']][] = array(
                'year' => $year['year'],
                'account' => $year['account_id'],
                'total' => $year['yearTotal']
            );
        }

        return $revenuPerYear;

    }

    // ==============
    // ACCOUNTS
    // ==============
    function getAllAccounts() {
        $query = $this->getPdo()->query('SELECT * FROM Account');

        $accounts_Array = array();

        while ( $data = $query->fetch()){
            $accounts_Array[] = $data;
        }

        $query->closeCursor();


        return $accounts_Array;
    }

    function getAccountName($accountId){
        $query = $this->getPdo()->query("SELECT name FROM Account WHERE id = '$accountId' ");
        $accountName = $query->fetch();
        return $accountName[0];
    }

    function getAccountType($accountId){
        $query = $this->getPdo()->query("SELECT type FROM Account WHERE id = '$accountId' ");
        $accountType = $query->fetch();
        return $accountType[0];
    }

    // ==============
    // CATEGORIES
    // ==============
    function getAllCategories() {
        $query = $this->getPdo()->query('SELECT * FROM Category');

        $categories_Array = array();

        while ( $data = $query->fetch()){
            $categories_Array[] = $data;
        }

        $query->closeCursor();


        return $categories_Array;
    }

    function getCategoryByType($type){
        $query = $this->getPdo()->prepare("SELECT * FROM Category WHERE type = :type");

        $query->execute(array(
            'type' => $type
        ));

        while( $category = $query->fetch()){
            $categories_Array[] = $category;
        }

        $query->closeCursor();

        return $categories_Array;
    }

    function getCategoryByName($name){

        $category = $this->getPdo()->prepare("SELECT id FROM Category WHERE name LIKE :name");

        $category->execute(array(
            'name' => $name
        ));

        return $category->fetch();
    }

    function getCategoryExpenses(){

        $query = $this->getPdo()->query( "SELECT id, name FROM Category WHERE subcategory = 'Expenses' ");

        while ($category = $query->fetch()){
            $categories[] = $category;
        }

        return $categories;
    }

//    function getCategoryTotal(){
//        SELECT category_id, ROUND(SUM(amount),2) AS categoryTotal FROM Transaction WHERE day BETWEEN '2017-04-01' AND '2018-03-31' GROUP BY category_id
//    }

    function getCategoryName($categoryId){
        $query = $this->getPdo()->query("SELECT name FROM Category WHERE id = '$categoryId' ");
        $categoryName = $query->fetch();
        return $categoryName[0];
    }

    function updateCategory($transactionId, $category, $allTransactions){

        if($allTransactions == true){

            $merchantName = $this->getMerchant($transactionId);

            if($merchantName == null){
                $merchantName = $this->getTransactionName($transactionId);
                $merchantName = str_replace("'", "\'", $merchantName);
                $merchantName = explode(' ON ', $merchantName);

                $set = $this->getPdo()->query("UPDATE Transaction SET category_id = '$category' WHERE name LIKE '%$merchantName[0]%' ");
            }else{
                $set = $this->getPdo()->query("UPDATE Transaction SET category_id = '$category' WHERE merchant = '$merchantName' ");
            }

        }else{
            $set = $this->getPdo()->prepare("UPDATE Transaction SET category_id = :category WHERE id = :id ");

            $set->execute(array(
                'category' => $category,
                'id' => $transactionId
            ));
        }

        return 'success';

    }

    function jSonCategory($account, $category, $startDate, $endDate){

        $transactions = $this->getTransactionPerCategory($account, $category, $startDate, $endDate);

        if( $transactions != 'No transactions'){

            while( $transaction = $transactions->fetch()) {

                $category       = $this->getCategoryName($transaction['category_id']);
                $account        = $this->getAccountName($transaction['account_id']);
                $date_split     = explode('-', $transaction['day']);

                $date = array(
                    'year'  => $date_split[0],
                    'month' => $this->getMonthName($date_split[1]),
                    'day'   => $date_split[2]
                );

                $spending[$date['year']][$date['month']][$date['day']][]= array(
                    'id'            => $transaction['id'],
                    'day'           => $transaction['day'],
                    'amount'        => $transaction['amount'],
                    'name'          => $transaction['name'],
                    'category_id'   => $transaction['category_id'],
                    'category_name' => $category,
                    'account_id'    => $transaction['account_id'],
                    'account_name'  => $account,
                    'merchant'      => $transaction['merchant']
                );

            }

            return json_encode($spending);

        }else{

            return 'No transactions';

        }
    }

    // ==============
    // TAX YEAR
    // ==============

    function getAllTaxYear(){

        $tax_year_all = $this->getPdo()->query("SELECT id FROM Tax_Year");

        while($tax_year = $tax_year_all->fetch()){
            $tax_year_ids[] = $tax_year['id'];
        };

        return $tax_year_ids;

    }

    function getTaxYear($id){

        $tax_year = $this->getPdo()->query("SELECT * FROM Tax_Year WHERE id = $id");

        return $tax_year->fetch();
    }

    function updateTaxYear(){
        // get all tax year id
        $tax_year_all = $this->getAllTaxYear();

        foreach($tax_year_all as $tax_year_id){

            $tax_year = $this->getTaxYear($tax_year_id);

            $tax_year_name = explode("-", $tax_year['name']);
            $tax_year_start = $tax_year_name[0] . '-04-01';
            $tax_year_end = $tax_year_name[1] . '-03-31';


            $tax_year_dividend = $this->getDividend($tax_year_start, $tax_year_end);
            $tax_year_expenses = $this->getExpenses($tax_year_start, $tax_year_end);
            $tax_year_salary   = $this->getPersonalSalary($tax_year_start, $tax_year_end);
            $tax_year_income   = $this->getIncome($tax_year_start, $tax_year_end);
            $tax_year_paid_tax = $this->getCorporateTaxes($tax_year_start, $tax_year_end);
            $tax_year_self_assessment = $this->getSelfAssessment($tax_year_start, $tax_year_end);
            $tax_year_personal_revenue = $this->getPersonalYearRevenue($tax_year_id);
            $tax_year_business_revenue = $this->getBusinessYearRevenue($tax_year_id);

            $watchCategory = array(
                'id' => $tax_year_id,
                'Dividend' => $tax_year_dividend,
                'Expenses' => $tax_year_expenses,
                'Salary'   => $tax_year_salary['total'],
                'Income'   => $tax_year_income['total'],
                'Taxes'    => $tax_year_paid_tax,
                'self-assessment'    => $tax_year_self_assessment
            );

            $update = $this->getPdo()->prepare("UPDATE Tax_Year SET dividend_total = :dividend, salary_total = :salary, expenses_total = :expenses, income_total = :income, corporate_tax_total = :taxes, self_assessment_total = :sa, personal_revenue = :personal, business_revenue = :business WHERE id = :id");

            $update->execute(array(
                'dividend'  => $tax_year_dividend,
                'expenses'  => $tax_year_expenses['total'],
                'salary'    => $tax_year_salary['total'],
                'income'    => $tax_year_income['total'],
                'taxes'     => $tax_year_paid_tax,
                'sa'        => $tax_year_self_assessment,
                'id'        => $tax_year_id,
                'personal'  => $tax_year_personal_revenue,
                'business'  => $tax_year_business_revenue
            ));

            $updateTransactions = $this->getPdo()->prepare("UPDATE Transaction SET tax_year_id = :year WHERE day BETWEEN :start AND :end");

            $updateTransactions->execute(array(
                'year' => $tax_year_id,
                'start' => $tax_year_start,
                'end' => $tax_year_end
            ));
        }

        return $watchCategory;
    }

    function getCorporateTaxes($start, $end){

        $corporate_Taxes_id = $this->getCategoryByName('Corporate Taxes');

        $query = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

        $query->execute(array(
            'category' => $corporate_Taxes_id[0],
            'start' => $start,
            'end' => $end
        ));

        while($corporate_Taxes = $query->fetch()){
            $taxes_total = ( $corporate_Taxes['total']) ? $corporate_Taxes['total'] : '0';
        }

        return $taxes_total;
    }

    function getSelfAssessment($start, $end){

        $taxes_id = $this->getCategoryByName('Taxes');

        $query = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

        $query->execute(array(
            'category' => $taxes_id[0],
            'start' => $start,
            'end' => $end
        ));

        while($taxes = $query->fetch()){
            $taxes_total = ( $taxes['total']) ? $taxes['total'] : '0';
        }

        return $taxes_total;
    }

    function getDividend($start, $end){

        $dividend_id = $this->getCategoryByName('Dividend');

        $query = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

        $query->execute(array(
            'category' => $dividend_id[0],
            'start' => $start,
            'end' => $end
        ));

        while($dividend = $query->fetch()){
            $dividend_total = ( $dividend['total']) ? $dividend['total'] : '0';
        }

        return $dividend_total;
    }

    function getExpenses($start, $end){

        $expenses_id = $this->getCategoryExpenses();
        $expenses_total = '0';
        $expenses_array = array();

        foreach ( $expenses_id as $expense) {

            $expenses = $this->getPdo()->prepare("SELECT name, ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

            $expenses->execute(array(
                'category' => $expense['id'],
                'start' => $start,
                'end' => $end
            ));


            while ($expense_sum = $expenses->fetch()) {
                $expense_total = ($expense_sum['total'] !== NULL) ? $expense_sum['total'] : '0';

                $expenses_array[] = array(
                    'name' => $expense['name'],
                    'total' => $expense_total
                );

            }

        }

        foreach($expenses_array as $data){
            $expenses_total += $data['total'];
        }

        $expenses_array['total'] = $expenses_total;

        return $expenses_array;
    }

    function getPersonalSalary($start, $end){

        $personal_salary_id = $this->getCategoryByName('Personal Salary');

        $query = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

        $query->execute(array(
            'category' => $personal_salary_id['id'],
            'start' => $start,
            'end' => $end
        ));

        while($personal_salary = $query->fetch()){
            $personal_salary_total = ($personal_salary) ? $personal_salary : '0';
        }

        return $personal_salary_total;

    }

    function getIncome($start, $end){
        $income_id = $this->getCategoryByName('Income');

        $query = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id = :category AND day BETWEEN :start AND :end");

        $query->execute(array(
            'category' => $income_id['id'],
            'start' => $start,
            'end' => $end
        ));

        while($income = $query->fetch()){
            $income_total = ($income) ? $income : '0';
        }

        return $income_total;
    }

    function getBusinessYearRevenue($id){

        $query = $this->getPdo()->prepare ( "SELECT * FROM Tax_Year WHERE id = :id");

        $query->execute(array(
            'id' => $id
        ));

        while($tax_year = $query->fetch()){
            $income   = $tax_year['income_total'];
            $dividend = $tax_year['dividend_total'] * -1;
            $expenses = $tax_year['expenses_total'] * -1;
            $salary   = $tax_year['salary_total'];
            $taxes    = $tax_year['corporate_tax_total'] * -1;

            $revenue = $income - ($dividend + $expenses + $salary + $taxes);

        }

        return $revenue;
    }

    function getPersonalYearRevenue($id){

        $taxes_id = $this->getCategoryByName('Taxes');

        $query = $this->getPdo()->prepare("SELECT * FROM Tax_Year WHERE id = :id");

        $query->execute(array(
            'id' => $id
        ));

        while($tax_year = $query->fetch()){
            $period     = explode("-", $tax_year['name']);
            $start      = $period[0] . '-04-01';
            $end        = $period[1] . '-03-31';
            $dividend   = $tax_year['dividend_total'] * -1;
            $salary     = $tax_year['salary_total'];
            $taxes      = $tax_year['self_assessment_total'] * -1;
        }

        $spending = $this->getPdo()->prepare("SELECT ROUND(SUM(amount),2) AS total FROM Transaction WHERE category_id != :taxes AND amount LIKE '-%' AND account_id = :account AND day BETWEEN :start AND :end");

        $spending->execute(array(
            'taxes' => $taxes_id['id'],
            'account' => 1,
            'start' => $start,
            'end'   => $end
        ));

        while($spent = $spending->fetch()){
            $sp = ($spent) ? $spent['total'] * -1 : '0';
        }

        $revenue = $dividend + $salary - $taxes - $sp;

        return $revenue;
    }

    // ==============
    // TRANSACTIONS
    // ==============
    function getAllTransactions() {

        $transactions = $this->getPdo()->query('SELECT * FROM Transaction');


        if( $transactions != null){

            return $transactions;

        }else{

            return 'No transactions';

        }
    }

    function getTransactionName($transactionId){
        $query = $this->getPdo()->query("SELECT name FROM Transaction WHERE id = '$transactionId' ");

        $transactionName = $query->fetch();
        $transactionName = explode(' ON ', $transactionName[0]);

        return $transactionName[0];
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

    function getJsonTransactions($account, $startDate, $endDate){
        $collection = array();

        $transactions = $this->getTransactions($account, $startDate, $endDate);

        while ($transaction  = $transactions->fetch()){
            $day = explode('-', $transaction['day']);
            $year = $day[0];
            $month = $day[1];
            $day = $day[2];

//            $collection[] = array([
//               'year' : $year,
//               'month' : [
//                    {
//                        'name' : $month,
//                        'day': [
//                        {
//                            'name' : $day,
//                            'transaction' : [
//                            {
//                                'id' : $transaction['id'],
//                                'name' : $transaction['name'],
//                                'amount' : $transaction['amount'],
//                                'category' : $transaction['category_id'],
//                                'account' : $transaction['account_id'],
//                                'merchant' : $transaction['merchant']
//                            }
//                        ]
//                        }
//                    ]
//                    }
//                ]
//            ]);

            $collection[] = array(
                'day' => $transaction['day'],
                'id' => $transaction['id'],
                'name' => $transaction['name'],
                'amount' => $transaction['amount'],
                'category' => $transaction['category_id'],
                'account' => $transaction['account_id'],
                'merchant' => $transaction['merchant']
            );
        }

        return $collection;

    }

    function getTransactions($account, $startDate, $endDate){

        if ($account != '0' OR $account != 0) {

            $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE day BETWEEN :start AND :endd ORDER BY day DESC");

            $transactions->execute(array(
                'start'    	=> $startDate,
                'endd'    	=> $endDate
            ));

        } else {

            // TODO replace with list of accounts ID
            $account = '1,2,3,4';

            $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :endd ORDER BY day DESC");

            $transactions->execute(array(
                'account' 	=> $account,
                'start'    	=> $startDate,
                'endd'    	=> $endDate
            ));

        }

        if( $transactions != null){

            return $transactions;

        }else{

            return 'No transactions';

        }
    }

    function getTransactionPerCategory($account, $category, $startDate, $endDate){
        $transactions = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE FIND_IN_SET(account_id, :account) AND category_id = :category AND day BETWEEN :start AND :endd ORDER BY day DESC");

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

    function getAllSimilarTransactions($account, $trigger){
        $query = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE name LIKE :trigger AND account_id = :account");

        $query->execute(array(
            'account' 	=> $account,
            'trigger'   => '%'. $trigger .'%'
        ));

        return $query;
    }

    function similarTransactions($account, $start, $end, $trigger){
        $query = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE name LIKE :trigger AND account_id = :account AND day BETWEEN :start AND :end");

        $query->execute(array(
            'account' 	=> $account,
            'start'    	=> $start,
            'end'       => $end,
            'trigger'   => '%'. $trigger .'%'
        ));

        return $query;
    }

    function lastTransaction($account){

        $query = $this->getPdo()->query('SELECT * FROM Transaction ORDER BY id DESC LIMIT 0, 1');

        return $query->fetch();
    }


    // ==============
    // MERCHANT
    // ==============
    function getMerchant($transactionId){
        $query = $this->getPdo()->query("SELECT merchant FROM Transaction WHERE id = '$transactionId' ");

        $merchantName = $query->fetch();

        return $merchantName[0];
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




    function getMonthName($monthNumber){
        $months = array(
            '01'  => 'January',
            '02'  => 'February',
            '03'  => 'March',
            '04'  => 'April',
            '05'  => 'May',
            '06'  => 'June',
            '07'  => 'July',
            '08'  => 'August',
            '09'  => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );

        return $months[$monthNumber];
    }

    function getMonthSpending ($account, $month, $year) {
        $account = ($account == 0) ? '1,2,3,4' : $account;

        $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :end ");

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
        $account = ($account == 0) ? '1,2,3,4' : $account;

        $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount NOT LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :endDate ");

        $query->execute(array(
            'account' 	=> $account,
            'start'    	=> $year . '-' . $month . '-01',
            'endDate'   => $year . '-' . $month . '-31'
        ));

        while ( $data = $query->fetch()){

            $amount += $data['amount'];

        }

        return $amount;
    }

    function getMonthSpendingPerCategory ($account, $category, $month, $year) {
        $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND category_id = :category AND day BETWEEN :start AND :end ");

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




    // param account id, year, month, day
    // return int
    function daySpending($account, $year, $month, $day){
        $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND day = :day");

        $query->execute(array(
            'account' 	=> $account,
            'day'    	=> $year . '-' . $month . '-' . $day
        ));

        $amount = $this->sumSpending($query);

        return $amount;
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




    //  param Mysql query result
    //  return int
    function sumSpending($query){
        $amount = 0;

        while( $data = $query->fetch()){
            $amount += str_replace('-', '', $data['amount']);
        }

        return $amount;
    }



    function spendingMonthToDateCategory ($account, $startDate, $endDate){

        $categories = $this->getAllCategories();

        foreach( $categories as $category){
            $amount = 0;
            $categoryId = $category['id'];
            $categoryName = $category['name'];

            $query = $this->getPdo()->prepare("SELECT * FROM Transaction WHERE amount LIKE '%-%' AND category_id = :category AND FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :end");

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
        $query = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :end ");

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

        $query  = $this->getPdo()->prepare("SELECT amount FROM Transaction WHERE amount LIKE '%-%' AND FIND_IN_SET(account_id, :account) AND day BETWEEN :start AND :end ");

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