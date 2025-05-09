
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إخراج صنف بالكاميرا</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 15px;
      background: #f4f4f4;
    }
    h2 {
      text-align: center;
      color: #004d40;
      font-size: 24px;
    }
    #reader {
      width: 100%;
      max-width: 480px;
      margin: 0 auto;
    }
    #result, #item-info {
      text-align: center;
      margin-top: 20px;
    }
    #item-info {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px #ccc;
      max-width: 90%;
      margin: 20px auto;
    }
    .label {
      font-weight: bold;
      color: #333;
    }
    button, select {
      padding: 12px;
      font-size: 18px;
      margin-top: 10px;
      width: 100%;
      max-width: 300px;
      border-radius: 8px;
    }
    button {
      background-color: #00796b;
      color: white;
      border: none;
    }
    button:hover {
      background-color: #00695c;
    }
  </style>
</head>
<body>

<h2>إخراج صنف باستخدام الكاميرا</h2>
<div style="text-align:center;">
  <label for="cameraSelect">📷 اختر الكاميرا:</label><br>
  <select id="cameraSelect"></select>
</div>
<div id="reader"></div>
<div id="result">يرجى مسح الباركود...</div>

<div id="item-info" style="display:none;">
  <div><span class="label">الاسم:</span> <span id="name"></span></div>
  <div><span class="label">الموديل:</span> <span id="model"></span></div>
  <div><span class="label">السعر:</span> <span id="price"></span> د.أ</div>
  <div><span class="label">الكمية:</span> <span id="quantity"></span></div>
  <div><span class="label">الموقع:</span> <span id="location_id"></span></div>
  <form method="POST" action="confirm_remove.php">
    <input type="hidden" name="item_id" id="item_id">
    <label for="remove_quantity" class="label">اختر الكمية المراد إخراجها:</label>
    <select name="remove_quantity" id="remove_quantity"></select>
    <button type="submit">تأكيد إخراج الصنف</button>
  </form>
  <button onclick="resetScan()">إعادة المسح</button>
</div>

<audio id="beep" src="https://cdn.pixabay.com/audio/2022/03/15/audio_d420eab5b2.mp3" preload="auto"></audio>

<script>
  const html5QrCode = new Html5Qrcode("reader");
  let currentCameraId = null;
  let scannerRunning = false;
  let scanned = false;

  function onScanSuccess(decodedText, decodedResult) {
    if (scanned) return;
    scanned = true;
    document.getElementById('beep').play();
    document.getElementById('result').innerText = "تم قراءة الباركود: " + decodedText;

    fetch('https://www.shneler.com/qr_ajouri/api/get_item_by_barcode.php?barcode=' + encodeURIComponent(decodedText))
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          const item = data.item;
          document.getElementById('item-info').style.display = 'block';
          document.getElementById('name').innerText = item.name;
          document.getElementById('model').innerText = item.model;
          document.getElementById('price').innerText = item.price;
          document.getElementById('quantity').innerText = item.quantity;
          document.getElementById('location_id').innerText = item.location_id;
          document.getElementById('item_id').value = item.id;

          const qtySelect = document.getElementById('remove_quantity');
          qtySelect.innerHTML = '';
          for (let i = 1; i <= item.quantity; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            qtySelect.appendChild(option);
          }
        } else {
          alert(data.message);
          resetScan();
        }
      })
      .catch(error => {
        alert("فشل في جلب البيانات.");
        resetScan();
      });
  }

  function startScanner(cameraId) {
    currentCameraId = cameraId;
    const config = {
      fps: 10,
      qrbox: { width: 250, height: 250 },
      formatsToSupport: [
        Html5QrcodeSupportedFormats.QR_CODE
      ]
    };
    html5QrCode.start(cameraId, config, onScanSuccess)
      .then(() => scannerRunning = true)
      .catch(err => alert("تعذر تشغيل الكاميرا: " + err));
  }

  function switchCamera(newCameraId) {
    if (scannerRunning) {
      html5QrCode.stop().then(() => {
        scannerRunning = false;
        startScanner(newCameraId);
      }).catch(err => {
        alert("تعذر التبديل إلى الكاميرا الجديدة: " + err);
      });
    } else {
      startScanner(newCameraId);
    }
  }

  function resetScan() {
    scanned = false;
    document.getElementById('item-info').style.display = 'none';
    document.getElementById('result').innerText = 'يرجى مسح الباركود...';
  }

  Html5Qrcode.getCameras().then(devices => {
    const select = document.getElementById('cameraSelect');
    devices.forEach(device => {
      const option = document.createElement('option');
      option.value = device.id;
      option.text = device.label || `Camera ${select.length + 1}`;
      select.appendChild(option);
    });

    if (devices.length > 0) {
      select.value = devices[0].id;
      startScanner(devices[0].id);
    }

    select.addEventListener('change', () => {
      const selectedId = select.value;
      if (selectedId !== currentCameraId) {
        switchCamera(selectedId);
      }
    });
  }).catch(err => {
    alert("تعذر الوصول إلى الكاميرا: " + err);
  });
</script>

</body>
</html>
