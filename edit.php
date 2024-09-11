<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "db_conn.php";

    // Check if $_POST array keys exist
    $itemID = $_POST["itemID"];
    $category = $_POST["category"];
    $keep = $_POST["keep"];
    $typeNumber = $_POST["typeNumber"];
    $datepicker = $_POST["datepicker"];
        // Use prepared statement to prevent SQL injection
        $sql = "UPDATE items SET Category = ?, ExpiryDate = ?, ItemNum = ? WHERE ItemID = ?";
        $stmt = $db->prepare($sql);
        
        $combinedValue = $category . "(" . $keep . ")";

        // Bind parameters
        $stmt->bindParam(1, $combinedValue);
        $stmt->bindParam(2, $datepicker);
        $stmt->bindParam(3, $typeNumber);
        $stmt->bindParam(4, $itemID);

        // Execute the statement
        $success = $stmt->execute();
        
        if (!$success) {
            echo "<script>alert('後端儲存失敗!');</script>";
            
        } else {
            echo "<script>alert('儲存成功!');window.location.href = 'fridge_edit.php';</script>";
        }
    
    $stmt = null;

    $sqlFilePath = "edit.sql"; // 請將路徑替換為實際檔案路徑 window.location.href = 'fridge_edit.php';

    // 讀取 SQL 檔案內容
    $sqlContent = file_get_contents($sqlFilePath);

    // 使用 exec 函數執行 SQL 查詢
    try {
        $db->exec($sqlContent);
        echo "<script>alert('儲存成功!'); window.location.href = 'fridge_edit.php';</script>";
    } catch (PDOException $e) {
        echo "錯誤：" . $e->getMessage();
        echo "<script>alert('儲存失敗!') ;</script>";
    }
}
?>
