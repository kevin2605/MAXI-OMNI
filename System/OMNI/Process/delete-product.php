<?php
include "../../DBConnection.php"; // Pastikan file ini sudah ada dan berisi koneksi ke database

// Ambil data dari request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id']) || !is_array($data['product_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid product ID format."]);
    exit;
}

$productIDs = $data['product_id'];

// Validasi setiap ProductID harus angka positif
foreach ($productIDs as $productID) {
    if (!is_numeric($productID) || $productID <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid ProductID: " . htmlspecialchars($productID)]);
        exit;
    }
}

try {
    if (!$conn) {
        throw new Exception("Database connection failed.");
    }

    $conn->beginTransaction();

    $stmt = $conn->prepare("UPDATE productomni SET Status = 2, LastUpdated = NOW() WHERE ProductID = ?");

    foreach ($productIDs as $productID) {
        $stmt->execute([$productID]);
    }

    $conn->commit();
    $stmt->closeCursor(); // Membersihkan statement
    echo json_encode(["success" => "Product status updated successfully."]);

} catch (Exception $e) {
    $conn->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Failed to update product status: " . $e->getMessage()]);
}
?>