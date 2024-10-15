<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");

function validateData($sku, $title, $price, $featured_image, $gallery_images, $categories, $tags) {
    $errors = [];

    if (empty($sku)) {
        $errors[] = "SKU is required.";
    }
    
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    if (!is_numeric($price) || $price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    if (!filter_var($featured_image, FILTER_VALIDATE_URL)) {
        $errors[] = "Featured image must be a valid URL.";
    }

    if (!empty($gallery_images)) {
        $gallery_images_array = explode(",", $gallery_images);
        foreach ($gallery_images_array as $image) {
            if (!filter_var(trim($image), FILTER_VALIDATE_URL)) {
                $errors[] = "One or more gallery images are not valid URLs.";
            }
        }
    }

    if (!is_array($categories) || empty($categories)) {
        $errors[] = "At least one category must be selected.";
    }

    if (!is_array($tags) || empty($tags)) {
        $errors[] = "At least one tag must be selected.";
    }

    return $errors;
}


// add product
if (isset($_POST['add-product'])) {
    $sku = $_POST["sku"];
    $title = $_POST["title"];
    $price = $_POST["price"];
    $featured_image = $_POST["featured_image"];
    $gallery_images = $_POST["gallery_images"];
    $categories = $_POST["categories"];
    $tags = $_POST["tags"];
    
    $validationErrors = validateData($sku, $title, $price, $featured_image, $gallery_images, $categories, $tags);

    if (!empty($validationErrors)) {
        $_SESSION['errors'] = $validationErrors;
        exit;
    }

    $sql_query = "INSERT INTO products (sku, title, price, featured_image) 
                VALUES ('$sku', '$title', '$price', '$featured_image')";
    $result = mysqli_query($conn, $sql_query);

    $last_product_id = mysqli_insert_id($conn);
    if (!$last_product_id) {
        header("location: index.php");
        $_SESSION['status'] = "Add product faile";
        exit;
    }

    $gallery_images_array = explode(",", $gallery_images);
    foreach ($gallery_images_array as $image) {
        $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                            VALUES ('$last_product_id', '$image')";
        mysqli_query($conn, $sql_gallery);
    }

    foreach ($categories as $category_id) {
        $sql_category = "INSERT INTO product_categories (product_id, category_id) 
                            VALUES ('$last_product_id', '$category_id')";
        mysqli_query($conn, $sql_category);
    }

    foreach ($tags as $tag_id) {
        $sql_tag = "INSERT INTO product_tags (product_id, tag_id) 
                        VALUES ('$last_product_id', '$tag_id')";
        mysqli_query($conn, $sql_tag);
    }


    if ($result) {
        $_SESSION['status'] = "Add product successfuly";
    } else {
        $_SESSION['status'] = "Add product faile";
    }


    header("location: index.php");
    exit();
};

// add property
if (isset($_POST['add-property'])) {

    $categories_input = $_POST["categories"]; // Chuỗi, ví dụ: "a,b,c"
    $tags_input = $_POST["tags"]; // Chuỗi, ví dụ: "x,y,z"

    $conn->begin_transaction();

    try {
        $categories = explode(",", $categories_input);
        $tags = explode(",", $tags_input);

        foreach ($categories as $category) {
            if (!empty($categories)) {
                $sql_category = "INSERT INTO categories (name) VALUES ('$category')";
                $conn->query($sql_category);
            }
        }

        foreach ($tags as $tag) {
            if (!empty($tags)) {
                $sql_tag = "INSERT INTO tags (name) VALUES ('$tag')";
                $conn->query($sql_tag);
            }
        }

        $result = $conn->commit();
        if ($result) {
            $_SESSION['status'] = "Add property successfuly";
        } else {
            $_SESSION['status'] = "Add property faile";
        }
    } catch (Exception $e) {
        $conn->rollback();
    }

    header("location: index.php");
    exit();
}


//get data to form update product
if (isset($_POST['click-edit-btn'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']); // Tránh SQL injection
    $sql_query = "SELECT p.id, p.sku, p.title, p.price, p.featured_image, 
            GROUP_CONCAT(DISTINCT c.id) AS category_ids,
            GROUP_CONCAT(DISTINCT t.id) AS tag_ids,
            GROUP_CONCAT(DISTINCT pg.image) AS gallery_images
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            LEFT JOIN product_tags pt ON p.id = pt.product_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            WHERE p.id = $id
            GROUP BY p.id";

    $fetch_query = mysqli_query($conn, $sql_query);

    if (mysqli_num_rows($fetch_query) > 0) {
        $row = mysqli_fetch_assoc($fetch_query);

        // Tách chuỗi thành mảng
        $row['category_ids'] = explode(',', $row['category_ids']);
        $row['tag_ids'] = explode(',', $row['tag_ids']);
        $row['gallery_images'] = explode(',', $row['gallery_images']);

        // Trả về kết quả dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }
    exit();
};


//update product
if (isset($_POST['edit-product'])) {

    $id = $_POST['id'];
    $sku = $_POST["sku"];
    $title = $_POST["title"];
    $price = $_POST["price"];
    $featured_image = $_POST["featured_image"];
    $gallery_images = $_POST["gallery_images"];
    $categories = $_POST["categories"];
    $tags = $_POST["tags"];


    $sql_update_product = "UPDATE products SET sku = '$sku', title = '$title', price = '$price', featured_image = '$featured_image' WHERE id = $id";
    mysqli_query($conn, $sql_update_product);
    if (mysqli_error($conn)) {
        error_log("Error updating product: " . mysqli_error($conn));
    }


    $sql_delete_gallery = "DELETE FROM product_gallery WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_gallery);
    if (mysqli_error($conn)) {
        error_log("Error deleting gallery: " . mysqli_error($conn));
    }


    $sql_delete_categories = "DELETE FROM product_categories WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_categories);
    if (mysqli_error($conn)) {
        error_log("Error deleting categories: " . mysqli_error($conn));
    }


    $sql_delete_tags = "DELETE FROM product_tags WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_tags);
    if (mysqli_error($conn)) {
        error_log("Error deleting tags: " . mysqli_error($conn));
    }


    $gallery_images_array = explode(",", $gallery_images);
    foreach ($gallery_images_array as $image) {
        $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                        VALUES ('$id', '$image')";
        mysqli_query($conn, $sql_gallery);
        if (mysqli_error($conn)) {
            error_log("Error inserting gallery image: " . mysqli_error($conn));
        }
    }


    foreach ($categories as $category_id) {
        $sql_category = "INSERT INTO product_categories (product_id, category_id) 
                        VALUES ('$id', '$category_id')";
        mysqli_query($conn, $sql_category);
        if (mysqli_error($conn)) {
            error_log("Error inserting category: " . mysqli_error($conn));
        }
    }


    foreach ($tags as $tag_id) {
        $sql_tag = "INSERT INTO product_tags (product_id, tag_id) 
                    VALUES ('$id', '$tag_id')";
        mysqli_query($conn, $sql_tag);
        if (mysqli_error($conn)) {
            error_log("Error inserting tag: " . mysqli_error($conn));
        }
    }

    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['status'] = "Product updated successfully";
    } else {
        $_SESSION['status'] = "Failed to update product";
    }

    header("location: index.php");
    exit();
};


//delete one product
if (isset($_POST['click-delete-one-btn'])) {
    $id = $_POST['id'];

    mysqli_begin_transaction($conn);

    try {

        $delete_query = "DELETE FROM products WHERE id = $id";
        $result = mysqli_query($conn, $delete_query);


        $sql_delete_gallery = "DELETE FROM product_gallery WHERE product_id = $id";
        mysqli_query($conn, $sql_delete_gallery);
        if (mysqli_error($conn)) {
            error_log("Error deleting gallery: " . mysqli_error($conn));
        }


        $sql_delete_categories = "DELETE FROM product_categories WHERE product_id = $id";
        mysqli_query($conn, $sql_delete_categories);
        if (mysqli_error($conn)) {
            error_log("Error deleting categories: " . mysqli_error($conn));
        }


        $sql_delete_tags = "DELETE FROM product_tags WHERE product_id = $id";
        mysqli_query($conn, $sql_delete_tags);
        if (mysqli_error($conn)) {
            error_log("Error deleting tags: " . mysqli_error($conn));
        }


        if ($result) {
            mysqli_commit($conn);
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
        } else {
            mysqli_rollback($conn);
            echo json_encode(['success' => false, 'message' => 'Failed to delete product.']);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }

    exit();
};


//delete all
if (isset($_POST['delete-all'])) {
    $sql_query = "DELETE FROM products";

    $result = mysqli_query($conn, $sql_query);

    $sql_gallery = "DELETE FROM  product_gallery";
    mysqli_query($conn, $sql_gallery);

    $sql_category = "DELETE FROM  product_categories";
    mysqli_query($conn, $sql_category);

    $sql_tag = "DELETE FROM product_tags";
    mysqli_query($conn, $sql_tag);


    if ($result) {
        $_SESSION['status'] = "Delete successfuly";
    } else {
        $_SESSION['status'] = "Delete faile";
    }


    header("location: index.php");
    exit();
}
