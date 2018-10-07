<?php

include '../block/bddConnexion.php';



if(isset($_POST['function2call']) && !empty($_POST['function2call'])) {

    $function2call = $_POST['function2call'];
    $currentYear    = date("Y");
    $currentMonth  	= date("m");
    $currentDay     = date('d');
    $previousMonth  = $currentMonth - 1;
    $transactionId  = $_POST['transaction'];
    $category       = $_POST['category'];
    $allTransactions = $_POST['all'];


    if(!isset($_POST['start']) OR !isset($_POST['end'])) {

        $startDate      = $currentYear . '-' . $currentMonth . '-01';
        $endDate        = $currentYear . '-' . $currentMonth . '-31';

    }else{

        $startDate      = $_POST['start'];
        $endDate        = $_POST['end'];

    }

    if(!isset($_POST['year'])){
        $year = $currentYear;
    }else{
        $year = $_POST['year'];
    }

    $category   = $_POST['category'];
    $type       = $_POST['type'];

    $render = new render();

    switch($function2call) {
        case 'spendingMonthToDateCategory'      : print $render->spendingMonthToDateCategory("1", $startDate, $endDate); break;
        case 'spendingMonthToDate'              : print json_encode($render->spendingMonthToDate("1", $currentYear, $currentMonth, $currentDay)); break;
        case 'yearSpendingPerMonthPerCategory'  : print json_encode($render->yearSpendingPerMonthPerCategory("1", $category, $year)); break;
        case 'yearOnYear'                       : print json_encode($render->yearOnYear("1", $year, $type)); break;
        case 'incomeVsSpending'                 : print json_encode($render->incomeVsSpending("1", $year)); break;
        case 'updateCategory'                   : print $render->updateCategory($transactionId, $category, $allTransactions);
        case 'other' : break;// do something;break;
            // other cases
    }
}