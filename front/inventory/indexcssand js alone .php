<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نظام إدارة المخزون</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

    <div class="header">
        <button class="toggle-button d-md-none" aria-label="Toggle Sidebar">☰</button>
        <div class="title">
            <a href="https://www.shneler.com/qr_ajouri/front/inventory/index.php">📦 نظام إدارة المخزون</a>
        </div>
        <button class="dark-mode-toggle" aria-label="Toggle Dark Mode"><i class="fas fa-moon"></i></button>
    </div>

    <div class="sidebar">
        <div class="sidebar-header d-none d-md-flex">
             <h4>القائمة الرئيسية</h4>
        </div>
        <a href="#" data-page="add_item.php"><i class="fas fa-plus"></i> إضافة صنف</a>
        <a href="#" data-page="remove_item.php"><i class="fas fa-minus"></i> إخراج صنف</a>
        <a href="#" data-page="list_items.php"><i class="fas fa-list"></i> عرض الأصناف</a>
        <a href="#" data-page="search.php"><i class="fas fa-search"></i> البحث</a>
        <a href="#" data-page="logs.php"><i class="fas fa-file-alt"></i> سجل الحركات</a>
        <a href="#" data-page="print_list.php"><i class="fas fa-print"></i> طباعة</a>
        <a href="#" data-page="set_thresholds.php"><i class="fas fa-cogs"></i> حدود التنبيه</a>
        <a href="#" data-page="notify_settings.php"><i class="fas fa-bell"></i> إعدادات التنبيهات</a>
        <a href="/qr_ajouri/accounting/index.php"><i class="fas fa-coins"></i> قسم المحاسبة</a>
    </div>

    <div class="content">
        <iframe src="list_items.php" id="mainFrame"></iframe>
    </div>

    <button id="backToTop" aria-label="Back to Top"><i class="fas fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts.js"></script>

</body>
</html>