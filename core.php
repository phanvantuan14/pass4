<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");


$sku = "";
$title = "";
$price = "";
$featured_image = "";
$gallery_images = "";
$categories = [];
$tags = [];


// add product
if (isset($_POST['add-product'])) {
    $sku = $_POST["sku"];
    $title = $_POST["title"];
    $price = $_POST["price"];
    $featured_image = $_POST["featured_image"];
    $gallery_images = $_POST["gallery_images"];
    $categories = $_POST["categories"];
    $tags = $_POST["tags"];


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

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
    }

    header("location: index.php");
    exit();
}


//update product
if (isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']); // Tránh SQL injection
    $sql_query = "SELECT p.sku, p.title, p.price, p.featured_image, 
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
} else {
    echo json_encode(null); // Nếu không nhận được id
    exit();
}

