<?php

// Prevent multiple includes
if (!function_exists('getAllBranch')) {

function getAllBranch()
{
    include 'connection.php';

    $viewcat = "SELECT * FROM branch WHERE is_deleted = 0";
    return mysqli_query($con, $viewcat);
}
function getAllArea()
{
    include 'connection.php';

    $viewcat = "SELECT * FROM area WHERE is_deleted = 0";
    return mysqli_query($con, $viewcat);
}
function getAllAreabyID($area_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM area WHERE is_deleted = 0 AND area_id = '$area_id'";
    return mysqli_query($con, $viewcat);
}
function getAllPrice()
{
    include 'connection.php';

    $viewcat = "SELECT * FROM price_table WHERE is_deleted = 0";
    return mysqli_query($con, $viewcat);
}

function checkPrice($start_area, $end_area)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM price_table WHERE is_deleted = 0 AND start_area = '$start_area' AND end_area = '$end_area'";
    return mysqli_num_rows(mysqli_query($con, $viewcat));
}

function getBille($customer_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM request join customer on customer.customer_id = request.customer_id WHERE request.customer_id = '$customer_id' ";
    return mysqli_query($con, $q1);
}

//product

function getAllemployee()
{
    include 'connection.php';

    $q1 = "SELECT * FROM employee WHERE is_deleted = 0 AND email != 'admin'";
    return mysqli_query($con, $q1);
}

function getemployeeByID($emp_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM employee WHERE is_deleted = 0 AND emp_id = '$emp_id'";
    return mysqli_query($con, $q1);
}

function getemployeeByEmail($email)
{
    include 'connection.php';

    $q1 = "SELECT * FROM employee WHERE is_deleted = 0 AND email = '$email'";
    return mysqli_query($con, $q1);
}

function getBranchByID($branch_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM branch WHERE is_deleted = 0 AND branch_id = '$branch_id'";
    return mysqli_query($con, $q1);
}

