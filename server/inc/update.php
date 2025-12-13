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

    // Prevent any updates to canceled orders (status 12)
    if ($table === 'request' && $id_fild === 'request_id') {
        $check_canceled = "SELECT tracking_status FROM request WHERE request_id = '$id'";
        $canceled_result = mysqli_query($con, $check_canceled);
        if ($canceled_result && mysqli_num_rows($canceled_result) > 0) {
            $canceled_row = mysqli_fetch_assoc($canceled_result);
            if (intval($canceled_row['tracking_status']) == 12) {
                // Order is canceled - prevent all updates
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'This order is canceled and cannot be modified.']);
                exit;
            }
        }
    }

    // If updating tracking_status in request table, record status change history
    if ($table === 'request' && $field === 'tracking_status' && $id_fild === 'request_id') {
        // Get current status before update
        $current_query = "SELECT tracking_status FROM request WHERE request_id = '$id'";
        $current_result = mysqli_query($con, $current_query);
        $current_row = mysqli_fetch_assoc($current_result);
        $old_status = $current_row['tracking_status'] ?? null;
        
        // Prevent changing from canceled status
        if (intval($old_status) == 12) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Canceled orders cannot be modified.']);
            exit;
        }
        
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

function saveOrderRating($data)
{
    include 'connection.php';
    
    $request_id = mysqli_real_escape_string($con, $data['request_id']);
    $rating = intval($data['rating']);
    $rating_comment = isset($data['rating_comment']) ? mysqli_real_escape_string($con, $data['rating_comment']) : null;
    
    // Validate rating (1-5)
    if ($rating < 1 || $rating > 5) {
        return json_encode(array('success' => false, 'error' => 'Rating must be between 1 and 5'));
    }
    
    // Check if order exists and belongs to the customer (if customer_id is provided)
    if (isset($data['customer_id'])) {
        $customer_id = mysqli_real_escape_string($con, $data['customer_id']);
        $check_query = "SELECT * FROM request WHERE request_id = '$request_id' AND customer_id = '$customer_id' AND is_deleted = 0";
        $check_result = mysqli_query($con, $check_query);
        
        if (mysqli_num_rows($check_result) == 0) {
            return json_encode(array('success' => false, 'error' => 'Order not found or access denied'));
        }
    }
    
    // Check if rating columns exist in the database, if not, add them automatically
    $check_rating = "SHOW COLUMNS FROM request LIKE 'rating'";
    $column_result = mysqli_query($con, $check_rating);
    
    if (mysqli_num_rows($column_result) == 0) {
        // Columns don't exist - add them automatically
        $add_rating = "ALTER TABLE `request` ADD COLUMN `rating` INT(1) DEFAULT NULL COMMENT 'Rating from 1 to 5'";
        if (!mysqli_query($con, $add_rating)) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Failed to add rating column: ' . mysqli_error($con)
            ));
        }
    }
    
    // Check and add rating_comment column
    $check_comment = "SHOW COLUMNS FROM request LIKE 'rating_comment'";
    $comment_result = mysqli_query($con, $check_comment);
    if (mysqli_num_rows($comment_result) == 0) {
        $add_comment = "ALTER TABLE `request` ADD COLUMN `rating_comment` TEXT DEFAULT NULL COMMENT 'Optional comment with rating'";
        if (!mysqli_query($con, $add_comment)) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Failed to add rating_comment column: ' . mysqli_error($con)
            ));
        }
    }
    
    // Check and add rating_date column
    $check_date = "SHOW COLUMNS FROM request LIKE 'rating_date'";
    $date_result = mysqli_query($con, $check_date);
    if (mysqli_num_rows($date_result) == 0) {
        $add_date = "ALTER TABLE `request` ADD COLUMN `rating_date` DATETIME DEFAULT NULL COMMENT 'Date when rating was submitted'";
        if (!mysqli_query($con, $add_date)) {
            return json_encode(array(
                'success' => false, 
                'error' => 'Failed to add rating_date column: ' . mysqli_error($con)
            ));
        }
    }
    
    // Check and add index if it doesn't exist
    $check_index = "SHOW INDEX FROM request WHERE Key_name = 'idx_rating'";
    $index_result = mysqli_query($con, $check_index);
    if (mysqli_num_rows($index_result) == 0) {
        $add_index = "ALTER TABLE `request` ADD INDEX `idx_rating` (`rating`)";
        mysqli_query($con, $add_index); // Don't fail if index creation fails
    }
    
    // Update rating
    if ($rating_comment) {
        $sql = "UPDATE request SET rating = '$rating', rating_comment = '$rating_comment', rating_date = NOW() WHERE request_id = '$request_id'";
    } else {
        $sql = "UPDATE request SET rating = '$rating', rating_comment = NULL, rating_date = NOW() WHERE request_id = '$request_id'";
    }
    
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        return json_encode(array('success' => true, 'message' => 'Rating saved successfully'));
    } else {
        // Get the actual MySQL error for debugging
        $mysql_error = mysqli_error($con);
        return json_encode(array(
            'success' => false, 
            'error' => 'Failed to save rating: ' . $mysql_error
        ));
    }
}

