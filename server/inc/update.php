<?php
function updateDataTable($data)
{
    include 'connection.php';

    $id_fild = $data['id_fild'];
    $id = $data['id'];
    $field = $data['field'];
    $value = $data['value'];
    $table = $data['table'];

    // Hash password if field is 'password'
    if ($field === 'password') {
        $value = password_hash($value, PASSWORD_DEFAULT);
    }
    
    $value = mysqli_real_escape_string($con, $value);
    $field = mysqli_real_escape_string($con, $field);
    $table = mysqli_real_escape_string($con, $table);
    $id_fild = mysqli_real_escape_string($con, $id_fild);
    $id = mysqli_real_escape_string($con, $id);

    // If updating tracking_status in request table, record status change history
    if ($table === 'request' && $field === 'tracking_status' && $id_fild === 'request_id') {
        // Get current status before update
        $current_query = "SELECT tracking_status FROM request WHERE request_id = '$id'";
        $current_result = mysqli_query($con, $current_query);
        $current_row = mysqli_fetch_assoc($current_result);
        $old_status = $current_row['tracking_status'] ?? null;
        
        // Validate status change - only allow sequential progression
        // Define status flow inline to avoid circular includes
        $status_flow = [
            1 => [2, 12],  // From "Order placed" can go to "Prepare Order" or "Canceled"
            2 => [3, 12],  // From "Prepare Order" can go to "Drop-off" or "Canceled"
            3 => [4],      // From "Drop-off" can go to "Picked up"
            4 => [5],      // From "Picked up" can go to "Sorting arrived"
            5 => [6],      // From "Sorting arrived" can go to "Sorting departed"
            6 => [7],      // From "Sorting departed" can go to "Hub arrived"
            7 => [8],      // From "Hub arrived" can go to "Out for delivery"
            8 => [9, 10, 11], // From "Out for delivery" can go to "Failed", "Collection", or "Delivered"
            9 => [8, 10, 11], // From "Failed" can retry delivery, go to collection, or mark delivered
            10 => [11],    // From "Collection" can go to "Delivered"
            11 => [],       // "Delivered" is final
            12 => [],       // "Canceled" is final
        ];
        
        // Check if it's a custom status (ID >= 100)
        $is_custom_status = (intval($value) >= 100);
        
        // For custom statuses, allow them to be added from any status (flexible workflow)
        // For default statuses, enforce step-by-step progression
        if (!$is_custom_status) {
            $next_statuses = isset($status_flow[$old_status]) ? $status_flow[$old_status] : [];
            if (!in_array($value, $next_statuses)) {
                // Invalid status change - return error
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid status change. You can only move to the next valid status step by step.']);
                exit;
            }
        }
        
        // Only record if status actually changed
        if ($old_status != $value) {
            // Check if this status already has a timestamp (don't overwrite)
            $check_query = "SELECT * FROM request_status_history WHERE request_id = '$id' AND status = '$value'";
            $check_result = mysqli_query($con, $check_query);
            
            // Only insert if this status doesn't have a timestamp yet
            if (mysqli_num_rows($check_result) == 0) {
                $history_sql = "INSERT INTO request_status_history (request_id, status, status_date) VALUES ('$id', '$value', NOW())";
                mysqli_query($con, $history_sql);
            }
        }
    }

    $sql = "UPDATE $table SET $field = '$value' where $id_fild = '$id'";
    $result = mysqli_query($con, $sql);
    
    // Return JSON response for status updates
    if ($table === 'request' && $field === 'tracking_status') {
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Database update failed']);
            exit;
        }
    }
    
    return $result;
}


function updateSubCatData($data)
{
    include 'connection.php';

    $id_fild = $data['id_fild'];
    $id = $data['id'];
    $field = $data['field'];
    $value = $data['value'];
    $table = $data['table'];

    $getdatas = getAllSubCategory($id);
    $count = mysqli_num_rows($getdatas);

    if ($count > 0) {
        echo $count;
    }
    else {
        $sql = "UPDATE $table SET $field = '$value' where $id_fild = '$id'";
        return mysqli_query($con, $sql);
    }
}

function editImages($data, $img)
{
    include 'connection.php';

    $id_fild = $data['id_fild'];
    $id = $data['id'];
    $field = $data['field'];
    $table = $data['table'];

    $sql = "UPDATE $table SET $field = '$img' where $id_fild = '$id'";
    return mysqli_query($con, $sql);
}

//qty reduce code

function productQtyReduce($pid, $qty)
{
    include 'connection.php';

    $viewProducts = "SELECT * FROM products WHERE pid = '$pid'";
    $res = mysqli_query($con, $viewProducts);
    $row = mysqli_fetch_assoc($res);

    $value = $row['product_qty'] - $qty;

    $sql = "UPDATE products SET product_qty = '$value', date_updated = now() where pid = $pid";
    return mysqli_query($con, $sql);
}

function increaseQtyProduct($data)
{
    include 'connection.php';

    $serve_id = $data['serve_id'];

    $viewProducts = "SELECT * FROM server_products WHERE serve_id = '$serve_id'";
    $res = mysqli_query($con, $viewProducts);
    $row = mysqli_fetch_assoc($res);

    $pid = $row['pid'];

    $exsactProducts = "SELECT * FROM products WHERE pid = '$pid'";
    $res2 = mysqli_query($con, $exsactProducts);
    $row2 = mysqli_fetch_assoc($res2);

    $value = $row['serve_qty'] + $row2['product_qty'];

    $sql = "UPDATE products SET product_qty = '$value', date_updated = now() where pid = $pid";
    return mysqli_query($con, $sql);
}

function changePageSettings($data)
{
    include 'connection.php';
    $field = $data['field'];
    $value = $data['value'];

    $sql = "UPDATE settings SET $field = '$value'";
    return mysqli_query($con, $sql);
}

function editSettingImage($data, $img)
{
    include 'connection.php';

    $field = $data['field'];

    $sql = "UPDATE settings SET $field = '$img'";
    return mysqli_query($con, $sql);
}

function editQtyinCart($data)
{
    include 'connection.php';

    $cart_id = $data['cart_id'];
    $field = $data['field'];
    $value = $data['value'];

    $sql = "UPDATE cart SET $field = '$value', date_updated = now() where cart_id = $cart_id";
    return mysqli_query($con, $sql);	
}

function updatePaymentStatus($request_id, $payment_status, $paypal_transaction_id = null)
{
    include 'connection.php';

    $paypal_transaction_id = mysqli_real_escape_string($con, $paypal_transaction_id);
    
    if ($paypal_transaction_id) {
        $sql = "UPDATE request SET payment_status = '$payment_status', paypal_transaction_id = '$paypal_transaction_id', payment_date = now() WHERE request_id = '$request_id'";
    } else {
        $sql = "UPDATE request SET payment_status = '$payment_status', payment_date = now() WHERE request_id = '$request_id'";
    }
    
    return mysqli_query($con, $sql);
}

function updatePassword($data)
{
    include 'connection.php';
    
    $customer_id = mysqli_real_escape_string($con, $data['customer_id']);
    $new_password = $data['new_password'];
    
    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $hashed_password = mysqli_real_escape_string($con, $hashed_password);
    
    $sql = "UPDATE customer SET password = '$hashed_password' WHERE customer_id = '$customer_id'";
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'error' => 'Failed to update password'));
    }
}

?>