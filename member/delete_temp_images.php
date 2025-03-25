<?php
$uploadDir = '../uploads/';

$data = json_decode(file_get_contents("php://input"), true);
if (!empty($data['images'])) {
    foreach ($data['images'] as $filename) {
        $filePath = $uploadDir . basename($filename);
        if (file_exists($filePath)) {
            unlink($filePath); 
        }
    }
}

echo json_encode(["success" => true]);
?>
