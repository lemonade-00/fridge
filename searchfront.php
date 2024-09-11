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

        .butt {
            position: relative;
            border-radius: 10px;
            box-sizing: border-box;
            height: 40px;
            width: 10vw;
            border-color: #BECBD3;
            overflow: hidden;
            background-color: #b1bbc7;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('.nav-link').click(function () {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
        window.addEventListener('load', function () {
            $('#reqModal').modal('show');
        });
        $(document).on('submit', 'form', function (event) {
            event.preventDefault();
            $('#reqModal').modal('hide');
        });
        document.addEventListener("DOMContentLoaded", function () {
            $(document).on('click', '#queryBtn', function () {
                let choose = $('#searchLab').val();
                let order = $('#keep').val();
                let vari, sql, orderby;
                if (choose === '名稱') {
                    sql = 'SELECT * FROM items WHERE Name LIKE ?';
                    vari = "%" + $('#itemName').val() + "%";
                    if (order === "由近而遠(到期日)") {
                        orderby = "ORDER BY ExpiryDate ASC";
                    } else {
                        orderby = "ORDER BY ExpiryDate DESC";
                    }
                } else if (choose === '分類') {
                    let choosed = document.querySelectorAll('#category input[type="checkbox"]');
                    let varitmp = "";
                    choosed.forEach(function (checkbox) {
                        if (checkbox.checked) {
                            if (varitmp != "")
                                varitmp += (' OR ');
                            let word = checkbox.nextElementSibling.textContent.trim();
                            varitmp += ('Category LIKE "' + word + '%"');
                        }
                    });
                    sql = 'SELECT * FROM categories WHERE ' + varitmp;
                    vari = '';
                    orderby = '';

                } else {
                    sql = 'SELECT * FROM items';
                    vari = '';
                    if (order === "由近而遠(到期日)") {
                        orderby = "ORDER BY ExpiryDate ASC";
                    } else {
                        orderby = "ORDER BY ExpiryDate DESC";
                    }
                }


                $.ajax({
                    url: 'search.php',
                    method: 'POST',
                    data: { sql: sql, vari: vari, orderby: orderby },
                    success: function (response) {
                        //console.log(response);
                        let tableModify = $('#mytable tbody');
                        let tableTitle = $('#mytable thead tr');
                        let resultArray = JSON.parse(response);
                        let row = $('<tr>');
                        tableModify.empty();
                        tableTitle.empty();
                        if (choose === '分類') {

                            let tit1 = $('<th>').text("分類");
                            let tit2 = $('<th>').text("種類數目");
                            let tit3 = $('<th>').text("食材數目");
                            tableTitle.append(tit1);
                            tableTitle.append(tit2);
                            tableTitle.append(tit3);
                            if (resultArray.length != 0) {
                                $.each(resultArray, function (i, item) {
                                    if (item.CategoryNum != 0) {
                                        let row = $('<tr>');
                                        let cell1 = $('<td>').text(item.Category);
                                        let cell2 = $('<td>').text(item.CategoryNum);
                                        let cell3 = $('<td>').text(item.ItemSum);
                                        row.append(cell1);
                                        row.append(cell2);
                                        row.append(cell3);
                                        tableModify.append(row);
                                    }
                                });
                            } else {
                                let cell5 = $('<td>').text("無相關食材分類").attr('colspan', '3');
                                row.append(cell5);
                                tableModify.append(row);
                            }

                        } else {
                            let tit1 = $('<th>').text("物品名稱");
                            let tit2 = $('<th>').text("數量");
                            let tit3 = $('<th>').text("分類");
                            let tit4 = $('<th>').text("到期日");
                            tableTitle.append(tit1);
                            tableTitle.append(tit2);
                            tableTitle.append(tit3);
                            tableTitle.append(tit4);

                            if (resultArray.length != 0) {
                                $.each(resultArray, function (i, item) {
                                    let row = $('<tr>');
                                    let cell1 = $('<td>').text(item.Name);
                                    let cell2 = $('<td>').text(item.ItemNum);
                                    let cell3 = $('<td>').text(item.Category);
                                    let cell4 = $('<td>').text(item.ExpiryDate);
                                    row.append(cell1);
                                    row.append(cell2);
                                    row.append(cell3);
                                    row.append(cell4);
                                    tableModify.append(row);
                                });
                            } else {
                                let cell5 = $('<td>').text("無相關食材").attr('colspan', '4');
                                row.append(cell5);
                                tableModify.append(row);
                            }
                        }

                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            });



            let nameValue = document.getElementById("itemName").value;
            let type = document.getElementById("searchLab");
            searchLab.addEventListener("change", function () {
                if (searchLab.value === "名稱") {
                    nameContainer.style.display = "block";
                    categoryContainer.style.display = "none";
                    orderContainer.style.display = "block";
                } else if (searchLab.value === "分類") {
                    nameContainer.style.display = "none";
                    categoryContainer.style.display = "block";
                    orderContainer.style.display = "none";
                } else {
                    nameContainer.style.display = "none";
                    categoryContainer.style.display = "none";
                    orderContainer.style.display = "block";
                }
            });
        })

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
                    <a href="searchfront.php" class="nav-link text-white active ps-4 fs-5">
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
        <div class="d-flex flex-column align-items-center w-75 mt-5">
            <div class="row mb-3 fs-6">
                <button class="butt" data-bs-toggle="modal" data-bs-target="#reqModal">
                    <i class="bi bi-search"></i>&nbsp;查詢
                </button>
            </div>
            <table class="table table-hover table-striped custom-coffee w-75 text-center" id="mytable">
                <thead>
                    <tr class=" fs-5" style="background-color: #002d72; color:white">
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <form id="mfrom" method="post">
        <div class="modal fade" id="reqModal" tabindex="-1" aria-labelledby="reqModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background-color:#D6DCDB;">
                    <div class="modal-header">
                        <h5 class="modal-title fs-2" id="reqModalLabel" style="color:#66828E;">物品查詢</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-4" id="reqc" style="color:#66828E;">
                        <div class="mb-3 col-md-6" id="itemNameContainer">
                            <label for="searchLab" class="form-label">查詢依據</label>
                            <select class="form-select" id="searchLab" name="searchkey">
                                <option>名稱</option>
                                <option>分類</option>
                                <option>全部</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6" id="nameContainer">
                            <label for="itemName" class="form-label">名稱</label>
                            <input type="text" class="form-control" name="name" id="itemName" autocomplete="off">
                        </div>
                        <div class="mb-3 col-md-6 dropdown" id="categoryContainer" style="display: none;">
                            <label for="multiDropdown" class="form-label">分類</label>
                            <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="multiDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">請選擇分類
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="multiDropdown" id="category">
                                <li><input class="form-check-input" type="checkbox" value="" id="option1"><label
                                        class="form-check-label" for="option1">蔬菜</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option3"><label
                                        class="form-check-label" for="option3">水果</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option6"><label
                                        class="form-check-label" for="option6">海鮮</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option8"><label
                                        class="form-check-label" for="option8">肉類</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option9"><label
                                        class="form-check-label" for="option9">豆類</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option10"><label
                                        class="form-check-label" for="option10">乳製品</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option11"><label
                                        class="form-check-label" for="option11">蛋類</label></li>
                                <li><input class="form-check-input" type="checkbox" value="" id="option12"><label
                                        class="form-check-label" for="option12">其他</label></li>
                            </ul>
                        </div>
                        <div class="mb-3 col-md-6" id="orderContainer">
                            <label for="keep" class="form-label">排序依據</label>
                            <select class="form-select" id="keep" name="order">
                                <option>由近而遠(到期日)</option>
                                <option>由遠而近(到期日)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" id="queryBtn">
                        <button type="submit" class="btn btn-secondary">查詢</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>