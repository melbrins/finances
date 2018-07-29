<?php

include '../block/bddConnexion.php';



if(isset($_POST['function2call']) && !empty($_POST['function2call'])) {

    $function2call = $_POST['function2call'];

    switch($function2call) {
        case 'getEmployeesList' : $render = new render(); print $render->spendingMonthToDate("1"); break;
        case 'other' : // do something;break;
            // other cases
    }
}