<?php 
// Hàm xử lý upload file
function handleFeatureUpload($file) {
    $allowedfileExtensions = ['jpg', 'gif', 'png', 'jpeg', 'jfif'];
    $fileTmpPath = $file['tmp_name'];
    $fileName = $file['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));


    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return $fileName; 
        }
    } 
}

?>