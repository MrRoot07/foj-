<?php

function deleteDataTables($data){
    include 'connection.php';

    $id_fild =  $data['id_fild'];
    $id =  $data['id'];
    $table = $data['table'];

    // Prevent deletion of canceled orders
    if ($table === 'request' && $id_fild === 'request_id') {
        $check_canceled = "SELECT tracking_status FROM request WHERE request_id = '$id'";
        $canceled_result = mysqli_query($con, $check_canceled);
        if ($canceled_result && mysqli_num_rows($canceled_result) > 0) {
            $canceled_row = mysqli_fetch_assoc($canceled_result);
            if (intval($canceled_row['tracking_status']) == 12) {
                // Order is canceled - prevent deletion
                return false;
            }
        }
    }

    $sql = "UPDATE $table SET is_deleted = '1' where $id_fild='$id'";
    return mysqli_query($con, $sql);	
}

function permanantDeleteDataTable($data){
    include 'connection.php';

    $id_fild =  $data['id_fild'];
    $id =  $data['id'];
    $table = $data['table'];

    $sql = "DELETE FROM $table WHERE $id_fild = $id";
    return mysqli_query($con, $sql);	
}


function deleteAllCartItems($customer_id){

	include 'connection.php';

	$sql2 = "DELETE FROM cart where customer_id = $customer_id";
    return mysqli_query($con, $sql2);
}


?>