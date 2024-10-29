<?php 

function validateProduct($conn, $sku, $title, $price, $type) {
    $errors = [];

    if (!empty($sku) && $type === 'add') {
        $sql_check_sku = "SELECT * FROM products WHERE sku = '$sku'";
        $result_check_sku = mysqli_query($conn, $sql_check_sku);
        if (mysqli_num_rows($result_check_sku) > 0) {
            $errors[] = "SKU already exists.";
        }
    }

    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    if (empty($price)) {
        $errors[] = "Price is required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a valid positive number.";
    }

    return $errors; 
}


?>