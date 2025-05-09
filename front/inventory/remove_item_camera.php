
<?php
// FILE: remove_item_camera.php (تم التعديل لإضافة دعم الكاميرا لمسح الباركود)
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إخراج صنف - باستخدام الكاميرا</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <style>
    body { font-family: 'Cairo', sans-serif; text-align: center; padding: 20px; direction: rtl; }
    #reader { width: 300px; margin: auto; }
    #result { margin-top: 20px; font-size: 20px; color: green; }
    input[type=text] { padding: 10px; width: 300px; font-size: 18px; margin-top: 20px; }
    button { padding: 10px 20px; font-size: 18px; }
  </style>
</head>
<body>
  <h2>إخراج صنف باستخدام الكاميرا</h2>
  <div id="reader"></div>
  <div id="result">...في انتظار مسح الباركود</div>

  <form action="remove_item_by_barcode.php" method="GET">
    <input type="text" name="barcode" id="barcode" placeholder="باركود الصنف" readonly required>
    <br><br>
    <button type="submit">بحث وإخراج</button>
  </form>

  <script>
    function onScanSuccess(decodedText, decodedResult) {
      document.getElementById('barcode').value = decodedText;
      document.getElementById('result').innerText = "تم قراءة الباركود: " + decodedText;
      setTimeout(() => document.forms[0].submit(), 1000); // إرسال النموذج تلقائيًا بعد ثانية
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", { fps: 10, qrbox: 250 }, false);
    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>
</html>
