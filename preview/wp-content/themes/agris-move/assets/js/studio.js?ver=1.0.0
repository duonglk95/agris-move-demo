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
    const HAND = '"Dancing Script", cursive';
    let stampImg = null;
    if (S.stamps && S.stamps.length) loadImg(S.stamps[Math.floor(Math.random() * S.stamps.length)]).then(i => { stampImg = i; draw(); }).catch(() => {});
    const stamp = (x, y, w, h) => {
      if (!stampImg) return;
      c.save(); c.translate(x + w / 2, y + h / 2); c.rotate(0.03); c.shadowColor = "rgba(0,0,0,.18)"; c.shadowBlur = 12; c.shadowOffsetY = 4;
      c.drawImage(stampImg, -w / 2, -h / 2, w, h); c.restore();
    };
    const postmark = (cx, cy) => {
      const R = 92;
      c.save(); c.globalAlpha = .9;
      c.fillStyle = "rgba(251,248,239,.82)"; c.beginPath(); c.arc(cx, cy, R, 0, 7); c.fill();
      c.strokeStyle = "rgba(11,93,52,.7)"; c.lineWidth = 4; c.beginPath(); c.arc(cx, cy, R, 0, 7); c.stroke();
      c.lineWidth = 2; c.beginPath(); c.arc(cx, cy, R - 12, 0, 7); c.stroke();
      if (logo) { const lw = 132, lh = lw * logo.height / logo.width; c.drawImage(logo, cx - lw / 2, cy - lh / 2 - 12, lw, lh); }
      c.fillStyle = "rgba(11,93,52,.85)"; c.textAlign = "center"; c.font = `800 15px ${FONT}`; c.fillText("VÀO VỤ", cx, cy + 30); c.font = `700 13px ${BODY}`; c.fillText("2026 – 2027", cx, cy + 48);
      c.strokeStyle = "rgba(11,93,52,.45)"; c.lineWidth = 4;
      for (let k = 0; k < 4; k++) { c.beginPath(); for (let t = 0; t <= 230; t += 10) { const px = cx + R + 6 + t, py = cy - 34 + k * 22 + Math.sin(t / 18) * 6; t ? c.lineTo(px, py) : c.moveTo(px, py); } c.stroke(); }
      c.restore(); c.textAlign = "left";
    };
    const draw = () => {
      const W = 1600, H = 1100;
      c.fillStyle = "#fbf8ef"; c.fillRect(0, 0, W, H);
      c.strokeStyle = "#e7dfc8"; c.lineWidth = 2; c.strokeRect(14, 14, W - 28, H - 28);
      // photo with white border, slightly tilted
      const px = 56, py = 64, pw = 860, ph = 900;
      c.save(); c.translate(px + pw / 2, py + ph / 2); c.rotate(-0.012); c.shadowColor = "rgba(0,0,0,.22)"; c.shadowBlur = 24; c.shadowOffsetY = 8;
      c.fillStyle = "#fff"; c.fillRect(-pw / 2, -ph / 2, pw, ph + 70); c.shadowColor = "transparent";
      photo ? cover(c, photo, -pw / 2 + 18, -ph / 2 + 18, pw - 36, ph - 36) : placeholder(c, -pw / 2 + 18, -ph / 2 + 18, pw - 36, ph - 36, "Chọn một ảnh");
      c.fillStyle = "#6b6450"; c.font = `500 22px ${BODY}`; wrap(c, credit ? "Ảnh: " + credit : "", -pw / 2 + 22, ph / 2 + 36, pw - 44, 26, 1);
      c.restore();
      // divider
      const dx = 980; c.strokeStyle = "#d8cfb4"; c.lineWidth = 2; c.setLineDash([10, 10]); c.beginPath(); c.moveTo(dx, 70); c.lineTo(dx, H - 70); c.stroke(); c.setLineDash([]);
      const x = dx + 44, w = W - 60 - x;
      stamp(W - 60 - 300, 50, 300, 200); postmark(W - 60 - 320, 196);
      const line = (y) => { c.strokeStyle = "#d8cfb4"; c.lineWidth = 2; c.beginPath(); c.moveTo(x, y); c.lineTo(W - 60, y); c.stroke(); };
      c.fillStyle = "#8a8169"; c.font = `600 22px ${BODY}`; c.fillText("Từ", x, 352); c.fillText("Gửi đến", x, 432);
      const fitOne = (txt, max, min, width) => { let z = max; c.font = `600 ${z}px ${HAND}`; while (z > min && c.measureText(txt).width > width) { z -= 2; c.font = `600 ${z}px ${HAND}`; } return z; };
      const oneLine = (txt, tx, ty, width) => { let t = txt; while (t.length > 1 && c.measureText(t).width > width) t = t.slice(0, -2) + "…"; c.fillText(t, tx, ty); };
      const lines = (txt, width) => { let n = 1, cur = ""; for (const wd of String(txt).split(/\s+/)) { const t = cur ? cur + " " + wd : wd; if (c.measureText(t).width > width && cur) { n++; cur = wd; } else cur = t; } return n; };
      c.fillStyle = "#0b4a2b";
      const fromT = val("from") || "một AgriS-er", toT = val("to") || "Đồng đội";
      fitOne(fromT, 44, 26, w - 60); oneLine(fromT, x + 60, 350, w - 60);
      fitOne(toT, 44, 26, w - 110); oneLine(toT, x + 110, 430, w - 110);
      line(366); line(446);
      const wish = val("wish") || "Lời chúc của bạn…", top = 528, bottom = H - 130;
      let z = 44, lh;
      for (; z >= 24; z -= 2) { c.font = `600 ${z}px ${HAND}`; lh = Math.round(z * 1.45); if (lines(wish, w) * lh <= bottom - top + lh * .3) break; }
      for (let y = top + 12; y < bottom; y += lh) line(y);
      c.fillStyle = "#1f3a33"; c.font = `600 ${Math.max(z, 24)}px ${HAND}`; wrap(c, wish, x, top, w, lh, Math.floor((bottom - top) / lh) + 1);
      c.fillStyle = "#0b6b3a"; c.font = `800 31px ${FONT}`; c.fillText(S.tags || "", x, H - 66);
    };
    if (document.fonts) Promise.all(['600 40px "Dancing Script"'].map(f => document.fonts.load(f, 'Đội ơi ạ'))).then(() => draw()).catch(() => {});
    const pick = async () => { const r = pcForm.querySelector("[name=photo]:checked"); credit = r ? r.dataset.credit : ""; photo = r ? await loadImg(r.value).catch(() => null) : null; draw(); };
    pcForm.addEventListener("input", e => (e.target.name === "photo" ? pick() : draw()));
    document.addEventListener("agm-logo", draw);
    const cnt = pcForm.querySelector("[data-count]"); if (cnt) pcForm.elements.wish.addEventListener("input", e => (cnt.textContent = e.target.value.length + "/220"));
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
