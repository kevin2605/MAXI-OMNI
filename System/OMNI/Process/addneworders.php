<?php

// include "../../DBConnection.php";
class AddNewOrders
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function processOrders($orders)
    {
        $successCount = 0;
        $errorCount = 0;

        foreach ($orders as $order) {
            if ($this->saveOrder($order)) {
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

    private function saveOrder($order)
    {
        try {
            // Debug: Print order data
            error_log("Order Data: " . print_r($order, true));

            // Data untuk order_header
            $orderID = $order['order_id'];

            // Cek apakah OrderID sudah ada di tabel order_header
            $check_sql = "SELECT COUNT(*) as count FROM order_header WHERE OrderID = ?";
            $check_stmt = $this->conn->prepare($check_sql);
            if (!$check_stmt) {
                error_log("Error preparing check statement: " . $this->conn->error);
                return false;
            }

            $check_stmt->bind_param("i", $orderID);
            if (!$check_stmt->execute()) {
                error_log("Error executing check statement: " . $check_stmt->error);
                $check_stmt->close();
                return false;
            }

            $result = $check_stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $check_stmt->close();

            if ($count > 0) {
                // OrderID sudah ada, skip proses penyimpanan
                error_log("OrderID $orderID already exists. Skipping.");
                return true;
            }

            // Jika OrderID belum ada, lanjutkan proses penyimpanan
            $orderDate = date('Y-m-d H:i:s', strtotime($order['payment_date']));
            $marketplace = 'Tokopedia';
            $totalAmount = $order['amt']['ttl_amount'];
            $orderStatus = $order['order_status'];
            $orderStatusDesc = $this->getOrderStatusDesc($orderStatus);
            $orderRefNum = $order['invoice_ref_num'];
            $shippingAgent = $order['logistics']['shipping_agency'];

            // Insert ke order_header
            $sql_header = "INSERT INTO order_header 
                          (OrderID, OrderDate, Marketplace, TotalAmount, OrderStatus, 
                           OrderStatusDesc, OrderRefNum, ShippingAgent)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_header = $this->conn->prepare($sql_header);
            if (!$stmt_header) {
                error_log("Error preparing header statement: " . $this->conn->error);
                return false;
            }

            $stmt_header->bind_param(
                "issdisss",
                $orderID,
                $orderDate,
                $marketplace,
                $totalAmount,
                $orderStatus,
                $orderStatusDesc,
                $orderRefNum,
                $shippingAgent
            );

            if (!$stmt_header->execute()) {
                error_log("Error executing header insert: " . $stmt_header->error);
                $stmt_header->close();
                return false;
            }
            $stmt_header->close();

            // Process order details
            if (isset($order['products']) && is_array($order['products'])) {
                error_log("Processing products: " . count($order['products']));
                foreach ($order['products'] as $product) {
                    error_log("Processing product: " . print_r($product, true));
                    if (!$this->saveOrderDetail($orderID, $orderDate, $product)) {
                        error_log("Failed to save product detail");
                        return false;
                    }
                }
            } else {
                error_log("No products found in order");
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in saveOrder: " . $e->getMessage());
            return false;
        }
    }

    private function saveOrderDetail($orderID, $orderDate, $product)
    {
        try {
            // Debug: Print product data
            error_log("Product Data for OrderID $orderID: " . print_r($product, true));

            $productID = $product['id'];
            $sku = $product['sku'];
            $productName = $product['name'];
            $quantity = $product['quantity'];
            $price = $product['price'];
            $totalPrice = $product['total_price'];

            $sql_detail = "INSERT INTO order_detail 
                          (OrderID, OrderDate, ProductID, SKU, ProductName, 
                           Quantity, Price, TotalPrice)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_detail = $this->conn->prepare($sql_detail);
            if (!$stmt_detail) {
                error_log("Prepare failed: " . $this->conn->error);
                return false;
            }

            $stmt_detail->bind_param(
                "isissidd",
                $orderID,
                $orderDate,
                $productID,
                $sku,
                $productName,
                $quantity,
                $price,
                $totalPrice
            );

            if (!$stmt_detail->execute()) {
                error_log("Error executing detail insert: " . $stmt_detail->error);
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log("Error in saveOrderDetail: " . $e->getMessage());
            return false;
        }
    }

    private function getOrderStatusDesc($status)
    {
        switch ($status) {
            case 0:
                return '';
            case 100:
                return 'Order Created';
            case 220:
                return 'Payment verified, order ready to process';
            case 400:
                return 'Seller accept order';
            case 450:
                return 'Waiting for pickup';
            case 500:
                return 'Order shipmen';
            case 600:
                return 'Order delivered';
            case 700:
                return 'Order finished';
            default:
                return ' Status Tidak Diketahui';
        }
    }
}
?>