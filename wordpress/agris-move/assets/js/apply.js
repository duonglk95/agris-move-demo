/* TOP 50 candidacy form – one step at a time */
(() => {
  const A = window.AGA || {}, form = document.querySelector("[data-apply]");
  if (!form) return;
  const steps = [...form.querySelectorAll(":scope > fieldset.pc-step")], bar = [...document.querySelectorAll(".ap-progress li")];
  const msg = form.querySelector(".ap-msg"), prev = form.querySelector("[data-prev]"), next = form.querySelector("[data-next]"), send = form.querySelector("[data-send]");
  const mb = b => Math.round(b / 1048576) + "MB";
  const max = form.querySelector("[data-max]"); if (max) max.textContent = mb(A.maxVideo || 0);
  form.querySelectorAll("[data-n]").forEach(n => { const t = form.elements[n.dataset.n]; t.addEventListener("input", () => (n.textContent = t.value.length + "/600")); });
  let cur = 0;
  const fail = (text, el) => { msg.textContent = text; msg.hidden = false; if (el) el.focus({ preventScroll: true }); return false; };
  const check = i => {
    msg.hidden = true;
    const s = steps[i], bad = [...s.querySelectorAll("[required]")].find(el => el.name !== "consent" && !el.checkValidity());
    if (bad) return fail(bad.type === "email" && bad.value ? "Email chưa đúng định dạng." : "Bạn điền đủ các mục ở bước này nhé.", bad);
    if (i === 1 && !form.querySelector("[name='spirits[]']:checked")) return fail("Chọn ít nhất một tinh thần MOVE nhé.");
    if (i === 2) {
      const vf = form.elements.video_file.files[0];
      if (!form.elements.video.value.trim() && !vf) return fail("Bạn gửi video bằng link hoặc tải file lên nhé.", form.elements.video);
      if (vf && vf.size > A.maxVideo) return fail("File video lớn hơn " + mb(A.maxVideo) + ". Bạn gửi link video thay thế nhé.", form.elements.video);
    }
    return true;
  };
  const summary = () => {
    const v = n => (form.elements[n] && form.elements[n].value || "").trim();
    const rows = [["Ứng viên", v("name") + " · " + v("msnv")], ["Đơn vị", v("dept") + " · " + v("center")], ["Email", v("email")], ["Hình thức", (form.querySelector("[name=via]:checked") || {}).value || ""],
      ["Câu chuyện", v("title")], ["Tinh thần", [...form.querySelectorAll("[name='spirits[]']:checked")].map(x => x.value).join(", ")],
      ["Video", v("video") || (form.elements.video_file.files[0] || {}).name || ""], ["Ảnh", (form.elements.photo.files[0] || {}).name || "Không có"]];
    const dl = form.querySelector(".ap-sum"); dl.innerHTML = "";
    rows.forEach(([k, val]) => { const dt = document.createElement("dt"), dd = document.createElement("dd"); dt.textContent = k; dd.textContent = val; dl.append(dt, dd); });
  };
  const show = i => {
    cur = i;
    steps.forEach((s, k) => { s.hidden = k !== i; });
    bar.forEach((b, k) => { b.classList.toggle("on", k <= i); b.classList.toggle("now", k === i); });
    prev.hidden = i === 0; next.hidden = i === steps.length - 1; send.hidden = i !== steps.length - 1;
    if (i === steps.length - 1) summary();
    const top = document.querySelector(".ap-progress").getBoundingClientRect().top + scrollY - 100;
    if (scrollY > top) scrollTo({ top, behavior: "smooth" });
    const first = steps[i].querySelector("input:not([type=hidden]),textarea,select"); if (first && i) setTimeout(() => first.focus({ preventScroll: true }), 50);
  };
  next.addEventListener("click", () => { if (check(cur)) show(cur + 1); });
  prev.addEventListener("click", () => { msg.hidden = true; show(cur - 1); });
  bar.forEach((b, k) => b.addEventListener("click", () => { if (k < cur) show(k); }));
  form.addEventListener("keydown", e => { if (e.key === "Enter" && e.target.tagName === "INPUT" && cur < steps.length - 1) { e.preventDefault(); next.click(); } });
  form.addEventListener("submit", async e => {
    e.preventDefault(); msg.hidden = true;
    for (let i = 0; i < steps.length - 1; i++) if (!check(i)) return show(i), check(i);
    if (!form.elements.consent.checked) return fail("Bạn đánh dấu đồng ý trước khi nộp nhé.", form.elements.consent);
    const vf = form.elements.video_file.files[0];
    send.disabled = true; send.textContent = vf ? "Đang tải video lên…" : "Đang gửi…";
    const fd = new FormData(form); fd.append("action", "agm_apply"); fd.append("nonce", A.nonce);
    try {
      const r = await fetch(A.ajax, { method: "POST", body: fd }).then(r => r.json());
      if (r.success) {
        form.hidden = true; document.querySelector(".ap-progress").hidden = true;
        const done = document.querySelector(".ap-done"); done.hidden = false;
        done.querySelector(".ap-code").innerHTML = "Mã hồ sơ: <b></b>"; done.querySelector(".ap-code b").textContent = r.data.code;
        done.scrollIntoView({ behavior: "smooth", block: "center" });
      } else {
        const f = r.data && r.data.field;
        if (f === "msnv") show(0); else if (f === "video") show(2);
        fail(r.data && r.data.message || "Chưa gửi được, bạn thử lại nhé.", f ? form.elements[f] : null);
      }
    } catch { fail("Chưa gửi được, bạn kiểm tra mạng rồi thử lại nhé."); }
    send.disabled = false; send.textContent = "Nộp hồ sơ";
  });
  show(0);
})();
