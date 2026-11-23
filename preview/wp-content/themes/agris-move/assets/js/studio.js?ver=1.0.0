/* Event studio: contest frame, postcard and avatar, all drawn on canvas in the browser. */
(() => {
  const S = window.AGS || {}, G = "#0b5d34", G2 = "#007a3d", LIME = "#b6e34a", INK = "#0f2b4c";
  const FONT = '"Montserrat", "Be Vietnam Pro", system-ui, sans-serif', BODY = '"Be Vietnam Pro", system-ui, sans-serif';
  const loadImg = src => new Promise((ok, no) => { const i = new Image(); i.crossOrigin = "anonymous"; i.onload = () => ok(i); i.onerror = no; i.src = src; });
  const fileImg = f => new Promise((ok, no) => { const r = new FileReader(); r.onload = () => loadImg(r.result).then(ok, no); r.onerror = no; r.readAsDataURL(f); });
  const cover = (c, img, x, y, w, h, zoom = 1, ox = 0, oy = 0) => {
    const s = Math.max(w / img.width, h / img.height) * zoom, iw = img.width * s, ih = img.height * s;
    c.save(); c.beginPath(); c.rect(x, y, w, h); c.clip();
    c.drawImage(img, x + (w - iw) / 2 + ox, y + (h - ih) / 2 + oy, iw, ih); c.restore();
  };
  const wrap = (c, text, x, y, maxW, lh, maxLines = 99) => {
    const words = String(text).split(/\s+/); let line = "", n = 0;
    for (const w of words) { const t = line ? line + " " + w : w; if (c.measureText(t).width > maxW && line) { c.fillText(line, x, y); y += lh; line = w; if (++n >= maxLines - 1) { line = words.slice(words.indexOf(w)).join(" "); break; } } else line = t; }
    if (line) { while (c.measureText(line).width > maxW && line.length > 3) line = line.slice(0, -2) + "…"; c.fillText(line, x, y); }
    return y + lh;
  };
  const placeholder = (c, x, y, w, h, label) => { c.fillStyle = "#d9ecdf"; c.fillRect(x, y, w, h); c.fillStyle = "#5f7a72"; c.font = `600 36px ${BODY}`; c.textAlign = "center"; c.fillText(label, x + w / 2, y + h / 2); c.textAlign = "left"; };
  const download = (cv, name, type = "image/jpeg") => cv.toBlob(b => { const a = document.createElement("a"); a.href = URL.createObjectURL(b); a.download = name; a.click(); setTimeout(() => URL.revokeObjectURL(a.href), 3000); }, type, .92);
  let logo = null; if (S.logo) loadImg(S.logo).then(i => { logo = i; document.dispatchEvent(new Event("agm-logo")); }).catch(() => {});

  /* ---------- contest frame (1080×1350) ---------- */
  const ctForm = document.querySelector("[data-contest]");
  if (ctForm) {
    const cv = document.getElementById("ctCanvas"), c = cv.getContext("2d"); let photo = null, overlay = null;
    if (S.frameCt) loadImg(S.frameCt).then(i => { overlay = i; draw(); }).catch(() => {});
    const val = n => (ctForm.elements[n].value || "").trim();
    const draw = () => {
      const W = 1080, H = 1350, B = 34, TOP = 120, BOT = 250;
      if (overlay) {
        c.fillStyle = "#fff"; c.fillRect(0, 0, W, H);
        photo ? cover(c, photo, 14, 14, W - 28, H - 28 - 170) : placeholder(c, 14, 14, W - 28, H - 28 - 170, "Ảnh của bạn");
        c.drawImage(overlay, 0, 0, W, H);
        c.fillStyle = "#fff"; c.font = `800 40px ${FONT}`; wrap(c, val("title") || "Tên ảnh", 52, H - 128, W - 104, 46, 1);
        c.font = `500 25px ${BODY}`; c.fillStyle = "rgba(255,255,255,.92)";
        c.fillText([val("author") || "Tên bạn", val("place") || "Địa điểm", val("center") || "Đơn vị"].join("  ·  "), 52, H - 86);
        c.fillStyle = "#e9ff9e"; c.font = `700 24px ${BODY}`; c.fillText(S.tags || "", 52, H - 50);
        return;
      }
      c.fillStyle = G; c.fillRect(0, 0, W, H);
      photo ? cover(c, photo, B, TOP, W - 2 * B, H - TOP - BOT) : placeholder(c, B, TOP, W - 2 * B, H - TOP - BOT, "Ảnh của bạn");
      c.fillStyle = "#fff"; c.font = `800 46px ${FONT}`; c.fillText("AgriS", B + 6, 78);
      c.fillStyle = LIME; c.font = `italic 800 40px ${FONT}`; c.textAlign = "right"; c.fillText("PITSTOP", W - B - 6, 76);
      c.fillStyle = "rgba(255,255,255,.7)"; c.font = `600 22px ${BODY}`; c.fillText(S.event || "", W - B - 6, 104); c.textAlign = "left";
      let y = H - BOT + 62;
      c.fillStyle = "#fff"; c.font = `800 44px ${FONT}`; y = wrap(c, val("title") || "Tên ảnh", B + 6, y, W - 2 * B - 12, 52, 1);
      c.font = `500 28px ${BODY}`; c.fillStyle = "rgba(255,255,255,.88)";
      c.fillText([val("author") || "Tên bạn", val("place") || "Địa điểm", val("center") || "Đơn vị"].join("  ·  "), B + 6, y + 2);
      c.fillStyle = LIME; c.font = `700 28px ${BODY}`; c.fillText(S.tags || "", B + 6, y + 56);
    };
    ctForm.addEventListener("input", draw); document.addEventListener("agm-logo", draw);
    ctForm.elements.photo.addEventListener("change", async e => { const f = e.target.files[0]; photo = f ? await fileImg(f).catch(() => null) : null; draw(); });
    draw();
    const msg = ctForm.querySelector(".tk-msg"), sub = ctForm.querySelector("[type=submit]");
    const rawBlob = () => new Promise(ok => { const m = 1600, s = Math.min(1, m / Math.max(photo.width, photo.height)), t = document.createElement("canvas"); t.width = photo.width * s; t.height = photo.height * s; t.getContext("2d").drawImage(photo, 0, 0, t.width, t.height); t.toBlob(ok, "image/jpeg", .9); });
    ctForm.addEventListener("submit", async e => {
      e.preventDefault(); msg.hidden = true;
      if (!photo) { msg.textContent = "Bạn chọn ảnh trước nhé."; msg.hidden = false; return; }
      sub.disabled = true; sub.textContent = "Đang gửi…"; draw();
      const fd = new FormData();
      ["title", "desc", "author", "place", "center"].forEach(n => fd.append(n, val(n)));
      fd.append("action", "agm_contest"); fd.append("nonce", S.nonce); fd.append("page", ctForm.dataset.page);
      fd.append("framed", await new Promise(ok => cv.toBlob(ok, "image/jpeg", .9)), "framed.jpg");
      fd.append("raw", await rawBlob(), "raw.jpg");
      try {
        const r = await fetch(S.ajax, { method: "POST", body: fd }).then(r => r.json());
        if (r.success) {
          document.getElementById("stShareTxt").value = r.data.share;
          const d = document.getElementById("stShare"); d.showModal();
          d.addEventListener("close", () => { location.href = r.data.url; location.reload(); }, { once: true });
        } else { msg.textContent = r.data && r.data.message || "Chưa gửi được, bạn thử lại nhé."; msg.hidden = false; }
      } catch { msg.textContent = "Chưa gửi được, bạn kiểm tra mạng rồi thử lại nhé."; msg.hidden = false; }
      sub.disabled = false; sub.textContent = "Gửi ảnh dự thi";
    });
    document.getElementById("stCopy").addEventListener("click", async () => {
      const t = document.getElementById("stShareTxt"); t.select();
      try { await navigator.clipboard.writeText(t.value); } catch { document.execCommand("copy"); }
      document.getElementById("stCopied").hidden = false;
    });
    const view = document.getElementById("stView");
    document.querySelectorAll(".st-thumb").forEach(b => b.addEventListener("click", () => {
      view.querySelector("img").src = b.dataset.full; view.querySelector("h3").textContent = b.dataset.title; view.querySelector("p").textContent = b.dataset.desc; view.showModal();
    }));
  }

  /* ---------- postcard (1600×1100) ---------- */
  const pcForm = document.querySelector("[data-postcard]");
  if (pcForm) {
    const cv = document.getElementById("pcCanvas"), c = cv.getContext("2d"); let photo = null, credit = "";
    const val = n => (pcForm.elements[n] && pcForm.elements[n].value || "").trim();
    const draw = () => {
      const W = 1600, H = 1100, PW = 960;
      c.fillStyle = "#fbfdf7"; c.fillRect(0, 0, W, H);
      photo ? cover(c, photo, 0, 0, PW, H) : placeholder(c, 0, 0, PW, H, "Chọn một ảnh");
      if (credit) { c.fillStyle = "rgba(0,0,0,.45)"; c.fillRect(0, H - 64, PW, 64); c.fillStyle = "#fff"; c.font = `500 24px ${BODY}`; c.fillText("Ảnh: " + credit, 28, H - 24); }
      const x = PW + 56, w = W - x - 56;
      if (logo) { const lw = 300, lh = lw * logo.height / logo.width; c.drawImage(logo, W - 56 - lw, 56, lw, lh); }
      c.fillStyle = "#5f7a72"; c.font = `600 24px ${BODY}`; c.fillText("Từ", x, 210);
      c.fillStyle = INK; c.font = `800 34px ${FONT}`; wrap(c, val("from") || "một AgriS-er", x + 70, 210, w - 70, 40, 1);
      c.fillStyle = "#5f7a72"; c.font = `600 24px ${BODY}`; c.fillText("Gửi đến", x, 266);
      c.fillStyle = INK; c.font = `800 34px ${FONT}`; wrap(c, val("to") || "Đồng đội", x + 110, 266, w - 110, 40, 1);
      c.strokeStyle = "#d5e5da"; c.lineWidth = 2; c.beginPath(); c.moveTo(x, 310); c.lineTo(W - 56, 310); c.stroke();
      c.fillStyle = "#23423a"; c.font = `italic 500 36px ${BODY}`; wrap(c, val("wish") || "Lời chúc của bạn…", x, 380, w, 54, 10);
      for (let i = 0; i < 24; i++) { c.fillStyle = i % 2 ? "#0b5d34" : "#fff"; c.fillRect(x + i * ((W - 56 - x) / 24), H - 100, (W - 56 - x) / 24, 10); }
      c.fillStyle = G2; c.font = `700 24px ${BODY}`; c.fillText(S.tags || "", x, H - 56);
    };
    const pick = async () => { const r = pcForm.querySelector("[name=photo]:checked"); credit = r ? r.dataset.credit : ""; photo = r ? await loadImg(r.value).catch(() => null) : null; draw(); };
    pcForm.addEventListener("input", e => (e.target.name === "photo" ? pick() : draw()));
    document.addEventListener("agm-logo", draw);
    document.getElementById("pcDown").addEventListener("click", () => download(cv, "buu-thiep-vao-vu.jpg"));
    pick();
  }

  /* ---------- avatar (1080×1080) ---------- */
  const avForm = document.querySelector("[data-avatar]");
  if (avForm) {
    const cv = document.getElementById("avCanvas"), c = cv.getContext("2d"); let photo = null, overlay = null, ox = 0, oy = 0, zoom = 1;
    if (S.frameAv) loadImg(S.frameAv).then(i => { overlay = i; draw(); }).catch(() => {});
    const draw = () => {
      const W = 1080, R = 540, RING = 64;
      c.clearRect(0, 0, W, W); c.save(); c.beginPath(); c.arc(R, R, R, 0, Math.PI * 2); c.clip();
      photo ? cover(c, photo, 0, 0, W, W, zoom, ox, oy) : placeholder(c, 0, 0, W, W, "Ảnh của bạn");
      if (overlay) c.drawImage(overlay, 0, 0, W, W);
      else {
        c.lineWidth = RING; c.strokeStyle = G2; c.beginPath(); c.arc(R, R, R - RING / 2, 0, Math.PI * 2); c.stroke();
        c.lineWidth = 10; c.strokeStyle = LIME; c.beginPath(); c.arc(R, R, R - RING - 4, Math.PI * .15, Math.PI * .85); c.stroke();
        c.fillStyle = G2; c.beginPath(); c.roundRect(R - 230, W - 210, 460, 96, 48); c.fill();
        c.fillStyle = "#fff"; c.font = `800 46px ${FONT}`; c.textAlign = "center"; c.fillText("#MOVE ACTION", R, W - 146);
        c.fillStyle = LIME; c.font = `italic 800 34px ${FONT}`; c.fillText("AgriS PITSTOP", R, 150); c.textAlign = "left";
      }
      c.restore();
    };
    avForm.elements.photo.addEventListener("change", async e => { const f = e.target.files[0]; photo = f ? await fileImg(f).catch(() => null) : null; ox = oy = 0; draw(); });
    avForm.elements.zoom.addEventListener("input", e => { zoom = +e.target.value; draw(); });
    let drag = null;
    cv.addEventListener("pointerdown", e => { drag = [e.clientX, e.clientY, ox, oy]; cv.setPointerCapture(e.pointerId); });
    cv.addEventListener("pointermove", e => { if (!drag) return; const k = cv.width / cv.getBoundingClientRect().width; ox = drag[2] + (e.clientX - drag[0]) * k; oy = drag[3] + (e.clientY - drag[1]) * k; draw(); });
    cv.addEventListener("pointerup", () => (drag = null));
    document.getElementById("avDown").addEventListener("click", () => download(cv, "avatar-vao-vu.png", "image/png"));
    draw();
  }
})();
