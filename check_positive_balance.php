<?php 
function checkFunds($balance,$amountToWithdraw){
    if($balance >= $amountToWithdraw){
        return TRUE;
    }
    else{
        return FALSE;
    }


}

?>