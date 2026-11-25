/* TOP 50 candidacy form */
(() => {
  const A = window.AGA || {}, form = document.querySelector("[data-apply]");
  if (!form) return;
  const msg = form.querySelector(".ap-msg"), btn = form.querySelector("[type=submit]");
  const mb = b => Math.round(b / 1048576) + "MB";
  const max = form.querySelector("[data-max]"); if (max) max.textContent = mb(A.maxVideo || 0);
  form.querySelectorAll("[data-n]").forEach(n => { const t = form.elements[n.dataset.n]; t.addEventListener("input", () => (n.textContent = t.value.length + "/600")); });
  const fail = (text, el) => { msg.textContent = text; msg.hidden = false; if (el) { el.focus(); el.scrollIntoView({ behavior: "smooth", block: "center" }); } };
  form.addEventListener("submit", async e => {
    e.preventDefault(); msg.hidden = true;
    const bad = [...form.querySelectorAll("[required]")].find(el => !el.checkValidity());
    if (bad) return fail("Bạn điền đủ các mục bắt buộc nhé.", bad);
    if (!form.querySelector("[name='spirits[]']:checked")) return fail("Chọn ít nhất một tinh thần MOVE nhé.", form.querySelector("[name='spirits[]']"));
    const vf = form.elements.video_file.files[0];
    if (!form.elements.video.value.trim() && !vf) return fail("Bạn gửi video bằng link hoặc tải file lên nhé.", form.elements.video);
    if (vf && vf.size > A.maxVideo) return fail("File video lớn hơn " + mb(A.maxVideo) + ". Bạn gửi link video thay thế nhé.", form.elements.video);
    btn.disabled = true; btn.textContent = vf ? "Đang tải video lên…" : "Đang gửi…";
    const fd = new FormData(form); fd.append("action", "agm_apply"); fd.append("nonce", A.nonce);
    try {
      const r = await fetch(A.ajax, { method: "POST", body: fd }).then(r => r.json());
      if (r.success) {
        form.hidden = true;
        const done = document.querySelector(".ap-done"); done.hidden = false;
        done.querySelector(".ap-code").innerHTML = "Mã hồ sơ: <b></b>"; done.querySelector(".ap-code b").textContent = r.data.code;
        done.scrollIntoView({ behavior: "smooth", block: "start" });
      } else fail(r.data && r.data.message || "Chưa gửi được, bạn thử lại nhé.", r.data && r.data.field ? form.elements[r.data.field] : null);
    } catch { fail("Chưa gửi được, bạn kiểm tra mạng rồi thử lại nhé."); }
    btn.disabled = false; btn.textContent = "Nộp hồ sơ";
  });
})();
