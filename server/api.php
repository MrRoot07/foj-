<?php
// Start output buffering to prevent unwanted output
ob_start();

if (session_id() == '') {
    session_start();
}

include 'inc/get.php';
include 'inc/connection.php';
include 'inc/update.php';
include 'inc/delete.php';
include 'inc/add.php';
include 'inc/paypal.php';

if (isset($_GET['function_code']) && $_GET['function_code'] == 'getCustomerTbleData') {
    echo json_encode(getAllCustomer());
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updatePaymentStatus') {
    ob_clean();
    header('Content-Type: application/json');
    
    // Check admin authentication
    if (!isset($_SESSION['admin']) && !isset($_SESSION['employee'])) {
        echo json_encode(['success' => false, 'error' => 'Authentication required']);
        ob_end_flush();
        exit;
    }
    
    if (isset($_POST['id']) && isset($_POST['value']) && isset($_POST['table']) && $_POST['table'] === 'request') {
        include 'inc/connection.php';
        
        $request_id = intval($_POST['id']);
        $payment_status = mysqli_real_escape_string($con, $_POST['value']);
        $failure_reason = isset($_POST['payment_failure_reason']) && !empty(trim($_POST['payment_failure_reason'])) 
            ? mysqli_real_escape_string($con, trim($_POST['payment_failure_reason'])) 
            : null;
        
        // Validate payment status
        if (!in_array($payment_status, ['pending', 'paid', 'failed'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid payment status']);
            ob_end_flush();
            exit;
        }
        
        // Check if order is canceled
        $check_canceled = "SELECT tracking_status FROM request WHERE request_id = '$request_id'";
        $canceled_result = mysqli_query($con, $check_canceled);
        if ($canceled_result && mysqli_num_rows($canceled_result) > 0) {
            $canceled_row = mysqli_fetch_assoc($canceled_result);
            if (intval($canceled_row['tracking_status']) == 12) {
                echo json_encode(['success' => false, 'error' => 'This order is canceled and cannot be modified.']);
                ob_end_flush();
                exit;
            }
        }
        
        // Check if payment_failure_reason column exists, if not, add it
        $checkColumn = "SHOW COLUMNS FROM request LIKE 'payment_failure_reason'";
        $columnResult = mysqli_query($con, $checkColumn);
        
        if (mysqli_num_rows($columnResult) == 0) {
            // Add column if it doesn't exist
            $addColumn = "ALTER TABLE request ADD COLUMN `payment_failure_reason` TEXT DEFAULT NULL COMMENT 'Reason for payment failure'";
            mysqli_query($con, $addColumn);
        }
        
        // Update payment status
        if ($payment_status === 'failed' && $failure_reason) {
            $sql = "UPDATE request SET payment_status = '$payment_status', payment_failure_reason = '$failure_reason' WHERE request_id = '$request_id'";
        } else {
            // Clear failure reason if status is not failed
            $sql = "UPDATE request SET payment_status = '$payment_status', payment_failure_reason = NULL WHERE request_id = '$request_id'";
        }
        
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Payment status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update payment status: ' . mysqli_error($con)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    }
    ob_end_flush();
    exit;
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updateData') {
    updateDataTable($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'insertImageUpload') {

    $img = $_FILES['file']['name'];
    $target_dir = "uploads/gallery/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "jfif", "svg", "webp");

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $img);
        insertImagetoGallery($img);
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'imageUploadProducts') {

    $img = $_FILES['file']['name'];
    $target_dir = "uploads/products/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "jfif", "svg", "webp");

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $img);
        editImages($_POST, $img);
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addProducts') {

    $img = $_FILES['file']['name'];
    $target_dir = "uploads/products/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "jfif", "svg", "webp");
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updateSettings') {
    ob_clean();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin'])) {
        echo json_encode(['success' => false, 'error' => 'Admin authentication required']);
        ob_end_flush();
        exit;
    }
    
    if (isset($_POST['field']) && isset($_POST['value'])) {
        $field = mysqli_real_escape_string($con, $_POST['field']);
        $value = mysqli_real_escape_string($con, $_POST['value']);
        
        // Validate field name (security check)
        $allowedFields = ['price_per_km'];
        if (!in_array($field, $allowedFields)) {
            echo json_encode(['success' => false, 'error' => 'Invalid field']);
            ob_end_flush();
            exit;
        }
        
        // Check if column exists, if not, add it
        $checkColumn = "SHOW COLUMNS FROM settings LIKE '$field'";
        $columnResult = mysqli_query($con, $checkColumn);
        
        if (mysqli_num_rows($columnResult) == 0) {
            // Add column if it doesn't exist
            $addColumn = "ALTER TABLE settings ADD COLUMN `$field` DECIMAL(10,2) DEFAULT 5.00";
            mysqli_query($con, $addColumn);
        }
        
        // Update settings
        $sql = "UPDATE settings SET `$field` = '$value'";
        $result = mysqli_query($con, $sql);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Settings updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update settings: ' . mysqli_error($con)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    }
    ob_end_flush();
    exit;
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'deleteData') {
    ob_clean(); // Clear any output before JSON
    header('Content-Type: application/json');
    
    // Check if trying to delete a canceled order
    if (isset($_POST['table']) && $_POST['table'] === 'request' && isset($_POST['id'])) {
        include 'inc/connection.php';
        $request_id = intval($_POST['id']);
        $check_canceled = "SELECT tracking_status FROM request WHERE request_id = '$request_id'";
        $canceled_result = mysqli_query($con, $check_canceled);
        if ($canceled_result && mysqli_num_rows($canceled_result) > 0) {
            $canceled_row = mysqli_fetch_assoc($canceled_result);
            if (intval($canceled_row['tracking_status']) == 12) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Canceled orders cannot be deleted.']);
                ob_end_flush();
                exit;
            }
        }
    }
    
    $result = deleteDataTables($_POST);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Item deleted successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to delete item. The order may be canceled.']);
    }
    ob_end_flush();
    exit;
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'permanantDeleteData') {
    $result = permanantDeleteDataTable($_POST);
    header('Content-Type: application/json');
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Item permanently deleted']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Failed to delete item']);
    }
    exit;
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'changesettings') {
    changePageSettings($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'SettingImage') {

    $img = $_FILES['file']['name'];
    $target_dir = "uploads/settings/";
    $target_file = $target_dir . basename($img);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $extensions_arr = array("jpg", "jpeg", "png", "gif", "jfif", "svg", "webp");

    if (in_array($imageFileType, $extensions_arr)) {
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $img);
        editSettingImage($_POST, $img);
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'login') {
    echo getLoginAdmin($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'checkPasswordByEmail') {
    checkPasswordByName($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'editQty') {
    editQtyinCart($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addcontact') {
    addMessage($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addCustomer') {
    createCustomer($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'checkEmail') {
    checkUserEmail($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'checkPassword') {
    checkuserPassword($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updatePassword') {
    updatePassword($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addEmployee') {
    addEmployee($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addBranch') {
    addBranch($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addPrice') {
    addPrice($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'checkArea') {
    checkArea($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addArea') {
    addArea($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addCustomStatus') {
    $name_en = isset($_POST['name_en']) ? trim($_POST['name_en']) : '';
    $name_ar = isset($_POST['name_ar']) ? trim($_POST['name_ar']) : '';
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : 'fa-circle';
    
    if (empty($name_en) || empty($name_ar)) {
        echo json_encode(['success' => false, 'error' => 'Status name is required in both languages']);
        exit;
    }
    
    $status_id = addCustomStatus($name_en, $name_ar, $icon);
    if ($status_id) {
        echo json_encode(['success' => true, 'status_id' => $status_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add custom status']);
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'addRequest') {
    $request_id = addRequest($_POST);
    if ($request_id) {
        echo json_encode(array('success' => true, 'request_id' => $request_id));
    } else {
        echo json_encode(array('success' => false));
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'createPayPalOrder') {
    // Hardcoded PayPal credentials for testing
    $client_id = 'ATF3NgqnXDgojMU7vjwdjYMENojiNMUdKDJb2npC8J6H0QThG8yfNUJUx8QTz9ILnf-7f57ys82pQssS';
    $client_secret = 'EAlVy0TnJ3TcWYvMKZSxw_NyiwmLVKONMGuflnXP_g7z3JSaPNngyxShnxdRSwn8AamJ_pHGKLHAEpN9';
    $mode = 'sandbox';
    
    $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    
    if ($request_id > 0) {
        // Get request details
        $request_result = getRequestById($request_id);
        $request = mysqli_fetch_assoc($request_result);
        
        // Allow payment for:
        // 1. PayPal orders with pending status
        // 2. Any order with failed status (to allow switching to PayPal)
        $allow_payment = false;
        if ($request['payment_status'] == 'failed') {
            // Allow payment for any failed payment, regardless of original method
            $allow_payment = true;
        } elseif ($request['payment_method'] == 'paypal' && $request['payment_status'] == 'pending') {
            // Allow payment for pending PayPal orders
            $allow_payment = true;
        }
        
        if ($request && $allow_payment) {
            $amount = floatval($request['total_fee']);
            
            // Get access token
            $access_token = getPayPalAccessToken($client_id, $client_secret, $mode);
            
            if ($access_token) {
                // Create order
                $order = createPayPalOrder($access_token, $amount, 'USD', $mode);
                
                if ($order && isset($order['id'])) {
                    echo json_encode(array('success' => true, 'orderID' => $order['id']));
                } else {
                    echo json_encode(array('success' => false, 'error' => 'Failed to create PayPal order'));
                }
            } else {
                echo json_encode(array('success' => false, 'error' => 'Failed to get PayPal access token'));
            }
        } else {
            echo json_encode(array('success' => false, 'error' => 'Invalid request or already paid'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Invalid request ID'));
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'capturePayPalOrder') {
    // Hardcoded PayPal credentials for testing
    $client_id = 'ATF3NgqnXDgojMU7vjwdjYMENojiNMUdKDJb2npC8J6H0QThG8yfNUJUx8QTz9ILnf-7f57ys82pQssS';
    $client_secret = 'EAlVy0TnJ3TcWYvMKZSxw_NyiwmLVKONMGuflnXP_g7z3JSaPNngyxShnxdRSwn8AamJ_pHGKLHAEpN9';
    $mode = 'sandbox';
    
    $order_id = isset($_POST['orderID']) ? $_POST['orderID'] : '';
    $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    
    if ($order_id && $request_id > 0) {
        // Get access token
        $access_token = getPayPalAccessToken($client_id, $client_secret, $mode);
        
        if ($access_token) {
            // Capture order
            $capture = capturePayPalOrder($access_token, $order_id, $mode);
            
            if ($capture && isset($capture['status']) && $capture['status'] == 'COMPLETED') {
                // Get transaction ID
                $transaction_id = '';
                if (isset($capture['purchase_units'][0]['payments']['captures'][0]['id'])) {
                    $transaction_id = $capture['purchase_units'][0]['payments']['captures'][0]['id'];
                }
                
                // Update payment status
                if (updatePaymentStatus($request_id, 'paid', $transaction_id)) {
                    echo json_encode(array('success' => true, 'transaction_id' => $transaction_id));
                } else {
                    echo json_encode(array('success' => false, 'error' => 'Failed to update payment status'));
                }
            } else {
                // Mark as failed
                updatePaymentStatus($request_id, 'failed');
                echo json_encode(array('success' => false, 'error' => 'Payment capture failed'));
            }
        } else {
            echo json_encode(array('success' => false, 'error' => 'Failed to get PayPal access token'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Invalid order ID or request ID'));
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'saveOrderRating') {
    header('Content-Type: application/json');
    
    if (isset($_POST['request_id']) && isset($_POST['rating'])) {
        $data = array(
            'request_id' => $_POST['request_id'],
            'rating' => $_POST['rating'],
            'rating_comment' => isset($_POST['rating_comment']) ? $_POST['rating_comment'] : null
        );
        
        // Add customer_id if user is logged in
        if (isset($_SESSION['auth']) && isset($_SESSION['customer_id'])) {
            $data['customer_id'] = $_SESSION['customer_id'];
        }
        
        echo saveOrderRating($data);
    } else {
        echo json_encode(array('success' => false, 'error' => 'Missing required parameters'));
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'requestCancellation') {
    header('Content-Type: application/json');
    
    if (isset($_POST['request_id']) && isset($_POST['cancellation_reason'])) {
        if (!isset($_SESSION['auth']) || !isset($_SESSION['customer_id'])) {
            echo json_encode(array('success' => false, 'error' => 'Authentication required'));
            exit;
        }
        
        // Check if order is already canceled
        include 'inc/connection.php';
        $request_id = intval($_POST['request_id']);
        $check_order = "SELECT tracking_status FROM request WHERE request_id = '$request_id'";
        $order_result = mysqli_query($con, $check_order);
        if ($order_result && mysqli_num_rows($order_result) > 0) {
            $order_row = mysqli_fetch_assoc($order_result);
            if (intval($order_row['tracking_status']) == 12) {
                echo json_encode(array('success' => false, 'error' => 'This order is already canceled and cannot be modified.'));
                exit;
            }
        }
        
        $data = array(
            'request_id' => $_POST['request_id'],
            'customer_id' => $_SESSION['customer_id'],
            'cancellation_reason' => $_POST['cancellation_reason']
        );
        
        $cancellation_id = addCancellationRequest($data);
        
        if ($cancellation_id) {
            echo json_encode(array('success' => true, 'cancellation_id' => $cancellation_id, 'message' => 'Cancellation request submitted successfully'));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Failed to submit cancellation request. A pending request may already exist.'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Missing required parameters'));
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updateCancellationStatus') {
    ob_clean(); // Clear any output before JSON
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin'])) {
        echo json_encode(array('success' => false, 'error' => 'Admin authentication required'));
        ob_end_flush();
        exit;
    }
    
    if (isset($_POST['cancellation_id']) && isset($_POST['status'])) {
        $data = array(
            'cancellation_id' => $_POST['cancellation_id'],
            'status' => $_POST['status'],
            'admin_response_comment' => isset($_POST['admin_response_comment']) ? $_POST['admin_response_comment'] : null
        );
        
        $result = updateCancellationRequestStatus($data);
        echo $result;
        ob_end_flush();
        exit;
    } else {
        echo json_encode(array('success' => false, 'error' => 'Missing required parameters'));
        ob_end_flush();
        exit;
    }
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'updateCustomerPayPalAccount') {
    ob_clean();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['auth']) || !isset($_SESSION['customer_id'])) {
        echo json_encode(array('success' => false, 'error' => 'Authentication required'));
        ob_end_flush();
        exit;
    }
    
    if (isset($_POST['cancellation_id']) && isset($_POST['paypal_account'])) {
        $cancellation_id = intval($_POST['cancellation_id']);
        $paypal_account = trim($_POST['paypal_account']);
        
        // Validate PayPal account (basic email validation)
        if (!filter_var($paypal_account, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('success' => false, 'error' => 'Please enter a valid PayPal email address'));
            ob_end_flush();
            exit;
        }
        
        // Verify cancellation request belongs to customer
        $check_sql = "SELECT cr.* FROM cancellation_requests cr 
                     JOIN request r ON r.request_id = cr.request_id 
                     WHERE cr.cancellation_id = '$cancellation_id' 
                     AND r.customer_id = '" . intval($_SESSION['customer_id']) . "'";
        include 'inc/connection.php';
        $check_result = mysqli_query($con, $check_sql);
        
        if (mysqli_num_rows($check_result) == 0) {
            echo json_encode(array('success' => false, 'error' => 'Cancellation request not found or access denied'));
            ob_end_flush();
            exit;
        }
        
        $result = updateCustomerPayPalAccount($cancellation_id, $paypal_account);
        
        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'PayPal account updated successfully'));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Failed to update PayPal account'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Missing required parameters'));
    }
    ob_end_flush();
    exit;
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'markRefundCompleted') {
    ob_clean();
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['admin'])) {
        echo json_encode(array('success' => false, 'error' => 'Admin authentication required'));
        ob_end_flush();
        exit;
    }
    
    if (isset($_POST['cancellation_id']) && isset($_POST['refund_transaction_id'])) {
        $cancellation_id = intval($_POST['cancellation_id']);
        $refund_transaction_id = trim($_POST['refund_transaction_id']);
        
        if (empty($refund_transaction_id)) {
            echo json_encode(array('success' => false, 'error' => 'Transaction ID is required'));
            ob_end_flush();
            exit;
        }
        
        $result = markRefundAsCompleted($cancellation_id, $refund_transaction_id);
        
        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Refund marked as completed successfully'));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Failed to update refund status'));
        }
    } else {
        echo json_encode(array('success' => false, 'error' => 'Missing required parameters'));
    }
    ob_end_flush();
    exit;
}
