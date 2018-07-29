<?php

include '../block/bddConnexion.php';



if(isset($_POST['function2call']) && !empty($_POST['function2call'])) {

    $function2call = $_POST['function2call'];
    $year = $_POST['year'];

    switch($function2call) {
        case 'getEmployeesList' : $render = new render(); print $render->yearSpendingPerMonth("1", $year); break;
        case 'other' : // do something;break;
            // other cases
    }
}