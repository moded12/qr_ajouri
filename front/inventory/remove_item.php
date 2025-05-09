<?php
// FILE: front/inventory/remove_item.php (JavaScript Redirect to Camera Page)
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إخراج صنف بالكاميرا</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
    // Perform a smooth redirect to the camera page after 2 seconds
    setTimeout(function() {
      window.location.href = '/qr_ajouri/front/inventory/remove_item_camera.php';
    }, 2000);
  </script>
</head>
<body>
  <div style="text-align: center; padding: 50px;">
    <p>يتم توجيهك إلى صفحة إخراج الصنف باستخدام الكاميرا...</p>
    <p>إذا لم يتم التحويل تلقائيًا، <a href="/qr_ajouri/front/inventory/remove_item_camera.php">اضغط هنا</a>.</p>
  </div>
</body>
</html>