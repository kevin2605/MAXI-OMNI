<?php

include "../../DBConnection.php";
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
            error_log("Order Data: " . print_r($order, true));
            $orderID = $order['order_id'];

            $check_sql = "SELECT OrderStatus FROM order_header WHERE OrderID = ?";
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
            $existingOrder = $result->fetch_assoc();
            $check_stmt->close();

            $orderDate = date('Y-m-d H:i:s', strtotime($order['payment_date']));
            $marketplace = 'Tokopedia';
            $totalAmount = $order['amt']['ttl_amount'];
            $orderStatus = $order['order_status'];
            $orderStatusDesc = $this->getOrderStatusDesc($orderStatus);
            $orderRefNum = $order['invoice_ref_num'];
            $shippingAgent = $order['logistics']['shipping_agency'];

            if ($existingOrder) {
                if ($existingOrder['OrderStatus'] != $orderStatus) {
                    $update_sql = "UPDATE order_header 
                                   SET OrderStatus = ?, OrderStatusDesc = ?, TotalAmount = ?, ShippingAgent = ?, OrderDate = ? 
                                   WHERE OrderID = ?";
                    $update_stmt = $this->conn->prepare($update_sql);
                    if (!$update_stmt) {
                        error_log("Error preparing update statement: " . $this->conn->error);
                        return false;
                    }

                    $update_stmt->bind_param(
                        "ssdssi",
                        $orderStatus,
                        $orderStatusDesc,
                        $totalAmount,
                        $shippingAgent,
                        $orderDate,
                        $orderID
                    );

                    if (!$update_stmt->execute()) {
                        error_log("Error executing update statement: " . $update_stmt->error);
                        $update_stmt->close();
                        return false;
                    }

                    $update_stmt->close();
                    error_log("OrderID $orderID updated successfully.");
                } else {
                    error_log("OrderID $orderID already exists with the same status. No update needed.");
                }
                return true;
            }

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
    public function updateOrderStatus($order_id, $order_status, $order_status_desc)
    {
        try {
            $update_sql = "UPDATE order_header SET OrderStatus = ?, OrderStatusDesc = ? WHERE OrderID = ?";
            $update_stmt = $this->conn->prepare($update_sql);
            if (!$update_stmt) {
                error_log("Error preparing update statement: " . $this->conn->error);
                return false;
            }

            $update_stmt->bind_param("isi", $order_status, $order_status_desc, $order_id);
            if (!$update_stmt->execute()) {
                error_log("Error executing update statement: " . $update_stmt->error);
                $update_stmt->close();
                return false;
            }

            $update_stmt->close();
            error_log("OrderID $order_id updated successfully.");
            return true;

        } catch (Exception $e) {
            error_log("Error processing single order: " . $e->getMessage());
            return false;
        }
    }


    public function getOrderStatusDesc($status)
    {
        switch ($status) {
            //  NEW
            case 100:
                return 'Order Created';
            case 103:
                return 'Waiting for payment confirmation from third party';

            //  Proccess
            case 220:
                return 'Payment verified, order ready to process';
            case 221:
                return 'Waiting for partner approval';
            case 400:
                return 'Seller accept order';
            case 450:
                return 'Waiting for pickup';
            case 500:
                return 'Order shipment';
            case 501:
                return 'Status changed to waiting resi have no input';
            case 520:
                return 'Invalid shipment reference number (AWB)';
            case 530:
                return 'Requested by user to correct invalid entry of shipment reference number';
            case 540:
                return 'Delivered to Pickup Point';

            //  Selesai
            case 600:
                return 'Order delivered';
            case 700:
                return 'Order finished';

            //  Cancel
            case 0:
                return 'Seller cancel order';
            case 3:
                return 'Order rejected due to empty stock';
            case 5:
                return 'Order canceled by fraud';
            case 6:
                return 'Order rejected (auto cancel out of stock)';
            case 10:
                return 'Order rejected by seller';
            case 15:
                return 'Instant cancel by buyer';
            case 550:
                return 'Return to Seller';
            case 601:
                return 'Buyer open a case to finish an order';
            case 690:
                return 'Fraud review';

            // Default 
            default:
                return 'Status Tidak Diketahui';
        }
    }

}
?>