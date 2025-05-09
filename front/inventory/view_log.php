<?php
// FILE: front/inventory/view_log.php
$logPath = __DIR__ . '/log.txt';

if (!file_exists($logPath)) {
  die("⚠️ الملف log.txt غير موجود.");
}

$log = file_get_contents($logPath);
echo "<pre style='direction: ltr; background:#f0f0f0; padding:20px;'>".htmlspecialchars($log)."</pre>";
?>
