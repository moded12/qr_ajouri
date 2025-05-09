
<?php
// FILE: front/inventory/index.php
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>نظام إدارة المخزون - محمد العجوري</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="assets/styles.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      background-color: #f8f9fa;
    }

    .navbar {
      background-color: #004D40;
    }

    .navbar-brand {
      color: white;
      font-weight: bold;
    }

    .navbar-brand:hover {
      color: #ddd;
    }

    .sidebar-fixed {
      background-color: #004D40;
      min-height: 100vh;
      color: white;
      padding-top: 20px;
    }

    .sidebar-fixed a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px 20px;
    }

    .sidebar-fixed a:hover,
    .sidebar-fixed a.active {
      background-color: #00695c;
    }

    .offcanvas-body a {
      display: block;
      padding: 10px 15px;
      color: #004D40;
      text-decoration: none;
    }

    .offcanvas-body a:hover,
    .offcanvas-body a.active {
      background-color: #e0f2f1;
      border-radius: 6px;
    }

    #main-content {
      padding: 20px;
    }

    #page-title {
      font-weight: bold;
      font-size: 20px;
      margin-bottom: 15px;
      color: #004D40;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top d-md-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand mx-auto" href="#">📦 نظام إدارة المخزون - محمد العجوري</a>
  </div>
</nav>

<!-- Offcanvas Sidebar (for mobile) -->
<div class="offcanvas offcanvas-start text-bg-light d-md-none" tabindex="-1" id="sidebarMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">القائمة الرئيسية</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <a href="#" class="menu-link active" data-page="list_items.php">📋 عرض الأصناف</a>
    <a href="#" class="menu-link" data-page="add_item.php">➕ إضافة صنف</a>
    <a href="#" class="menu-link" data-page="remove_item.php">➖ إخراج صنف</a>
    <a href="manual_remove.php" class="menu-link">📤 إخراج يدويًا</a>
      <a href="remove_item_smart.php" class="menu-link">📷 إخراج بالكاميرا</a>
    <a href="#" class="menu-link" data-page="logs.php">🕘 سجل الحركات</a>
    <a href="#" class="menu-link" data-page="search.php">🔍 البحث</a>
    <a href="#" class="menu-link" data-page="alerts.php">🔔 التنبيهات</a>
    <a href="#" class="menu-link" data-page="print_list.php">🖨️ طباعة القائمة</a>
    <a href="#" class="menu-link" data-page="notify_settings.php">⚙️ الإعدادات</a>
  </div>
</div>

<div class="container-fluid" style="margin-top: 60px;">
  <div class="row">
    <!-- Sidebar (for desktop) -->
    <div class="col-md-3 col-lg-2 d-none d-md-block sidebar-fixed">
      <h5 class="text-center mb-4">📦 نظام إدارة المخزون</h5>
      <a href="#" class="menu-link active" data-page="list_items.php">📋 عرض الأصناف</a>
      <a href="#" class="menu-link" data-page="add_item.php">➕ إضافة صنف</a>
      <a href="#" class="menu-link" data-page="remove_item.php">➖ إخراج صنف</a>
      <a href="manual_remove.php" class="menu-link">📤 إخراج يدويًا</a>
      <a href="remove_item_smart.php" class="menu-link">📷 إخراج بالكاميرا</a>
      <a href="#" class="menu-link" data-page="logs.php">🕘 سجل الحركات</a>
      <a href="#" class="menu-link" data-page="search.php">🔍 البحث</a>
      <a href="#" class="menu-link" data-page="alerts.php">🔔 التنبيهات</a>
      <a href="#" class="menu-link" data-page="print_list.php">🖨️ طباعة القائمة</a>
      <a href="#" class="menu-link" data-page="notify_settings.php">⚙️ الإعدادات</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10">
      <div id="page-title">📋 عرض الأصناف</div>
      <div id="main-content">جارٍ التحميل...</div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function loadPage(page, title) {
    fetch(page)
      .then(response => response.text())
      .then(html => {
        document.getElementById("main-content").innerHTML = html;
        if (page.startsWith("add_item.php")) {
          const script = document.createElement("script");
          script.src = "assets/add_item.js?v=" + Date.now();
          document.body.appendChild(script);
        }
        document.getElementById("page-title").innerText = title;
      });
  }

  function activateMenu(link) {
    document.querySelectorAll(".menu-link").forEach(l => l.classList.remove("active"));
    link.classList.add("active");
  }

  document.querySelectorAll(".menu-link").forEach(link => {
    link.addEventListener("click", function(e) {
      const page = this.getAttribute("data-page");
      if (page) {
        e.preventDefault();
        const title = this.innerText;
        activateMenu(this);
        loadPage(page, title);
        const sidebar = bootstrap.Offcanvas.getInstance(document.getElementById("sidebarMenu"));
        if (sidebar) sidebar.hide();
      }
    });
  });

  window.addEventListener('DOMContentLoaded', () => {
    loadPage('list_items.php', '📋 عرض الأصناف');
  });
</script>

</body>
</html>
