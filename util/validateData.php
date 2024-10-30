<?php 

function validateProduct($conn, $sku, $title, $price, $type) {
    $errors = [];

    if (!empty($sku) && $type === 'add') {
        $sql_check_sku = "SELECT * FROM products WHERE sku = '$sku'";
        $result_check_sku = mysqli_query($conn, $sql_check_sku);
        if (mysqli_num_rows($result_check_sku) > 0) {
            $errors[] = "SKU này đã tồn tại";
        }
    } 

    if (empty($title)) {
        $errors[] = "Vui lòng nhập tên sản phẩm";
    }

    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Giá sản phẩm không hợp lí";
    }

    return $errors; 
}


?>