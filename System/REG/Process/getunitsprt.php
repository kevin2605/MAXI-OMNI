<?php

include "../DBConnection.php";

if (isset($_POST['prodcd'])) {
    $prodcd = $_POST['prodcd'];
    $query = "SELECT UnitCD FROM sparepart WHERE PartName = '$prodcd' OR PartCD = '$prodcd'";
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