<?php
include "../../DBConnection.php";

class ProdukTokopedia
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function processSimpan($products)
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

        $basic = $product['basic'];

        $productId = $basic['productID'];
        $productName = $basic['name'];
        $status = $basic['status'];
        $Description = $basic['shortDesc'];
        date_default_timezone_set('Asia/Jakarta');
        $CreatedOn = date('Y-m-d H:i:s', $basic['createTimeUnix']);
        $LastUpdated = date('Y-m-d H:i:s', $basic['updateTimeUnix']);

        $price = $product['price'];
        $modalPrice = $price['value'];
        $sellingPrice = $price['idr'];

        $variant = $product['variant'];
        $isParent = isset($variant['isParent']) ? ($variant['isParent'] ? 1 : 0) : 0;
        $childrenIDs = isset($variant['childrenID']) && !empty($variant['childrenID'])
            ? implode(',', $variant['childrenID'])
            : '';

        $stockvalue = isset($product['stock']['value']) ? $product['stock']['value'] : 0;
        $mainstock = isset($product['main_stock']) ? $product['main_stock'] : 0;
        $availablein = 'tokopedia';



        error_log("Deskripsi sebelum bind_param: " . $Description);

        $queryCek = "SELECT * FROM productomni WHERE ProductID = ?";
        $stmt = $this->conn->prepare($queryCek);

        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $hasilCek = $stmt->get_result();
        $stmt->close();
        $variants = getProductVariants($productId);


        if (isset($variants['data']['children']) && !empty($variants['data']['children'])) {
            foreach ($variants['data']['children'] as $variantx) {

                $productid = isset($variantx['product_id']) ? intval($variantx['product_id']) : 0;
                $prodname = isset($variantx['name']) ? $variantx['name'] : 'Unknown';
                $pricevariant = isset($variantx['price']) ? intval($variantx['price']) : 0;
                $pricemodal = isset($variantx['price_fmt']) ? intval(str_replace(['Rp', '.', ','], '', $variantx['price_fmt'])) : 0;
                $stockvaluevariant = isset($variantx['stock']) ? intval($variantx['stock']) : 0;
                $mainstockvariant = isset($variantx['main_stock']) ? intval($variantx['main_stock']) : 0;


                error_log("Data yang akan disimpan: " . json_encode([
                    'ProductIDVariant' => $productid,
                    'ProductName' => $prodname,
                    'ModalPrice' => $pricemodal,
                    'SellingPrice' => $pricevariant,
                    'StockValue' => $stockvaluevariant,
                    'MainStock' => $mainstockvariant
                ]));

                $queryVariantCek = "SELECT * FROM productvariantomni WHERE ProductIDVariant = ?";
                $stmtVariant = $this->conn->prepare($queryVariantCek);
                $stmtVariant->bind_param("i", $productid);
                $stmtVariant->execute();
                $hasilVariantCek = $stmtVariant->get_result();

                if ($hasilVariantCek->num_rows > 0) {
                    $queryUpdateVariant = "UPDATE productvariantomni SET ProductName = ?, ModalPrice = ?, SellingPrice = ?, StockValue = ?, MainStock = ?, ReserveStock = ?  WHERE ProductIDVariant = ?";
                    $stmtVariant = $this->conn->prepare($queryUpdateVariant);
                    $stmtVariant->bind_param("siiiisi", $prodname, $pricemodal, $pricevariant, $stockvaluevariant, $mainstockvariant, $mainstockvariant, $productid);
                } else {
                    $queryInsertVariant = "INSERT INTO productvariantomni (ProductIDVariant, ProductName, ModalPrice, SellingPrice, StockValue, MainStock, ReserveStock) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmtVariant = $this->conn->prepare($queryInsertVariant);
                    $stmtVariant->bind_param("isiiiis", $productid, $prodname, $pricemodal, $pricevariant, $stockvaluevariant, $mainstockvariant, $mainstockvariant);
                }

                if (!$stmtVariant->execute()) {
                    error_log("Gagal menyimpan data varian: " . $stmtVariant->error);
                } else {
                    error_log("Data varian berhasil disimpan.");
                }

                $stmtVariant->close();
            }
        } else {
            error_log("Tidak ada data varian yang ditemukan.");
        }

        $imageNames = [];
        if (isset($product['pictures']) && !empty($product['pictures'])) {
            foreach ($product['pictures'] as $picture) {
                $img = $picture['fileName'];
                $imgPath = $picture['filePath'];
                $imgOriginalURL = $picture['OriginalURL'];

                $imgFolder = '../Produk/ProductImg/';
                $imgFile = $imgFolder . $img;

                $ch = curl_init($imgOriginalURL);
                $fp = fopen($imgFile, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);

                $imageNames[] = $img;
            }
        }

        $img = $isParent ? implode(',', $imageNames) : (isset($imageNames[0]) ? $imageNames[0] : '');

        if ($hasilCek->num_rows > 0) {
            $queryUpdate = "UPDATE productomni SET ProductName = ?, Img = ?, Description = ?, ModalPrice = ?, SellingPrice = ?, isParent = ?, ChildID = ?, StockValue = ?, MainStock = ?, CreatedOn = ?, LastUpdated= ?, AvailableIn = ?, Status = ? WHERE ProductID = ?";
            $stmt = $this->conn->prepare($queryUpdate);
            $stmt->bind_param("sssdsssiisssss", $productName, $img, $Description, $modalPrice, $sellingPrice, $isParent, $childrenIDs, $stockvalue, $mainstock, $CreatedOn, $LastUpdated, $availablein, $status, $productId);
        } else {
            $queryInsert = "INSERT INTO productomni (ProductID, Img, ProductName, Description, ModalPrice, SellingPrice, isParent, ChildID, StockValue, MainStock, CreatedOn, LastUpdated, AvailableIn,Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($queryInsert);
            $stmt->bind_param("sssddssiisssss", $productId, $img, $productName, $Description, $modalPrice, $sellingPrice, $isParent, $childrenIDs, $stockvalue, $mainstock, $CreatedOn, $LastUpdated, $availablein, $status);
        }

        if (!$stmt->execute()) {
            error_log("Error executing query: " . $stmt->error);
        }
        $stmt->close();


    }
}
?>