document.getElementById("addItemForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    console.log("📡 سيتم إرسال البيانات إلى insert_item.php ...");
    fetch("insert_item.php", {
      method: "POST",
      body: formData
    })
    .then(res => {
      console.log("🔁 تم الاتصال بالسيرفر. Status:", res.status);
      return res.text();
    })
    .then(text => {
      console.log("📥 رد السيرفر:", text);
      if (text.includes("success")) {
        const msg = document.getElementById("success-msg");
        msg.textContent = "✅ تم حفظ الصنف بنجاح.";
        msg.classList.remove("d-none");
        form.reset();
      } else {
        alert("❌ لم يتم الحفظ. الرد: " + text);
      }
    })
    .catch(error => {
      console.error("❌ فشل الاتصال بـ insert_item.php:", error);
    });
  });