function updateCancellationRequestStatus($data)
{
    include 'connection.php';
    
    $cancellation_id = intval($data['cancellation_id']);
    $status = mysqli_real_escape_string($con, $data['status']); // 'approved' or 'rejected'
    $admin_response_comment = isset($data['admin_response_comment']) && !empty(trim($data['admin_response_comment'])) ? mysqli_real_escape_string($con, trim($data['admin_response_comment'])) : null;
    $admin_id = isset($_SESSION['emp_id']) ? intval($_SESSION['emp_id']) : null;
    
    // Validate status
    if (!in_array($status, ['approved', 'rejected'])) {
        return json_encode(array('success' => false, 'error' => 'Invalid status'));
    }
    
    // Get cancellation request details
    $get_request = "SELECT * FROM cancellation_requests WHERE cancellation_id = '$cancellation_id'";
    $request_result = mysqli_query($con, $get_request);
    
    if (!$request_result || mysqli_num_rows($request_result) == 0) {
        return json_encode(array('success' => false, 'error' => 'Cancellation request not found'));
    }
    
    $cancellation = mysqli_fetch_assoc($request_result);
    $request_id = $cancellation['request_id'];
    
    // Check if already processed
    if ($cancellation['cancellation_status'] != 'pending') {
        return json_encode(array('success' => false, 'error' => 'Cancellation request already processed'));
    }
    
    // Update cancellation request
    if ($admin_response_comment) {
        $update_sql = "UPDATE cancellation_requests 
                      SET cancellation_status = '$status', 
                          admin_response_date = NOW(), 
                          admin_response_comment = '$admin_response_comment',
                          admin_id = " . ($admin_id ? "'$admin_id'" : "NULL") . "
                      WHERE cancellation_id = '$cancellation_id'";
    } else {
        $update_sql = "UPDATE cancellation_requests 
                      SET cancellation_status = '$status', 
                          admin_response_date = NOW(),
                          admin_id = " . ($admin_id ? "'$admin_id'" : "NULL") . "
                      WHERE cancellation_id = '$cancellation_id'";
    }
    
    $update_result = mysqli_query($con, $update_sql);
    
    if (!$update_result) {
        return json_encode(array('success' => false, 'error' => 'Failed to update cancellation request: ' . mysqli_error($con)));
    }
    
    // If approved, update order status to canceled (12)
    if ($status == 'approved') {
        // Get order details to check payment status
        $order_query = "SELECT payment_status, payment_method, total_fee FROM request WHERE request_id = '$request_id'";
        $order_result = mysqli_query($con, $order_query);
        $order_data = mysqli_fetch_assoc($order_result);
        
        // Update order status
        $order_sql = "UPDATE request SET tracking_status = 12 WHERE request_id = '$request_id'";
        $order_update_result = mysqli_query($con, $order_sql);
        
        if (!$order_update_result) {
            return json_encode(array('success' => false, 'error' => 'Failed to update order status: ' . mysqli_error($con)));
        }
        
        // Record status change in history
        $history_sql = "INSERT INTO request_status_history (request_id, status, status_date) 
                       VALUES ('$request_id', 12, NOW())
                       ON DUPLICATE KEY UPDATE status_date = NOW()";
        mysqli_query($con, $history_sql);
        
        // If payment was made via PayPal, set refund status to pending
        if ($order_data && $order_data['payment_method'] == 'paypal' && $order_data['payment_status'] == 'paid') {
            $refund_amount = floatval($order_data['total_fee']);
            $refund_sql = "UPDATE cancellation_requests 
                          SET refund_status = 'pending', 
                              refund_amount = '$refund_amount'
                          WHERE cancellation_id = '$cancellation_id'";
            mysqli_query($con, $refund_sql);
        }
    }
    // If rejected, order continues normally (no status change needed)
    
    return json_encode(array('success' => true, 'message' => 'Cancellation request ' . $status . ' successfully'));
}

?>