<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "db_conn.php";

    // Check if $_POST array keys exist
    $itemName = $_POST["itemName"];
    $keep = $_POST["keep"];
    $category = $_POST["category"];
    $typeNumber = $_POST["typeNumber"];
    $datepicker = $_POST["datepicker"];
    
    // Assuming $Description is defined and has a value
    //$Description = "YourDescriptionValue"; // Replace with the actual value

    // Use prepared statement to prevent SQL injection
    $sql = "INSERT INTO `items` (`ItemID`, `Name`, `Category`, `ExpiryDate`, `ItemNum`) VALUES (null, ?, ?, ?, ?)";
    if ($stmt = $db->prepare($sql)) {
        $combinedValue = $category . "(" . $keep . ")";
        $success = $stmt->execute(array($itemName, $combinedValue, $datepicker, $typeNumber));
        if (!$success) {
          echo "<script>alert('後端儲存失敗!');</script>";
          //header('Location: add.php');// Using implode to display the error info as a string
        }
        else {
          echo "<script>alert('儲存成功!'); window.location.href = 'add.php';</script>";
          //header('Location: add.php'); 
          //header('Location: add.php'); // Ensure that the script stops execution after the redirect
        }
    }
    // Set the statement to null to release resources
    $stmt = null;

    $sqlFilePath = "reset.sql"; // 請將路徑替換為實際檔案路徑

    // 讀取 SQL 檔案內容
    $sqlContent = file_get_contents($sqlFilePath);

    // 使用 exec 函數執行 SQL 查詢
    try {
        $db->exec($sqlContent);
        echo "<script>alert('儲存成功!'); window.location.href = 'add.php';</script>";
    } catch (PDOException $e) {
        echo "錯誤：" . $e->getMessage();
        echo "<script>alert('儲存失敗!') ;</script>";
    }

}
?>
