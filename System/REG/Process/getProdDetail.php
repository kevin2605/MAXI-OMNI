<?php

include "../DBConnection.php";

$prodcd = $_GET["product"];

$query = "SELECT * FROM product WHERE ProductCD='".$prodcd."'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

echo '<h3>Informasi Barang</h3>';
echo'<table class="table">
        <tfoot>
            <tr> 
            <td>Kode Barang :</td>
            <td colspan="1">'.$row["ProductCD"].' </td>
            </tr>
            <tr> 
            <td>Nama Barang :</td>
            <td colspan="1">'.$row["ProductName"].'</td>
            </tr>
            <tr> 
            <td>Tanggal dibuat :</td>
            <td colspan="1">'.$row["CreatedOn"].'</td>
            </tr>
            <tr> 
            <td>Dibuat oleh :</td>
            <td colspan="2">'.$row["CreatedBy"].'</td>
            </tr>
        </tfoot>
    </table>';
echo '<br><a class="btn btn-warning" href="product.php">Back</a>';
?>