function getAllTrackingByCUS($customer_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM request WHERE is_deleted = 0 AND customer_id = '$customer_id' ORDER BY date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function getAllTracking()
{
    include 'connection.php';

    $viewcat = "SELECT request.*, customer.name, customer.email, customer.phone 
                FROM request 
                LEFT JOIN customer ON customer.customer_id = request.customer_id AND customer.is_deleted = 0 
                WHERE request.is_deleted = 0 
                ORDER BY request.date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function getStatusHistory($request_id)
{
    include 'connection.php';
    
    $request_id = mysqli_real_escape_string($con, $request_id);
    $query = "SELECT * FROM request_status_history WHERE request_id = '$request_id' ORDER BY status_date ASC";
    return mysqli_query($con, $query);
}

function getStatusTimestamp($request_id, $status)
{
    include 'connection.php';
    
    $request_id = mysqli_real_escape_string($con, $request_id);
    $status = mysqli_real_escape_string($con, $status);
    $query = "SELECT status_date FROM request_status_history WHERE request_id = '$request_id' AND status = '$status' ORDER BY status_date ASC LIMIT 1";
    $result = mysqli_query($con, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['status_date'];
    }
    return null;
}

function getAllCustomStatuses()
{
    include 'connection.php';
    
    $query = "SELECT * FROM custom_statuses WHERE is_active = 1 ORDER BY status_order ASC, status_id ASC";
    return mysqli_query($con, $query);
}

function getCustomStatusById($status_id)
{
    include 'connection.php';
    
    $status_id = mysqli_real_escape_string($con, $status_id);
    $query = "SELECT * FROM custom_statuses WHERE status_id = '$status_id' AND is_active = 1 LIMIT 1";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result);
}

function addCustomStatus($name_en, $name_ar, $icon = 'fa-circle')
{
    include 'connection.php';
    
    $name_en = mysqli_real_escape_string($con, $name_en);
    $name_ar = mysqli_real_escape_string($con, $name_ar);
    $icon = mysqli_real_escape_string($con, $icon);
    
    // Get max order
    $max_order_query = "SELECT MAX(status_order) as max_order FROM custom_statuses";
    $max_result = mysqli_query($con, $max_order_query);
    $max_row = mysqli_fetch_assoc($max_result);
    $new_order = ($max_row['max_order'] ?? 0) + 1;
    
    $query = "INSERT INTO custom_statuses (status_name_en, status_name_ar, status_icon, status_order) 
              VALUES ('$name_en', '$name_ar', '$icon', '$new_order')";
    
    if (mysqli_query($con, $query)) {
        return mysqli_insert_id($con);
    }
    return false;
}

function getNextValidStatuses($current_status)
{
    // Define status flow - admin can only move forward step by step
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
    
    $next_statuses = isset($status_flow[$current_status]) ? $status_flow[$current_status] : [];
    
    // Add all custom statuses (ID >= 100) as valid next steps from any status
    // This allows flexibility for custom workflows
    $custom_statuses_result = getAllCustomStatuses();
    $custom_start_id = 100;
    while ($custom_row = mysqli_fetch_assoc($custom_statuses_result)) {
        $custom_id = $custom_start_id + $custom_row['status_id'];
        if (!in_array($custom_id, $next_statuses)) {
            $next_statuses[] = $custom_id;
        }
    }
    
    return $next_statuses;
}

function canChangeToStatus($current_status, $target_status)
{
    $next_statuses = getNextValidStatuses($current_status);
    return in_array($target_status, $next_statuses);
}

function getRequestById($request_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM request WHERE request_id = '$request_id' AND is_deleted = 0";
    return mysqli_query($con, $viewcat);
}

function getAllPayments()
{
    include 'connection.php';

    $viewcat = "SELECT r.*, c.name as customer_name, c.email as customer_email FROM request r JOIN customer c ON c.customer_id = r.customer_id WHERE r.is_deleted = 0 ORDER BY r.date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function checkemployeetByEmail($email)
{
    include 'connection.php';

    $employee = "SELECT * FROM employee WHERE email = '$email' AND is_deleted = 0";
    $result = mysqli_query($con, $employee);

    $customer = "SELECT * FROM customer WHERE email = '$email' AND is_deleted = 0";
    $cus_res = mysqli_query($con, $customer);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_num_rows($result);
    } else if (mysqli_num_rows($cus_res) > 0) {
        return mysqli_num_rows($cus_res);
    } else {
        return 0;
    }
}

function getAllgalleryImages()
{
    include 'connection.php';

    $q1 = "SELECT * FROM gallery";
    return mysqli_query($con, $q1);
}

//customer


function checkuserPassword($data)
{
    include 'connection.php';
    $customer_id = $data['customer_id'];
    $password = $data['password'];

    // Get customer with hashed password
    $viewcat = "SELECT * FROM customer WHERE is_deleted = 0 AND customer_id = '$customer_id' ";
    $result = mysqli_query($con, $viewcat);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify password using password_verify for hashed passwords
        if (password_verify($password, $user['password'])) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
}

function checkArea($data)
{
    include 'connection.php';

    $start_area = $data['send_location'];
    $end_area = $data['end_location'];

    $viewcat = "SELECT * FROM price_table WHERE is_deleted = 0 AND start_area = '$start_area' AND end_area = '$end_area' ";
    $result = mysqli_query($con, $viewcat);
    $row = mysqli_fetch_assoc($result);
    echo $row['price'];
}

function checkAreaByName($area_name)
{
    include 'connection.php';

    $q1 = "SELECT * FROM area WHERE area_name = '$area_name' AND is_deleted = 0";
    $res =  mysqli_query($con, $q1);
    return mysqli_num_rows($res);
}

function checkUserEmail($data)
{
    include 'connection.php';

    $customer_id = $data['customer_id'];
    $email = $data['email'];

    $viewcat = "SELECT * FROM customer WHERE is_deleted = 0 AND email = '$email' AND customer_id = '$customer_id' ";
    $result = mysqli_query($con, $viewcat);
    $count = mysqli_num_rows($result);
    echo $count;
}

function getAllcustomerById($customer_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM customer WHERE is_deleted = '0' AND customer_id = '$customer_id'";
    return mysqli_query($con, $q1);
}

function getAllcustomers()
{
    include 'connection.php';

    $q1 = "SELECT * FROM customer WHERE is_deleted = 0 AND email != 'admin'";
    return mysqli_query($con, $q1);
}

function getLoginAdmin($data)
{
    if (session_id() == '') {
        session_start();
    }
    include 'connection.php';

    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';

    if (empty($email) || empty($password)) {
        echo "";
        return;
    }

    $loginAdmin = "SELECT * FROM employee WHERE email = '$email' AND password = '$password' AND is_deleted = '0'";
    $countloginAdmin = mysqli_query($con, $loginAdmin);
    
    if (!$countloginAdmin) {
        echo "";
        return;
    }
    
    $counts_loginAdmin = mysqli_num_rows($countloginAdmin);

    $loginCustomer = "SELECT * FROM customer WHERE email = '$email' AND password = '$password' AND is_deleted = '0'";
    $count_loginCustomer = mysqli_query($con, $loginCustomer);
    
    if (!$count_loginCustomer) {
        echo "";
        return;
    }
    
    $counts_loginCustomer = mysqli_num_rows($count_loginCustomer);

    $value = "";

    if ($counts_loginAdmin > 0) {
        $value = 'admin';
        $res = checkemployee($email);
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ($row) {
                $_SESSION['admin'] = $row['email'];
            }
        }
    } else if ($counts_loginCustomer > 0) {
        $value = 'customer';
        $res = checkCustomerByEmail($email);
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            if ($row) {
                $_SESSION['customer'] = $row['customer_id'];
            }
        }
    }
    echo $value;
}

function checkemployee($email)
{
    include 'connection.php';

    $q1 = "SELECT * FROM employee WHERE email='$email' AND is_deleted='0'";
    return mysqli_query($con, $q1);
}

