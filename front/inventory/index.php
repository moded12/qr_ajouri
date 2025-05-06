<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// تأكد من أنك قد قمت بإعداد اتصال قاعدة البيانات بشكل صحيح
require_once '../db.php'; // تعديل المسار إذا كان مختلفًا
header('Content-Type: text/html; charset=utf-8');

// استعلام جلب الأصناف من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM items");
$items = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض الأصناف</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <div class="header-wrapper">
        <div class="page-header">
            <h2>عرض الأصناف</h2>
            <button class="print-button" onclick="window.print();">طباعة</button>
        </div>
    </div>

    <div class="filters">
        <input type="text" id="search" class="form-control" placeholder="بحث عن صنف" onkeyup="searchItems()" />
    </div>

    <table id="itemsTable">
        <thead>
            <tr>
                <th>اسم الصنف</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>العملية</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?> د.أ</td>
                    <td class="actions">
                        <a href="edit_item.php?id=<?php echo $item['id']; ?>" class="edit"><i class="fas fa-edit"></i> تعديل</a>
                        <a href="delete_item.php?id=<?php echo $item['id']; ?>" class="delete"><i class="fas fa-trash-alt"></i> حذف</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // فلترة الأصناف باستخدام البحث
        function searchItems() {
            let input = document.getElementById('search');
            let filter = input.value.toUpperCase();
            let table = document.getElementById('itemsTable');
            let tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td')[0]; // البحث في عمود "اسم الصنف"
                if (td) {
                    let txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</body>
</html>