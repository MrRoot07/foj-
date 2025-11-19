<?php
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
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'deleteData') {
    deleteDataTables($_POST);
} else if (isset($_GET['function_code']) && $_GET['function_code'] == 'permanantDeleteData') {
    permanantDeleteDataTable($_POST);
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
        
        if ($request && $request['payment_method'] == 'paypal' && $request['payment_status'] == 'pending') {
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
}
