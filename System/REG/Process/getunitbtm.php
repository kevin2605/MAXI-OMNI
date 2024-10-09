<?php

include "../DBConnection.php";

if (isset($_POST['prodcd'])) {
    $prodcd = $_POST['prodcd'];
    $query = "SELECT UnitCD_2 FROM material WHERE MaterialName = '$prodcd' OR MaterialCD = '$prodcd'";
    $result = mysqli_query($conn, $query);

    $unit = [];
    if ($row = mysqli_fetch_assoc($result)) {
        $unit[] = [
            'UnitCD' => $row['UnitCD_2']
        ];
    }

    echo json_encode($unit);
}
?>