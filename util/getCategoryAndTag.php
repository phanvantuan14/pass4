<?php
$conn = mysqli_connect("localhost", "root", "", "phantuan_sql");



$sql_categories = "SELECT id, name FROM categories";
$result_categories = $conn->query($sql_categories);
$categories_list = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories_list[] = $row;
    }
}

$sql_tags = "SELECT id, name FROM tags";
$result_tags = $conn->query($sql_tags);
$tags_list = [];
if ($result_tags->num_rows > 0) {
    while ($row = $result_tags->fetch_assoc()) {
        $tags_list[] = $row;
    }
}
?>