function checkCustomerByEmail($email)
{
    include 'connection.php';

    $q1 = "SELECT * FROM customer WHERE email='$email' AND is_deleted='0'";
    return mysqli_query($con, $q1);
}


function checkCustomerByID($customer_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM customer WHERE customer_id='$customer_id' AND is_deleted = '0'";
    return mysqli_query($con, $q1);
}

function getAllCustomer()
{
    include 'connection.php';

    $q1 = "SELECT * FROM customer WHERE is_deleted = '0' AND email != 'admin'";
    $table = mysqli_query($con, $q1);
    $columns = mysqli_fetch_all($table, MYSQLI_ASSOC);

    return $columns;
}


//contact

function getAllMessages()
{
    include 'connection.php';

    $messages = "SELECT * FROM contact";
    return mysqli_query($con, $messages);
}

//count

function dataCount($table)
{
    include 'connection.php';

    $counts = "SELECT * FROM $table WHERE is_deleted = 0";
    $res =  mysqli_query($con, $counts);
    $count =  mysqli_num_rows($res);
    echo $count;
}

function dataCountWhere($table, $where)
{
    include 'connection.php';

    $counts = "SELECT * FROM $table WHERE $where AND is_deleted = 0";
    $res =  mysqli_query($con, $counts);
    $count =  mysqli_num_rows($res);
    echo $count;
}

function dataforCount($table)
{
    include 'connection.php';

    $counts = "SELECT sum(total) as sum FROM $table WHERE is_deleted = 0";
    return mysqli_query($con, $counts);
}

function dataforCountToday($table)
{
    include 'connection.php';

    $counts = "SELECT sum(total) as sum FROM $table WHERE month(now()) = month(date_updated) AND is_deleted = 0s";
    return mysqli_query($con, $counts);
}


//settings

function getAllSettings()
{
    include 'connection.php';

    $settings = "SELECT * FROM settings";
    return mysqli_query($con, $settings);
}

function checkPasswordByName($data)
{
    include 'connection.php';
    $email = $data['email'];
    $password = $data['password'];

    $viewcat = "SELECT * FROM employee WHERE password = '$password' AND email = '$email' ";
    $result = mysqli_query($con, $viewcat);
    $count = mysqli_num_rows($result);
    echo $count;
}

function getAllCart($customer_id)
{
    include 'connection.php';

    $q1 = "SELECT * FROM cart join products on products.pid = cart.pid join customer on customer.customer_id = cart.customer_id WHERE cart.customer_id = '$customer_id'";
    return mysqli_query($con, $q1);
}


function getAllOrdersByCustomer($customer_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM product_orders WHERE customer_id = '$customer_id' AND is_deleted = '0' ORDER BY date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function getAllOrderItemsBYOrder($order_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM order_items join products on order_items.pid = products.pid WHERE order_items.order_id = '$order_id'";
    return mysqli_query($con, $viewcat);
}

function getAllOrders()
{
    include 'connection.php';

    $viewcat = "SELECT * FROM product_orders join customer on customer.customer_id = product_orders.customer_id  WHERE product_orders.is_deleted = '0' ORDER BY date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function getAllOrdersPending()
{
    include 'connection.php';

    $viewcat = "SELECT * FROM product_orders join customer on customer.customer_id = product_orders.customer_id  WHERE product_orders.is_deleted = '0' AND product_orders.order_status = '1' ORDER BY date_updated DESC";
    return mysqli_query($con, $viewcat);
}

function getAllOrderItems($order_id)
{
    include 'connection.php';

    $viewcat = "SELECT * FROM order_items join products on order_items.pid = products.pid WHERE order_items.order_id = '$order_id'";
    return mysqli_query($con, $viewcat);
}

function getCancellationRequestByRequestId($request_id)
{
    include 'connection.php';
    
    $q1 = "SELECT * FROM cancellation_requests WHERE request_id = '$request_id' ORDER BY requested_date DESC LIMIT 1";
    return mysqli_query($con, $q1);
}

function getAllPendingCancellationRequests()
{
    include 'connection.php';
    
    $q1 = "SELECT cr.*, r.tracking_code, r.request_id, c.name as customer_name, c.email as customer_email, c.phone as customer_phone
           FROM cancellation_requests cr
           JOIN request r ON r.request_id = cr.request_id
           JOIN customer c ON c.customer_id = cr.customer_id
           WHERE cr.cancellation_status = 'pending'
           ORDER BY cr.requested_date DESC";
    return mysqli_query($con, $q1);
}

function getCancellationRequestById($cancellation_id)
{
    include 'connection.php';
    
    $q1 = "SELECT cr.*, r.tracking_code, c.name as customer_name, c.email as customer_email
           FROM cancellation_requests cr
           JOIN request r ON r.request_id = cr.request_id
           JOIN customer c ON c.customer_id = cr.customer_id
           WHERE cr.cancellation_id = '$cancellation_id'";
    return mysqli_query($con, $q1);
}

} // End of function_exists check
