<?php
include "db_conn.php";

$vari = $_POST["vari"];
$sql = $_POST["sql"];
$orderby = $_POST["orderby"];


$query = ($sql);
if ($orderby === '') {
    $stmt = $db->prepare($query);
} else {
    $stmt = $db->prepare($query . ' ' . $orderby);
}

if ($vari === '') {
    $success = $stmt->execute();
} else {
    $success = $stmt->execute(array($vari));
}

if (!$success) {
    echo "查詢失敗!" . $stmt->errorInfo();
    //echo "<script>alert('後端儲存失敗!');$stmt->errorInfo();</script>";
} else {
    $result = $stmt->fetchAll();
    echo json_encode($result);
}
/*for ($i = 0; $i < count($result); $i++) {
        echo "name:" . $result[$i]['Name'] . ' ' . "itemNum:" . $result[$i]['ItemNum'] . ' ' . "category:" . $result[$i]['Category'] . ' ' . "expirydate:" . $result[$i]['ExpiryDate'] . ' ' . '<br>';
}*/?>