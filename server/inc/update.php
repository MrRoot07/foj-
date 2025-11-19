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

    $sql = "UPDATE $table SET $field = '$value' where $id_fild = '$id'";
    return mysqli_query($con, $sql);
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