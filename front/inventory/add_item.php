<?php
// FILE: front/inventory/add_item.php (full debug)
require_once '../../api/db.php';
header('Content-Type: text/html; charset=utf-8');

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$units = $pdo->query("SELECT * FROM units")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations")->fetchAll();
?>
<div class="container">
  <div id="success-msg" class="alert alert-success d-none text-center mt-3"></div>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">➕ إضافة صنف جديد</h5>
    </div>
    <div class="card-body">
      <form id="addItemForm" onsubmit="return false;">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">اسم الصنف</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">الموديل</label>
            <input type="text" name="model" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">الكمية</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">الوحدة</label>
            <select name="unit_id" class="form-select" required>
              <option value="">اختر وحدة</option>
              <?php foreach ($units as $unit): ?>
                <option value="<?= $unit['id'] ?>"><?= htmlspecialchars($unit['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">التصنيف</label>
            <select name="category_id" class="form-select" required>
              <option value="">اختر تصنيف</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">الموقع</label>
            <select name="location_id" class="form-select" required>
              <option value="">اختر موقع</option>
              <?php foreach ($locations as $loc): ?>
                <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">الباركود</label>
            <input type="text" name="barcode" class="form-control" placeholder="يتم توليده تلقائيًا إن تُرك فارغًا">
          </div>
          <div class="col-md-6">
            <label class="form-label">السيريال</label>
            <input type="text" name="serial_number" class="form-control" placeholder="يتم توليده تلقائيًا إن تُرك فارغًا">
          </div>
          <div class="col-md-6">
            <label class="form-label">السعر</label>
            <input type="number" step="0.01" name="price" class="form-control">
          </div>
          <div class="col-12">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" rows="2" class="form-control"></textarea>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-success px-5">💾 حفظ الصنف</button>
        </div>
      </form>
    </div>
  </div>
</div>