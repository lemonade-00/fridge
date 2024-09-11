<?php
include "db_conn.php";

$itemID = $_POST["itemID"];
$cathid = $_POST["cathid"];
$query = ('SELECT ItemNum FROM items WHERE items.itemID = ?');
$stmt = $db->prepare($query);
$success = $stmt->execute(array($itemID));
if (!$success) {
    echo "刪除失敗!" . $stmt->errorInfo();
} else {
    $result = $stmt->fetchColumn();
    echo $result;
    $query = ('UPDATE categories SET CategoryNum = CategoryNum - 1, ItemSum = ItemSum -' . $result . ' WHERE Category = ?');
    $stmt = $db->prepare($query);
    $success = $stmt->execute(array($cathid));
    if (!$success) {
        echo "category更新失敗!" . $stmt->errorInfo();
    }
}


$query = ('DELETE items, reminders FROM items JOIN reminders ON items.itemID = reminders.ReminderID WHERE itemID = ?');
$stmt = $db->prepare($query);
$success = $stmt->execute(array($itemID));
if (!$success) {
    echo "刪除失敗!" . $stmt->errorInfo();
} else {
    header("Location: fridge_edit.php");
}
?>