<?php

include "../DBConnection.php";

$material = $_GET["material"];

$query = "SELECT * FROM material WHERE MaterialCD='".$material."'";
$result=mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

echo '<h3>Informasi Barang</h3>';
echo'<table class="table">
        <tfoot>
            <tr> 
            <td>Kode Bahan :</td>
            <td colspan="1">'.$row["MaterialCD"].' </td>
            </tr>
            <tr> 
            <td>Nama Barang :</td>
            <td colspan="1">'.$row["MaterialName"].'</td>
            </tr>
            <tr> 
            <td>Satuan Stok :</td>
            <td colspan="2">'.$row["UnitCD_2"].'</td>
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
echo '<br><a class="btn btn-warning" href="material.php">Back</a>';
?>