<?php
session_start(); // Mulai session
require_once '../APITokenTokopedia.php';

$apiToken = new APITokenTokopedia();
$token = $apiToken->getToken();
$fs_id = $apiToken->getFsId();
$shop_ids = $apiToken->getShopIds();
$headers = $apiToken->getHeaders();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $description = $_POST['description']; // Ambil dari rich text editor
    $category_id = intval($_POST['category_id']);
    $initial_cost = floatval($_POST['initial_cost']);
    $selling_price = floatval($_POST['selling_price']);
    $qty = floatval($_POST['qty']);
    $weight = floatval($_POST['weight']);
    // $components = $_POST['components']; // Array komponen produk
    $platform = $_POST['platform']; // Array platform yang dipilih

    // Data yang akan dikirim ke API Tokopedia
    $data = [
        "products" => [
            [
                "sku" => $sku,
                "Name" => $name,
                "price" => $selling_price,
                "stock" => $qty, // Asumsi stok
                "status" => "LIMITED",
                "weight" => $weight, // Asumsi berat produk
                "pictures" => [
                    [
                        "file_path" => "https://ecs7.tokopedia.net/img/cache/700/product-1/2017/9/27/5510391/5510391_9968635e-a6f4-446a-84d0-ff3a98a5d4a2.jpg" // Ganti dengan URL gambar yang diunggah
                    ]
                ],
                "condition" => "NEW",
                "category_id" => $category_id,
                "Description" => $description,
                "weight_unit" => "GR",
                "price_currency" => "IDR",
                "min_order" => 1
            ]
        ]
    ];

    // URL API Tokopedia
    $apiUrl = "https://fs.tokopedia.net/v3/products/fs/{$fs_id}/create?shop_id={$shop_ids[0]}";

    // Inisialisasi cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Eksekusi request dan tangkap response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Ambil HTTP status code

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        // Cek jika request berhasil (HTTP status code 200)
        if ($httpCode == 200) {
            // Simpan pesan sukses ke session
            $_SESSION['success_message'] = "Produk berhasil ditambahkan!";

            // Redirect ke halaman ../Produk/add-produk.php
            header("Location: ../Produk/add-produk.php");
            exit(); // Pastikan tidak ada output lain sebelum redirect
        } else {
            echo "Gagal membuat produk. Response:\n" . $response;
        }
    }
    curl_close($ch);
} else {
    echo "Invalid request method.";
}
?>