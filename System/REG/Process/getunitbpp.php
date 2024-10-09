<?php

include "../DBConnection.php";

if (isset($_POST['prodcd'])) {
    $prodcd = $_POST['prodcd'];
    $query = "SELECT UnitCD FROM supportinggoods WHERE GoodsName = '$prodcd' OR GoodsCD = '$prodcd'";
    $result = mysqli_query($conn, $query);

    $unit = [];
    if ($row = mysqli_fetch_assoc($result)) {
        $unit[] = [
            'UnitCD' => $row['UnitCD']
        ];
    }

    echo json_encode($unit);
}
?>