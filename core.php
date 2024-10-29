<?php

$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");

include './util/validateData.php';
include './util/handleFeatureUpload.php';
include './util/handleGalleryUploads.php';
include './util/handleCategoriesAndTags.php';

$typeValidate = ['add', 'edit'];


//get tag and category 
if (isset($_GET['get_tag-category'])) {
    $sql_categories = "SELECT id, name, COUNT(*) as total FROM categories GROUP BY id, name";
    $result_categories = $conn->query($sql_categories);
    $categories_list = [];
    if ($result_categories->num_rows > 0) {
        while ($row = $result_categories->fetch_assoc()) {
            $categories_list[] = $row;
        }
    }

    $sql_tags = "SELECT id, name, COUNT(*) as total FROM tags GROUP BY id, name";
    $result_tags = $conn->query($sql_tags);
    $tags_list = [];
    if ($result_tags->num_rows > 0) {
        while ($row = $result_tags->fetch_assoc()) {
            $tags_list[] = $row;
        }
    }

    echo json_encode(['status' => 'success', 'categories' => $categories_list, 'tags' => $tags_list]);
};


//get view prodcut
if (isset($_GET['view-product'])) {
    
    $sql = "SELECT 
                p.id,
                p.sku,
                p.title,
                p.price,
                p.featured_image,
                p.created_date,
                GROUP_CONCAT(DISTINCT pg.image) AS gallery_images,
                GROUP_CONCAT(DISTINCT c.name) AS category_names,
                GROUP_CONCAT(DISTINCT t.name) AS tag_names
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            LEFT JOIN product_tags pt ON p.id = pt.product_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            GROUP BY p.id
            ORDER BY p.created_date DESC";

    $result = $conn->query($sql);


    if ($result === false) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }


    echo json_encode([
        'products' => $products
    ]);
};



// filter product
if (isset($_GET['filter-product'])) {

    $sortBy = $_GET['sort_by'] ?? '';
    $sortOrder = strtoupper($_GET['sort_order'] ?? 'ASC') === 'DESC' ? 'DESC' : 'ASC';
    $categories = $_GET['categories'] ?? [];
    $tags = $_GET['tags'] ?? [];
    $dateFrom = $_GET['date_from'] ?? '';
    $dateTo = $_GET['date_to'] ?? '';
    $priceFrom = $_GET['price_from'] ?? '';
    $priceTo = $_GET['price_to'] ?? '';

    $sql = "SELECT 
                p.id,
                p.sku,
                p.title,
                p.price,
                p.featured_image,
                p.created_date,
                GROUP_CONCAT(DISTINCT pg.image) AS gallery_images,
                GROUP_CONCAT(DISTINCT c.name) AS category_names,
                GROUP_CONCAT(DISTINCT t.name) AS tag_names
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            LEFT JOIN product_tags pt ON p.id = pt.product_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            WHERE 1=1";

    if (!empty($categories) && is_array($categories)) {
        $categories = array_map('intval', $categories);
        $categoriesList = implode(",", $categories);
        $sql .= " AND p.id IN (
            SELECT pc.product_id FROM product_categories pc 
            WHERE pc.category_id IN ($categoriesList)
            GROUP BY pc.product_id 
            HAVING COUNT(DISTINCT pc.category_id) = " . count($categories) . ")";
    }

    if (!empty($tags) && is_array($tags)) {
        $tags = array_map('intval', $tags);
        $tagsList = implode(",", $tags);
        $sql .= " AND p.id IN (
            SELECT pt.product_id FROM product_tags pt 
            WHERE pt.tag_id IN ($tagsList)
            GROUP BY pt.product_id 
            HAVING COUNT(DISTINCT pt.tag_id) = " . count($tags) . ")";
    }

    if (!empty($dateFrom)) {
        $dateFrom = $conn->real_escape_string($dateFrom);
        $sql .= " AND p.created_date >= '$dateFrom'";
    }

    if (!empty($dateTo)) {
        $dateTo = $conn->real_escape_string($dateTo . " 23:59:59");
        $sql .= " AND p.created_date <= '$dateTo'";
    }

    if (!empty($priceFrom)) {
        $priceFrom = (float)$priceFrom;
        $sql .= " AND p.price >= $priceFrom";
    }

    if (!empty($priceTo)) {
        $priceTo = (float)$priceTo;
        $sql .= " AND p.price <= $priceTo";
    }

    $allowedSortColumns = ['p.price', 'p.created_date', 'p.title'];
    $sortBy = in_array("p.$sortBy", $allowedSortColumns) ? "p.$sortBy" : 'p.price';

    $sql .= " GROUP BY p.id ORDER BY $sortBy $sortOrder;";

    $result = $conn->query($sql);

    if ($result === false) {
        echo json_encode(['error' => 'Query failed: ' . $conn->error]);
        exit;
    }

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        'products' => $products,
    ]);
}


