<?php 

function validateProduct($conn, $sku, $title, $price, $featured_image, $type) {
    // Mảng chứa lỗi
    $errors = [];

    // Validate SKU
    if (!empty($sku) && $type === 'add') {
        $sql_check_sku = "SELECT * FROM products WHERE sku = '$sku'";
        $result_check_sku = mysqli_query($conn, $sql_check_sku);
        if (mysqli_num_rows($result_check_sku) > 0) {
            $errors[] = "SKU already exists.";
        }
    }

    // Validate title
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    // Validate price
    if (empty($price)) {
        $errors[] = "Price is required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a valid positive number.";
    }

    // Validate featured_image
    if (empty($featured_image)) {
        $errors[] = "Featured image is required.";
    } elseif (!filter_var($featured_image, FILTER_VALIDATE_URL)) {
        $errors[] = "Featured image must be a valid URL.";
    }



    return $errors; // Trả về mảng chứa lỗi
}


?>