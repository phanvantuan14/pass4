<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");

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

    if ($result) {
        $_SESSION['status'] = "Add product successfuly";
    } else {
        $_SESSION['status'] = "Add product faile";
    }

    $last_product_id = $conn->insert_id;

    if (!$last_product_id) {
        header("location: index.php");
        exit;
    }

    $gallery_images_array = explode(",", $gallery_images);
    foreach ($gallery_images_array as $image) {
        $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                            VALUES ('$last_product_id', '$image')";
        // $conn->query($sql_gallery);
        mysqli_query($conn, $sql_gallery);
    }

    foreach ($categories as $category_id) {
        $sql_category = "INSERT INTO product_categories (product_id, category_id) 
                            VALUES ('$last_product_id', '$category_id')";
        mysqli_query($conn, $sql_category);
        // $conn->query($sql_category);


    }

    foreach ($tags as $tag_id) {
        $sql_tag = "INSERT INTO product_tags (product_id, tag_id) 
                        VALUES ('$last_product_id', '$tag_id')";
        mysqli_query($conn, $sql_tag);
        // $conn->query($sql_tag);

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

        foreach($categories as $category){
            if (!empty($categories)) {
                $sql_category = "INSERT INTO categories (name) VALUES ('$category')";
                $conn->query($sql_category);
            }
        }
        
        foreach($tags as $tag){
            if (!empty($tags)) {
                $sql_tag = "INSERT INTO tags (name) VALUES ('$tag')";
                $conn->query($sql_tag);
            }
        }

        $conn->commit();

    } catch (Exception $e) {$conn->rollback();}

    header("location: index.php");
    exit();
}


//update product
if (isset($_POST['edit-product'])) {
    echo "edit product";

}

