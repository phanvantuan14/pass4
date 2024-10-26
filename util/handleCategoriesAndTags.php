<?php 

// Hàm xử lý danh mục và thẻ
function handleCategoriesAndTags($categories, $tags, $productId) {
   foreach ($categories as $category_id) {
       $sql_category = "INSERT INTO product_categories (product_id, category_id) 
                        VALUES ('$productId', '$category_id')";
       mysqli_query($GLOBALS['conn'], $sql_category);
   }

   foreach ($tags as $tag_id) {
       $sql_tag = "INSERT INTO product_tags (product_id, tag_id) 
                   VALUES ('$productId', '$tag_id')";
       mysqli_query($GLOBALS['conn'], $sql_tag);
   }
}
?>