<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");
include './util/validateData.php';



$typeValidate = ['add', 'edit'];


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



//filter product
if (isset($_GET['filter-product'])) {

    $sortBy = $_GET['sort_by'] ?? '';
    $sortOrder = $_GET['sort_order'] ?? 'ASC';
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
        $sql .= " AND p.id IN (SELECT pc.product_id FROM product_categories pc WHERE pc.category_id IN ($categoriesList) GROUP BY pc.product_id HAVING COUNT(DISTINCT pc.category_id) = " . count($categories) . ")";
    }

    if (!empty($tags) && is_array($tags)) {
        $tags = array_map('intval', $tags); 
        $tagsList = implode(",", $tags);    
        $sql .= " AND p.id IN (SELECT pt.product_id FROM product_tags pt WHERE pt.tag_id IN ($tagsList) GROUP BY pt.product_id HAVING COUNT(DISTINCT pt.tag_id) = " . count($tags) . ")";
    }


    if (!empty($dateFrom)) {
        $sql .= " AND p.created_date >= '" . $conn->real_escape_string($dateFrom) . "'";
    }

    if (!empty($dateTo)) {
        $sql .= " AND p.created_date <= '" . $conn->real_escape_string($dateTo) . "'";
    }

    if (!empty($priceFrom)) {
        $sql .= " AND p.price >= " . (float)$priceFrom;
    }

    if (!empty($priceTo)) {
        $sql .= " AND p.price <= " . (float)$priceTo;
    }

    $allowedSortColumns = ['price', 'created_date', 'title'];
    if (!in_array($sortBy, $allowedSortColumns)) {
        $sortBy = 'p.created_date'; 
    }

    $sql .= " GROUP BY p.id ORDER BY $sortBy $sortOrder;";

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
if (isset($_POST['add-product'])) {
    $sku = trim($_POST["sku"]); 

    if (empty($sku)) {
        $sku = 'SKU-' . strtoupper(bin2hex(random_bytes(4))); 
    }

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $gallery_images = $_POST["gallery_images"];
    $categories = $_POST["categories"];
    $tags = $_POST["tags"];
    $uploadedFile = null;
    
    $errors = validateProduct($conn, $sku, $title, $price, $typeValidate[0]);

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['show_modal'] = true;
        header("location: index.php");
        exit;
    }

    if (isset($_FILES['featured_image_file']) &&
    $_FILES['featured_image_file']['error'] === UPLOAD_ERR_OK) {
        // Lấy thông tin file
        $fileTmpPath = $_FILES['featured_image_file']['tmp_name'];
        $fileName = $_FILES['featured_image_file']['name'];
        $fileSize = $_FILES['featured_image_file']['size'];
        $fileType = $_FILES['featured_image_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Định nghĩa các loại file hợp lệ
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'jfif');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Đường dẫn thư mục tải lên
            $uploadFileDir = './uploads/';
            $dest_path = $uploadFileDir . $fileName;

            // Di chuyển file vào thư mục đích
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Nếu upload thành công, lưu tên file vào biến
                $uploadedFile = $fileName;
            } else {
                echo 'There was an error moving the uploaded file.';
            }
        } else {
            echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        // Nếu không có file được upload
        $uploadedFile = null;
        echo 'No file uploaded or there was an error during the upload.';
    }

    // $imageURL = $uploadedFile ? $uploadedFile : $featured_image;
    
    $sql_query = "INSERT INTO products (sku, title, price, featured_image) 
                VALUES ('$sku', '$title', '$price', '$uploadedFile')";
    $result = mysqli_query($conn, $sql_query);

    $last_product_id = mysqli_insert_id($conn);
    if (!$last_product_id) {
        $_SESSION['status'] = "Add product failed.";
        header("location: index.php");
        exit;
    }


    if (isset($_FILES['gallery_images_file'])) {
        $uploadFileDir = './uploads/';
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg', 'jfif');
        
        // Lặp qua tất cả các file được chọn
        foreach ($_FILES['gallery_images_file']['name'] as $key => $fileName) {
            $fileTmpPath = $_FILES['gallery_images_file']['tmp_name'][$key];
            $fileSize = $_FILES['gallery_images_file']['size'][$key];
            $fileType = $_FILES['gallery_images_file']['type'][$key];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
    
            // Kiểm tra định dạng file có hợp lệ không
            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Tạo đường dẫn đích đến nơi lưu trữ file
                $dest_path = $uploadFileDir . $fileName;
    
                // Di chuyển file vào thư mục đích
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Nếu upload thành công, lưu tên file vào cơ sở dữ liệu
                    $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                                    VALUES ('$last_product_id', '$fileName')";
                    mysqli_query($conn, $sql_gallery);
                    echo 'File ' . $fileName . ' uploaded successfully.<br>';
                } else {
                    echo 'Error moving the file ' . $fileName . '.<br>';
                }
            } else {
                echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions) . '<br>';
            }
        }
    } else {
        echo 'No files were uploaded.<br>';
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
        $_SESSION['status'] = "Add product successfully.";
    } else {
        $_SESSION['status'] = "Add product failed.";
    }

    header("location: index.php");
    exit();
}


