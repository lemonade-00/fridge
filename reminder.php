<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

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
            let sql = 'SELECT * FROM Reminders NATURAL JOIN items WHERE Reminders.ReminderID = items.itemID AND Description IS NOT NULL';
            let vari = '';
            let orderby = "ORDER BY ExpiryDate ASC";
            $.ajax({
                url: 'search.php',
                method: 'POST',
                data: { sql: sql, vari: vari, orderby: orderby },
                success: function (response) {
                    let tableModify = $('#mytable tbody');
                    let resultArray = JSON.parse(response);
                    tableModify.empty();
                    $.each(resultArray, function (i, item) {
                        let row = $('<tr>');
                        let cell1 = $('<td>').text(item.Name);
                        let cell2 = $('<td>').text(item.ItemNum);
                        let cell3 = $('<td>').text(item.ExpiryDate);
                        let cell4 = $('<td>').text(item.Description);

                        row.append(cell1);
                        row.append(cell2);
                        row.append(cell3);
                        row.append(cell4);
                        tableModify.append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });

    </script>
</head>

<body>
    <div class="row ts">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-secondary " style="height: 100vh; width:20%">
            <span class="fs-3 ps-3">Settings</span>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="reminder.php" class="nav-link text-white active ps-4 fs-5">
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
                    <a href="fridge_edit.php" class="nav-link text-white ps-4 fs-5">
                        <i class="bi bi-gear-fill"></i>&nbsp;&nbsp;修改
                    </a>
                </li>
            </ul>
            <hr>
        </div>
        <div class="d-flex flex-column justify-content-between align-items-center w-75 mt-5">
            <table class="table table-hover table-striped custom-coffee w-75" id="mytable">
                <thead>
                    <tr>
                        <th scope="col" colspan="2" class="ps-4 fs-4">Reminder</th>
                    </tr>
                    <tr class="text-center fs-5" style="background-color: #002d72; color:white">
                        <th scope="col">物品名稱</th>
                        <th scope="col">數量</th>
                        <th scope="col">到期日</th>
                        <th scope="col">狀態描述</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                </tbody>
            </table>
        </div>
    </div>
</body>


</html>