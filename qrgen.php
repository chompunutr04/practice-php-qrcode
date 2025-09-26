<?php
// index.php — โชว์ฟอร์มและแสดงผลในหน้าเดียว
// หมายเหตุ: QR ถูกวาดด้วย JavaScript (qrcodejs) จากค่าในฟอร์ม

// รับค่าข้อความ (POST)
$text = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['text'])) {
    $text = trim($_POST['text']);
  }
  // ถ้าอัปโหลดไฟล์ .txt ให้อ่านเนื้อหาแทน
  if (!empty($_FILES['txtfile']['tmp_name'])) {
    $uploadContent = file_get_contents($_FILES['txtfile']['tmp_name']);
    if ($uploadContent !== false && strlen(trim($uploadContent)) > 0) {
      $text = trim($uploadContent);
    }
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>QR ง่ายสุด ๆ (PHP + qrcodejs)</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4">
  <div class="w-full max-w-xl bg-white rounded-2xl p-6 shadow">
    <h1 class="text-xl font-bold">ตัวสร้างคิวอาร์โค้ด (พื้นฐานสุด ๆ)</h1>
    <p class="text-sm text-slate-600 mt-1">พิมพ์ข้อความ หรือเลือกไฟล์ <code>.txt</code> เพื่อแสดง QR</p>

    <form method="post" enctype="multipart/form-data" class="mt-4 space-y-3">
      <div>
        <label class="block text-sm font-medium">พิมพ์ข้อความ</label>
        <textarea name="text" rows="3" class="w-full mt-1 border rounded-lg p-2" placeholder="พิมพ์อะไรก็ได้..."><?= htmlspecialchars($text) ?></textarea>
      </div>
      <div class="flex items-center gap-3">
        <input type="file" name="txtfile" accept=".txt" class="text-sm" />
        <span class="text-xs text-slate-500">(เลือกไฟล์ .txt ได้ ไม่บังคับ)</span>
      </div>
      <div class="flex gap-2">
        <button class="px-4 py-2 rounded-lg bg-black text-white">สร้าง QR</button>
        <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="px-4 py-2 rounded-lg border">ล้าง</a>
      </div>
    </form>

    <div class="mt-5 border rounded-xl p-4 <?php echo $text ? '' : 'hidden'; ?>">
      <div id="qrcode" class="flex items-center justify-center"></div>
      <div class="mt-3 text-xs text-slate-600">ข้อมูลที่เข้ารหัส:</div>
      <pre class="text-xs bg-slate-50 p-2 rounded-lg overflow-x-auto"><?php echo htmlspecialchars($text); ?></pre>
     
    </div>
  </div>

  <script>
    // ถ้ามีข้อความจาก PHP ให้สร้าง QR ทันทีหลังโหลดหน้า (หลังผู้ใช้กดปุ่ม)
    const text = <?php echo json_encode($text); ?>;
    if (text) {
      new QRCode(document.getElementById('qrcode'), {
        text: text,
        width: 220,
        height: 220,
        correctLevel: QRCode.CorrectLevel.M
      });
    }
  </script>
</body>
</html>