// add property
if (isset($_POST['add-property'])) {

    $categories_input = trim($_POST["categories"]); 
    $tags_input = trim($_POST["tags"]);

    if(empty($categories_input) && empty($tags_input) ||
            ($categories_input == '' && $tags_input =='')){
        $_SESSION['status'] = "Add property failed.";
        header("location: index.php");
        exit;
    }

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
if (isset($_POST['edit-product'])) {

    $id = $_POST['id'];
    $sku = trim($_POST["sku"]); 

    if (empty($sku)) {
        $sql_sku = "SELECT p.sku FROM products p WHERE id = '$id'";
        $result = mysqli_query($conn, $sql_sku);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $sku = $row['sku']; 
        } else {
            $sku = null; 
        }
    }

    $title = trim($_POST["title"]);
    $price = trim($_POST["price"]);
    $featured_image = trim($_POST["featured_image_file"]);
    $gallery_images = $_POST["gallery_images_file"];
    $categories = $_POST["categories"];
    $tags = $_POST["tags"];

    $errors = validateProduct($conn, $sku, $title, $price, $typeValidate[1]);

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['show_modal'] = true;
        header("location: index.php");
        exit;
    }

    if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] == UPLOAD_ERR_OK) {
        $featured_image_name = basename($_FILES['featured_image_file']['name']);
        $featured_image_target = "./uploads/" . $featured_image_name;

        if (move_uploaded_file($_FILES['featured_image_file']['tmp_name'], $featured_image_target)) {
            $sql_update_product = "UPDATE products SET featured_image = '$featured_image_name' WHERE id = $id";
            mysqli_query($conn, $sql_update_product);
        } else {
            error_log("Error uploading featured image.");
        }
    }

    if (isset($_FILES['gallery_images_file-edit'])) {
        echo '<pre>';
        print_r($_FILES['gallery_images_file-edit']);
        echo '</pre>';
    }
        
    if (isset($_FILES['gallery_images_file-edit']) && count($_FILES['gallery_images_file-edit']['name']) > 0) {
        // Kiểm tra xem có ít nhất một file hợp lệ không
        $hasNewFiles = false;
        foreach ($_FILES['gallery_images_file-edit']['name'] as $key => $gallery_image_name) {
            // Kiểm tra nếu tên file không rỗng và không có lỗi
            if (!empty($gallery_image_name) && $_FILES['gallery_images_file-edit']['error'][$key] === UPLOAD_ERR_OK) {
                $hasNewFiles = true;
                break;
            }
        }
    
        if ($hasNewFiles) {
            // Nếu có ít nhất một hình ảnh mới, xóa hình ảnh cũ
            $sql_delete_gallery = "DELETE FROM product_gallery WHERE product_id = $id";
            mysqli_query($conn, $sql_delete_gallery);
    
            foreach ($_FILES['gallery_images_file-edit']['name'] as $key => $gallery_image_name) {
                // Kiểm tra lỗi tải lên
                if ($_FILES['gallery_images_file-edit']['error'][$key] === UPLOAD_ERR_OK) {
                    $gallery_image_target = "./uploads/" . basename($gallery_image_name);
                    if (move_uploaded_file($_FILES['gallery_images_file-edit']['tmp_name'][$key], $gallery_image_target)) {
                        // Thêm hình ảnh mới vào cơ sở dữ liệu
                        $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                                        VALUES ('$id', '$gallery_image_name')";
                        mysqli_query($conn, $sql_gallery);
                    } else {
                        error_log("Error uploading gallery image: " . $gallery_image_name);
                    }
                } else {
                    error_log("Error with file upload: " . $_FILES['gallery_images_file-edit']['error'][$key]);
                }
            }
        }
    }
    
    


    // Update product details
    $sql_update_product = "UPDATE products SET sku = '$sku', title = '$title', price = '$price' WHERE id = $id";
    mysqli_query($conn, $sql_update_product);
    if (mysqli_error($conn)) {
        error_log("Error updating product: " . mysqli_error($conn));
    }

    // Update categories
    $sql_delete_categories = "DELETE FROM product_categories WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_categories);
    if (mysqli_error($conn)) {
        error_log("Error deleting categories: " . mysqli_error($conn));
    }

    foreach ($categories as $category_id) {
        $sql_category = "INSERT INTO product_categories (product_id, category_id) 
                        VALUES ('$id', '$category_id')";
        mysqli_query($conn, $sql_category);
        if (mysqli_error($conn)) {
            error_log("Error inserting category: " . mysqli_error($conn));
        }
    }

    // Update tags
    $sql_delete_tags = "DELETE FROM product_tags WHERE product_id = $id";
    mysqli_query($conn, $sql_delete_tags);
    if (mysqli_error($conn)) {
        error_log("Error deleting tags: " . mysqli_error($conn));
    }

    foreach ($tags as $tag_id) {
        $sql_tag = "INSERT INTO product_tags (product_id, tag_id) 
                    VALUES ('$id', '$tag_id')";
        mysqli_query($conn, $sql_tag);
        if (mysqli_error($conn)) {
            error_log("Error inserting tag: " . mysqli_error($conn));
        }
    }

    // Feedback to user
    if (mysqli_affected_rows($conn) > 0) {
        $_SESSION['status'] = "Product updated successfully";
    } else {
        $_SESSION['status'] = "Failed to update product";
    }

    header("location: index.php");
    exit();
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
if (isset($_POST['delete-all'])){
    $sql_query = "DELETE FROM products";

    $result = mysqli_query($conn, $sql_query);
    
    $sql_gallery = "DELETE FROM  product_gallery";
    mysqli_query($conn, $sql_gallery);

    $sql_category = "DELETE FROM  product_categories";
    mysqli_query($conn, $sql_category);

    $sql_tag = "DELETE FROM product_tags";
    mysqli_query($conn, $sql_tag);


    if ($result) {
        $_SESSION['status'] = "Delete all successfuly";
    } else {
        $_SESSION['status'] = "Delete all faile";
    }


    header("location: index.php");
    exit();
} 
?>