// search product
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    $sql = "SELECT  p.id,
                    p.sku,
                    p.title,
                    p.price,
                    p.featured_image,
                    GROUP_CONCAT(DISTINCT pg.image) AS gallery_images,
                    GROUP_CONCAT(DISTINCT c.name) AS category_names,
                    GROUP_CONCAT(DISTINCT t.name) AS tag_names,
                    p.created_date
                FROM products p
                LEFT JOIN product_gallery pg ON p.id = pg.product_id
                LEFT JOIN product_categories pc ON p.id = pc.product_id
                LEFT JOIN categories c ON pc.category_id = c.id
                LEFT JOIN product_tags pt ON p.id = pt.product_id
                LEFT JOIN tags t ON pt.tag_id = t.id
                WHERE p.title LIKE '%$searchQuery%' 
                GROUP BY p.id
                ORDER BY p.created_date DESC
                LIMIT 5 OFFSET 0;
            ";

    $result = $conn->query($sql);

    $products = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    echo json_encode($products);
};


// add product
if (isset($_POST['action']) && $_POST['action'] === 'add-product') {

    $sku = trim($_POST["sku"]); 
    if (empty($sku)) {
        $sku = 'SKU-' . strtoupper(bin2hex(random_bytes(4))); 
    }

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $categories = isset($_POST["categories"]) ? $_POST["categories"] : [];
    $tags = isset($_POST["tags"]) ? $_POST["tags"] : [];
    $uploadedFile = null;

    $errors = validateProduct($conn, $sku, $title, $price, $typeValidate[0]);
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode(", ", $errors)]);
        exit;
    }

    if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = handleFeatureUpload($_FILES['featured_image_file']);
        if (!$uploadedFile) {
            echo json_encode(['status' => 'error']);
            exit;
        }
    }

    $sql_query = "INSERT INTO products (sku, title, price, featured_image) 
                    VALUES ('$sku', '$title', '$price', '$uploadedFile')";
    $result = mysqli_query($conn, $sql_query);
    $last_product_id = mysqli_insert_id($conn);

    if (!$last_product_id) {
        echo json_encode(['status' => 'error', 'message' => 'Add product failed.']);
        exit;
    }

    if (isset($_FILES['gallery_images_file'])) {
        handleGalleryUploads($_FILES['gallery_images_file'], $last_product_id);
    }

    handleCategoriesAndTags($categories, $tags, $last_product_id);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit;
}



// add property
if (isset($_POST['action']) && $_POST['action'] === 'add-property') {

    $categories_input = trim($_POST["categories"]); 
    $tags_input = trim($_POST["tags"]);

    if (empty($categories_input) && empty($tags_input)) {
        echo json_encode(['status' => 'error', 'message' => 'Add property failed.']);
        exit;
    }

    $conn->begin_transaction();

    try {
        $categories = explode(",", $categories_input);
        $tags = explode(",", $tags_input);

        foreach ($categories as $category) {
            $category = trim($category);
            if (!empty($category)) {
                $sql_category = "INSERT INTO categories (name) VALUES ('$category')";
                $conn->query($sql_category);
            }
        }

        foreach ($tags as $tag) {
            $tag = trim($tag); 
            if (!empty($tag)) {
                $sql_tag = "INSERT INTO tags (name) VALUES ('$tag')";
                $conn->query($sql_tag);
            }
        }

        $conn->commit();  
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error']);
    }

    exit();
}



