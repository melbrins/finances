<?php

include '../block/bddConnexion.php';



if(isset($_POST['function2call']) && !empty($_POST['function2call'])) {

    $function2call = $_POST['function2call'];

    if(!isset($_POST['start']) OR !isset($_POST['end'])) {

        $currentYear    = date("Y");
        $currentMonth  	= date("m");

        $startDate      = $currentYear . '-' . $currentMonth . '-01';
        $endDate        = $currentYear . '-' . $currentMonth . '-31';

    }else{

        $startDate      = $_POST['start'];
        $endDate        = $_POST['end'];

    }

    switch($function2call) {
        case 'spendingMonthToDate' : $render = new render(); print $render->spendingMonthToDateCategory("1", $startDate, $endDate); break;
        case 'other' : // do something;break;
            // other cases
    }
}