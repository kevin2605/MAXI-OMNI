<?php
include '../../DBConnection.php';
if (isset($_COOKIE["UserID"]) && !empty($_COOKIE["UserID"])) {
    $creator = $_COOKIE["UserID"];
} else {
    die("Error: Cookie 'UserID' tidak ada atau kosong.");
}
$query_access = "SELECT Grup FROM useraccesslevel WHERE UserID = '$creator'";
$result_access = mysqli_query($conn, $query_access);
$can_updatee = false;
if ($result_access) {
    $row_access = mysqli_fetch_assoc($result_access);
    $access_level = $row_access['Grup'];
    if (strpos($access_level, 'U') !== false) {
        $can_updatee = true;
    }
} else {
    die("Error: Gagal mengambil data akses pengguna.");
}

$status_filter = isset($_GET['status']) ? $_GET['status'] : '1';
$query = "SELECT * FROM `groups`";

if ($status_filter == '1') {
    $query .= " WHERE Status = 1";
} elseif ($status_filter == '0') {
    $query .= " WHERE Status = 0";
}

$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        echo '
      <tr>
        <td>' . $row["Sequence"] . '</td>
        <td>' . $row["GroupCD"] . '</td>
        <td>' . $row["GroupName"] . '</td>
        <td>' . $row["LastEdit"] . '</td>
        <td> 
        <ul class="action">';
        if ($can_updatee) {
            echo '<button onclick="editGroup(this)" type="button" class="light-card border-warning border b-r-10" value="' . $row["GroupCD"] . '"><i class="icon-pencil-alt txt-warning"></i></button>';
        }
        echo ' 
                                            
        </ul>
            </td>
        </tr>
    ';
    }
} else {
    echo "Error: " . mysqli_error($conn);
}

?>