<?php
include "../../DBConnection.php";

class ProdukShopee
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function processShopee($products)
    {
        $successCount = 0;
        $errorCount = 0;

        foreach ($products as $product) {
            if ($this->simpanProduk($product)) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        if ($errorCount == 0) {
            return [
                'status' => 'success'
            ];
        } else {
            return [
                'status' => 'error'
            ];
        }
    }

    public function simpanProduk($product)
    {
        if (!$this->conn) {
            die("Koneksi gagal");
        }

        // Ambil data dari response API Shopee
        $productId = $product['item_id'];
        $productName = $product['item_name'];
        $productSKU = $product['item_sku'];
        $description = isset($product['description_info']['extended_description']['field_list'][0]['text'])
            ? $product['description_info']['extended_description']['field_list'][0]['text']
            : '';
        $status = ($product['item_status'] === 'NORMAL') ? 1 : 0;

        $createdOn = date('Y-m-d H:i:s', $product['create_time']);
        $lastUpdated = date('Y-m-d H:i:s', $product['update_time']);
        $modalPrice = isset($product['price_info'][0]['original_price']) ? $product['price_info'][0]['original_price'] : 0;
        $sellingPrice = isset($product['price_info'][0]['current_price']) ? $product['price_info'][0]['current_price'] : 0;
        $productvariantshopee = $product['has_model'] === true ? 1 : 0;
        $stockValue = isset($product['stock_info_v2']['summary_info']['total_available_stock']) ? $product['stock_info_v2']['summary_info']['total_available_stock'] : 0;
        $mainStock = isset($product['stock_info_v2']['seller_stock'][0]['stock']) ? $product['stock_info_v2']['seller_stock'][0]['stock'] : 0;
        $availableIn = 'shopee';

        // Ambil gambar produk
        $imageNames = [];
        if (isset($product['image']['image_url_list']) && !empty($product['image']['image_url_list'])) {
            foreach ($product['image']['image_url_list'] as $imageUrl) {
                $img = basename($imageUrl); // Ambil nama file dari URL
                $imgFolder = '../Produk/ProductImg/';
                $imgFile = $imgFolder . $img;

                // Download gambar dari URL
                $ch = curl_init($imageUrl);
                $fp = fopen($imgFile, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                $imageNames[] = $img;
            }
        }

        $img = !empty($imageNames) ? implode(',', $imageNames) : '';

        // Cek apakah produk sudah ada di database
        $queryCek = "SELECT * FROM productomni WHERE ProductID = ?";
        $stmt = $this->conn->prepare($queryCek);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $hasilCek = $stmt->get_result();
        $stmt->close();

        // Jika produk sudah ada, lakukan update. Jika belum, lakukan insert.
        if ($hasilCek->num_rows > 0) {
            $queryUpdate = "UPDATE productomni SET ProductName = ?, SKU = ?, Img = ?, Description = ?, ModalPrice = ?, SellingPrice = ?, isParent = ?, StockValue = ?, MainStock = ?, CreatedOn = ?, LastUpdated = ?, AvailableIn = ?, Status = ? WHERE ProductID = ?";
            $stmt = $this->conn->prepare($queryUpdate);
            $stmt->bind_param("ssssddiisssssi", $productName, $productSKU, $img, $description, $modalPrice, $sellingPrice, $productvariantshopee, $stockValue, $mainStock, $createdOn, $lastUpdated, $availableIn, $status, $productId);
        } else {
            $queryInsert = "INSERT INTO productomni (ProductID, ProductName, SKU, Img, Description, ModalPrice, SellingPrice, isParent, StockValue, MainStock, CreatedOn, LastUpdated, AvailableIn, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($queryInsert);
            $stmt->bind_param("isssddissssssi", $productId, $productName, $productSKU, $img, $description, $modalPrice, $sellingPrice, $productvariantshopee, $stockValue, $mainStock, $createdOn, $lastUpdated, $availableIn, $status);
        }

        // Eksekusi query
        if (!$stmt->execute()) {
            error_log("Error executing query: " . $stmt->error);
            return false;
        }
        $stmt->close();

        // Jika produk memiliki varian, simpan varian ke tabel productvariantomni
        if ($product['has_model']) {
            $variants = getShopeeProductVariants(product_id: $productId);

            if (isset($variants['response']['model']) && !empty($variants['response']['model'])) {
                foreach ($variants['response']['model'] as $variantx) {
                    $productIdVariant = $variantx['model_id'];
                    $productNameVariant = $variantx['model_name'];
                    $productSKUVariant = $variantx['model_sku'];

                    // Ambil harga varian
                    $modalPriceVariant = isset($variantx['price_info'][0]['original_price']) ? $variantx['price_info'][0]['original_price'] : 0;
                    $sellingPriceVariant = isset($variantx['price_info'][0]['current_price']) ? $variantx['price_info'][0]['current_price'] : 0;

                    // Ambil stok varian
                    $stockValueVariant = isset($variantx['stock_info_v2']['summary_info']['total_available_stock']) ? $variantx['stock_info_v2']['summary_info']['total_available_stock'] : 0;
                    $mainStockVariant = isset($variantx['stock_info_v2']['seller_stock'][0]['stock']) ? $variantx['stock_info_v2']['seller_stock'][0]['stock'] : 0;

                    // Ambil gambar varian (jika ada)
                    // Ambil gambar varian (jika ada)
                    $imgVariant = '';
                    $imgFolder = '../Produk/ProductImg/';

                    if (isset($variantx['tier_index']) && !empty($variantx['tier_index'])) {
                        $tierIndex = $variantx['tier_index'];
                        $variantIndex = $tierIndex[0]; // Ambil index varian yang sesuai

                        // Cek apakah varian memiliki gambar
                        if (
                            isset($variants['response']['tier_variation'][0]['option_list'][$variantIndex]['image']['image_url']) &&
                            !empty($variants['response']['tier_variation'][0]['option_list'][$variantIndex]['image']['image_url'])
                        ) {

                            $imgVariantUrl = $variants['response']['tier_variation'][0]['option_list'][$variantIndex]['image']['image_url'];
                            $imgVariant = basename($imgVariantUrl); // Ambil nama file dari URL
                            $imgVariantFile = $imgFolder . $imgVariant;

                            // Download gambar dari URL
                            $ch = curl_init($imgVariantUrl);
                            $fp = fopen($imgVariantFile, 'wb');
                            curl_setopt($ch, CURLOPT_FILE, $fp);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_exec($ch);
                            curl_close($ch);
                            fclose($fp);
                        }
                    }

                    // Jika tidak ada gambar varian, gunakan gambar utama
                    if (empty($imgVariant)) {
                        $imgVariant = $img; // $img adalah gambar utama produk
                    }


                    $statusVariant = 1;

                    // Cek apakah varian sudah ada di database
                    $queryVariantCek = "SELECT * FROM productvariantomni WHERE ProductIDVariant = ?";
                    $stmtVariant = $this->conn->prepare($queryVariantCek);
                    $stmtVariant->bind_param("i", $productIdVariant);
                    $stmtVariant->execute();
                    $hasilVariantCek = $stmtVariant->get_result();

                    if ($hasilVariantCek->num_rows > 0) {
                        // Update varian yang sudah ada
                        $queryUpdateVariant = "UPDATE productvariantomni SET 
                            ProductName = ?, 
                            SKU = ?, 
                            Img = ?, 
                            ModalPrice = ?, 
                            SellingPrice = ?, 
                            StockValue = ?, 
                            MainStock = ?, 
                            ReserveStock = ?, 
                            Status = ? 
                            WHERE ProductIDVariant = ?";
                        $stmtVariant = $this->conn->prepare($queryUpdateVariant);
                        $stmtVariant->bind_param(
                            "ssssdiisss",
                            $productNameVariant,
                            $productSKUVariant,
                            $imgVariant,
                            $modalPriceVariant,
                            $sellingPriceVariant,
                            $stockValueVariant,
                            $mainStockVariant,
                            $mainStockVariant,
                            $statusVariant,
                            $productIdVariant
                        );
                    } else {
                        // Insert varian baru
                        $queryInsertVariant = "INSERT INTO productvariantomni 
                            (ProductIDVariant, ProductName, SKU, Img, ModalPrice, SellingPrice, StockValue, MainStock, ReserveStock, Status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmtVariant = $this->conn->prepare($queryInsertVariant);
                        $stmtVariant->bind_param(
                            "isssdiiisi",
                            $productIdVariant,
                            $productNameVariant,
                            $productSKUVariant,
                            $imgVariant,
                            $modalPriceVariant,
                            $sellingPriceVariant,
                            $stockValueVariant,
                            $mainStockVariant,
                            $mainStockVariant,
                            $statusVariant
                        );
                    }

                    // Eksekusi query
                    if (!$stmtVariant->execute()) {
                        error_log("Gagal menyimpan data varian: " . $stmtVariant->error);
                        error_log("Query: " . ($hasilVariantCek->num_rows > 0 ? $queryUpdateVariant : $queryInsertVariant));
                        error_log("Data: " . print_r([
                            $productIdVariant,
                            $productNameVariant,
                            $productSKUVariant,
                            $imgVariant,
                            $modalPriceVariant,
                            $sellingPriceVariant,
                            $stockValueVariant,
                            $mainStockVariant,
                            $mainStockVariant,
                            $statusVariant
                        ], true));
                    } else {
                        error_log("Data varian berhasil " . ($hasilVariantCek->num_rows > 0 ? "diupdate" : "disimpan") . ".");
                    }

                    $stmtVariant->close();
                }
            } else {
                error_log("Tidak ada data varian yang ditemukan.");
            }
        }

        return true;
    }
}
?>