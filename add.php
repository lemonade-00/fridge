<?php
include("db_conn.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script
        src="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/locales/bootstrap-datepicker.zh-CN.min.js"></script>
    <link href="https://www.itxst.com/package/bootstrap-datepicker-1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
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
        //function InsertContent(){
        //document.getElementById("itemName").value = document.getElementById("itemName").value;
        //document.getElementById("keep").value = document.getElementById("keep").value;
        //document.getElementById("category").value = document.getElementById("category").value;
        //document.getElementById("typeNumber").value = document.getElementById("typeNumber").value;
        //document.getElementById("datepicker").value = document.getElementById("datepicker").value;
        //document.getElementById("mfrom").action = "insert.php";//fridge_addsave.php
        //document.getElementById("mfrom").submit();
        //}
        $(document).ready(function () {
            $('.nav-link').click(function () {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
            });
        });
        $(document).ready(function () {
            let now = new Date();
            let nowDate = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();
            $('#datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: nowDate
            });
        });
        $(document).on('submit', 'form', function (event) {
            event.preventDefault();
            let selectedDate = $("#datepicker").datepicker("getDate");
            let formattedDate;
            let now = new Date();
            if (itemName === null) {
                alert('名稱為必填欄位');
                event.preventDefault();
            } else if (selectedDate === null) {
                let category = $('#category').val();
                let keep = $('#keep').val();
                let selectedYear = now.getFullYear();
                let selectedMonth = now.getMonth() + 1;
                let selectedDay = now.getDate();
                if (category === "蔬菜") {
                    if (keep === "冷藏") {
                        selectedDay += 2;
                    } else if (keep === "冷凍") {
                        selectedYear += 1;
                    }
                } else if (category === "水果") {
                    if (keep === "冷藏") {
                        selectedDay += 7;
                    } else if (keep === "冷凍") {
                        selectedYear += 1;
                    }
                } else if (category === "海鮮") {
                    if (keep === "冷藏") {
                        selectedDay += 3;
                    } else if (keep === "冷凍") {
                        selectedMonth += 4;
                    }
                } else if (category === "肉類") {
                    if (keep === "冷藏") {
                        selectedDay += 3;
                    } else if (keep === "冷凍") {
                        selectedMonth += 3;
                    }
                } else if (category === "豆類") {
                    if (keep === "冷藏") {
                        selectedDay += 3;
                    } else if (keep === "冷凍") {
                        selectedMonth += 3;
                    }
                } else if (category === "乳製品") {
                    if (keep === "冷藏") {
                        selectedDay += 7;
                    } else if (keep === "冷凍") {
                        selectedMonth += 6;
                    }
                } else if (category === "蛋類") {
                    if (keep === "冷藏") {
                        selectedMonth += 1;
                    } else if (keep === "冷凍") {
                        selectedMonth += 3;
                    }
                } else if (category === "其他") {
                    if (keep === "冷藏") {
                        selectedYear += 1;
                    } else if (keep === "冷凍") {
                        selectedYear += 3;
                    }
                }
                let futureDate = new Date(selectedYear, selectedMonth - 1, selectedDay);
                selectedYear = futureDate.getFullYear();
                electedMonth = futureDate.getMonth() + 1;
                selectedDay = futureDate.getDate();
                formattedDate = `${selectedYear}-${selectedMonth + 1}-${selectedDay}`;
                document.getElementById("datepicker").value = formattedDate;
                
                alert(formattedDate);

            }
            else {
                let selectedYear = selectedDate.getFullYear();
                let selectedMonth = selectedDate.getMonth();
                let selectedDay = selectedDate.getDate();
                formattedDate = `${selectedYear}-${selectedMonth + 1}-${selectedDay}`;
                
                
            } 
            document.getElementById("mfrom").action = "insert.php";//fridge_addsave.php
            document.getElementById("mfrom").submit();
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
                    <a href="searchfront.php" class="nav-link text-white ps-4 fs-5">
                        <i class="bi bi-search"></i>&nbsp;&nbsp;查詢
                    </a>
                </li>
                <li>
                    <a href="add.php" class="nav-link text-white active ps-4 fs-5">
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
        <form class="d-flex flex-column w-50 m-5 fs-5" id="mfrom" method="post" action="insert.php">
            <div class="form-group row m-4">
                <label for="itemName" class="col-md-2 col-form-label">名稱</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="itemName" name="itemName" autocomplete="off"
                        required="required">
                </div>
            </div>
            <div class="form-group row m-4">
                <label for="keep" class="col-md-2 col-form-label">保存</label>
                <div class="col-md-8">
                    <select class="form-select" id="keep" name="keep">
                        <option>冷藏</option>
                        <option>冷凍</option>
                    </select>
                </div>
            </div>
            <div class="form-group row m-4">
                <label for="category" class="col-md-2 col-form-label">分類</label>
                <div class="col-md-8">
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
            </div>
            <div class="form-group row m-4">
                <label class="col-md-2 col-form-label" for="typeNumber">數量</label>
                <div class="col-md-8">
                    <input type="number" value="1" min="1" max="100" id="typeNumber" name="typeNumber"
                        class="form-control" />
                </div>
            </div>
            <div class="form-group row m-4">
                <label for="category" class="col-md-2 col-form-label">到期日</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="datepicker" name="datepicker" autocomplete="off">
                </div>
            </div>
            <div class="m-4 row col-md-10 justify-content-end">
                <button type="submit" class="w-25 butt">新增</button>
            </div>
        </form>
</body>


</html>