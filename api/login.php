<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if ($username === '' || $password === '') {
  echo json_encode(['success' => false, 'message' => 'يرجى إدخال اسم المستخدم وكلمة المرور']);
  exit;
}

// ❌ إزالة هذا التحقق:
// if (!ctype_digit($username) || !ctype_digit($password)) {
//   echo json_encode(['success' => false, 'message' => 'اسم المستخدم وكلمة المرور يجب أن يكونا أرقام فقط']);
//   exit;
// }

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->execute([$username, $password]);
$user = $stmt->fetch();

if ($user) {
  echo json_encode([
    'success' => true,
    'message' => 'تم تسجيل الدخول بنجاح',
    'user' => [
      'id' => $user['id'],
      'username' => $user['username'],
      'role' => $user['role']
    ]
  ]);
} else {
  echo json_encode(['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
}
?>