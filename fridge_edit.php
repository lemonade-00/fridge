<?php
include("db_conn.php");
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script
        src="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <link href="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <style type="text/css">
        @import url("https://fonts.googleapis.com/earlyaccess/cwtexyen.css");
        body {
            font-family: 'cwTeXYen';
            background-color: #DCDDDF;
            margin: 0;
            overflow-y: hidden;
            overflow-x: hidden;
        }

        .custom-coffee {
            background-color: white;
            color: black;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('.nav-link').click(function () {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
        function UpdateContent(element) {
            var row = element.closest('tr');
            var editModeElements = row.querySelectorAll('.edit-mode');
            var displayModeElements = row.querySelectorAll('.display-mode');
            var checkIcon = row.querySelector('.check-icon');

            // Toggle between display mode and edit mode
            editModeElements.forEach(function (element) {
                element.style.display = element.style.display === 'none' ? 'inline-block' : 'none';
            });

            displayModeElements.forEach(function (element) {
                element.style.display = element.style.display === 'none' ? 'inline-block' : 'none';
            });

            // Toggle the check icon
            checkIcon.style.display = checkIcon.style.display === 'none' ? 'inline-block' : 'none';
        }
        function check(itemID) {
            let datepicker = document.getElementById("datepicker" + itemID).value;
            if (datepicker === "") {
                alert('日期為必填欄位');
                event.preventDefault();
            }
            else{
                document.getElementById("mfrom " + itemID).action = "edit.php";//fridge_addsave.php
                document.getElementById("mfrom " + itemID).submit();
            }
        }
        function DeleteContent(itemID) {
            console.log(itemID);
            document.getElementById("mfrom " + itemID).action = "delete.php";//fridge_addsave.php
            document.getElementById("mfrom " + itemID).submit();
        }
    </script>
</head>

<body>
    <div class="row ts">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-secondary " style="height: 100vh; width:20%">
            <span class="fs-3 ps-3">Settings</span>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="reminder.php" class="nav-link text-white ps-4 fs-5">
                        <i class="bi bi-house"></i>&nbsp;&nbsp;首頁
                    </a>
                </li>
                <li class="nav-item">
                    <a href="searchfront.php" class="nav-link text-white ps-4 fs-5">
                        <i class="bi bi-search"></i>&nbsp;&nbsp;查詢
                    </a>
                </li>
                <li>
                    <a href="add.php" class="nav-link text-white ps-4 fs-5">
                        <i class="bi bi-folder-plus"></i>&nbsp;&nbsp;新增
                    </a>
                </li>
                <li>
                    <a href="fridge_edit.php" class="nav-link text-white active ps-4 fs-5">
                        <i class="bi bi-gear-fill"></i>&nbsp;&nbsp;修改
                    </a>
                </li>
            </ul>
            <hr>
        </div>
        <div class="d-flex flex-column justify-content-between align-items-center w-75 mt-5">
            <table class="table table-hover table-striped custom-coffee w-75">
                <thead>
                    <tr class="text-center" style="background-color: #002d72; color:white">
                        <th scope="col">物品名稱</th>
                        <th scope="col">數量</th>
                        <th scope="col">分類</th>
                        <th scope="col">到期日</th>
                        <th scope="col">操作</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    $query = "select * from Items";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <form method='post' id="mfrom <?php echo $row["ItemID"] ?>">
                            <input type="hidden" id="itemID" name="itemID" value="<?php echo $row["ItemID"] ?>">
                            <tr>
                                <td class="editable align-middle" style="width: auto;">
                                    <span id="itemName " name="itemName" value="<?php echo $row["Name"]; ?>">
                                        <?php echo $row["Name"]; ?>
                                    </span>
                                </td>
                                <td class="editable">
                                    <span class="display-mode">
                                        <?php echo $row['ItemNum']; ?>
                                    </span>
                                    <input type="number" id="typeNumber" name="typeNumber" min="1" max="100"
                                        class="edit-mode form-control" value="<?php echo $row['ItemNum']; ?>"
                                        style="display: none; margin: 5px;">
                                </td>
                                <td class="editable">
                                    <input type="hidden" id="cathid" name="cathid" value="<?php echo $row['Category'] ?>">
                                    <span class="display-mode">
                                        <?php echo $row['Category']; ?>
                                    </span>
                                    <div class="edit-mode" style="display: none; flex-grow: 1; margin: 5px;">
                                        <select class="form-select" id="category" name="category">
                                            <option>蔬菜</option>
                                            <option>水果</option>
                                            <option>海鮮</option>
                                            <option>肉類</option>
                                            <option>豆類</option>
                                            <option>乳製品</option>
                                            <option>蛋類</option>
                                            <option>其他</option>
                                        </select>
                                    </div>

                                    <div class="edit-mode" style="display: none;">
                                        <select class="form-select" id="keep" name="keep">
                                            <option>冷藏</option>
                                            <option>冷凍</option>
                                        </select>
                                    </div>
                                </td>
                                <td class="editable">
                                    <span class="display-mode">
                                        <?php echo $row['ExpiryDate']; ?>
                                    </span>
                                    <input type="text" class="edit-mode form-control datepicker" id="datepicker<?php echo $row['ItemID']; ?>" name="datepicker"
                                        value="<?php echo $row['ExpiryDate']; ?>" required="required" style="display: none; margin: 5px;"
                                        autocomplete="off">
                                </td>
                                <td class = "align-middle">
                                    <a href="#" onclick="check(<?php echo $row['ItemID']; ?>);" style="display: none;" class="check-icon">
                                        <i class="bi bi-check2-circle" style="color:black;" type='submit' name='update'
                                            value='更新'></i>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="#" onclick="UpdateContent(this);"><i class="bi bi-pencil-square align-middle"
                                            style="color:black;"></i></a>
                                    &nbsp;&nbsp;
                                    <a href="#" onclick="DeleteContent(<?php echo $row['ItemID']; ?>);"><i
                                            class="bi bi-trash-fill " style="color:black;"></i></a>
                                </td>
                            </tr>
                        </form>
                        <?php
                    }
                    ?>

                </tbody>
                <script>
                        let now = new Date();
                        let nowDate = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();
                        $('.datepicker').datepicker({
                            format: 'yyyy-mm-dd',
                            autoclose: true,
                            startDate: nowDate
                        });
                        
            </script>
            </table>
        </div>
    </div>
</body>

</html>