//get data to form update product
if (isset($_POST['click-edit-btn'])) {
    $id = $conn->real_escape_string($_POST['id']);  
    
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

    $fetch_query = $conn->query($sql_query);

    if ($fetch_query->num_rows > 0) {
        $row = $fetch_query->fetch_assoc();

        $row['category_ids'] = explode(',', $row['category_ids']);
        $row['tag_ids'] = explode(',', $row['tag_ids']);
        $row['gallery_images'] = explode(',', $row['gallery_images']);

        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        echo json_encode(null);
    }

    $conn->close();
    
    exit();
}



// update product
if (isset($_POST['action']) && $_POST['action'] === 'update-product') {
    $id = $_POST['id'];
    $sku = trim($_POST["sku"]);

    if (empty($sku)) {
        $sql_sku = "SELECT p.sku FROM products p WHERE id = '$id'";
        $result = mysqli_query($conn, $sql_sku);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $sku = $row['sku'];
        } 
    }

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $featured_image = isset($_POST["featured_image_file"]) ? $_POST["featured_image_file"] : [];
    $gallery_images = isset($_POST["gallery_images_file"]) ? $_POST["gallery_images_file"] : [];
    $categories = isset($_POST["categories"]) ? $_POST["categories"] : [];
    $tags = isset($_POST["tags"]) ? $_POST["tags"] : [];

    $errors = validateProduct($conn, $sku, $title, $price, $typeValidate[1]);

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode(", ", $errors)]); // Trả về lỗi
        exit;
    }

    if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] == UPLOAD_ERR_OK) {
        $featured_image_name = handleFeatureUpload($_FILES['featured_image_file']);
        if ($featured_image_name) {
            $sql_update_product = "UPDATE products SET featured_image = '$featured_image_name' WHERE id = $id";
            mysqli_query($conn, $sql_update_product);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi tải lên hình ảnh đặc trưng.']);
            exit;
        }
    }

    if (isset($_FILES['gallery_images_file-edit']) && count($_FILES['gallery_images_file-edit']['name']) > 0) {
        $hasNewFiles = false;
        foreach ($_FILES['gallery_images_file-edit']['name'] as $key => $gallery_image_name) {
            if (!empty($gallery_image_name) && $_FILES['gallery_images_file-edit']['error'][$key] === UPLOAD_ERR_OK) {
                $hasNewFiles = true;
                break;
            }
        }

        if ($hasNewFiles) {
            $sql_delete_gallery = "DELETE FROM product_gallery WHERE product_id = $id";
            mysqli_query($conn, $sql_delete_gallery);
            handleGalleryUploads($_FILES['gallery_images_file-edit'], $id);
        }
    }

    $sql_update_product = "UPDATE products SET sku = '$sku', title = '$title', price = '$price' WHERE id = $id";
    if (!mysqli_query($conn, $sql_update_product)) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật thông tin sản phẩm.']);
        exit;
    }

    $sql_delete_categories = "DELETE FROM product_categories WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_categories);

    $sql_delete_tags = "DELETE FROM product_tags WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_tags);

    if (empty($categories) && empty($tags)) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật thành công mà không có danh mục hay thẻ.']);
        exit;
    }

    handleCategoriesAndTags($categories, $tags, $id);

    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Không có thay đổi nào được thực hiện.']);
    }

    exit;
}



//delete one product
if (isset($_POST['click-delete-one'])) {
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
            echo json_encode(['success' => true]);
        } else {
            mysqli_rollback($conn);
            echo json_encode(['success' => false]);
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['success' => false]);
    }

    exit();
};


//delete all
if (isset($_POST['click-delete-all'])) {
    
    $sql_query = "DELETE FROM products";
    $result = mysqli_query($conn, $sql_query);

    $sql_gallery = "DELETE FROM product_gallery";
    mysqli_query($conn, $sql_gallery);

    $sql_category = "DELETE FROM product_categories";
    mysqli_query($conn, $sql_category);

    $sql_tag = "DELETE FROM product_tags";
    mysqli_query($conn, $sql_tag);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit();
}

?>
