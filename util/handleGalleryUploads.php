<?php 

// Hàm xử lý upload hình ảnh trong thư viện
function handleGalleryUploads($files, $productId) {
    $uploadFileDir = './uploads/';
    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'jfif'];
    

    foreach ($files['name'] as $key => $fileName) {
        $fileTmpPath = $files['tmp_name'][$key];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql_gallery = "INSERT INTO product_gallery (product_id, image) 
                                VALUES ('$productId', '$fileName')";
                mysqli_query($GLOBALS['conn'], $sql_gallery);
            } 
        } 
    }

}

?>