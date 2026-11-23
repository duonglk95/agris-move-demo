(() => {
  // the recurring ribbon: three layered strokes for a glassy band
  let n = 0;
  document.querySelectorAll("[data-ribbon]").forEach(el => {
    const id = "rb" + (n++);
    el.innerHTML = `<svg viewBox="0 0 520 180" preserveAspectRatio="none" aria-hidden="true"><defs>
      <linearGradient id="${id}" x1="0" x2="1" y1="0" y2="0"><stop offset="0" stop-color="#19b5a5" stop-opacity="0"/><stop offset=".35" stop-color="#19b5a5"/><stop offset=".7" stop-color="#2faa5a"/><stop offset="1" stop-color="#b6e36a"/></linearGradient>
      <linearGradient id="${id}g" x1="0" x2="1"><stop offset="0" stop-color="#f6c544" stop-opacity="0"/><stop offset=".6" stop-color="#f6c544"/><stop offset="1" stop-color="#fff3c4"/></linearGradient></defs>
      <path d="M0 170 C150 175 300 150 520 20" stroke="url(#${id})" stroke-width="34" fill="none" opacity=".22"/>
      <path d="M40 176 C190 172 330 140 520 46" stroke="url(#${id})" stroke-width="14" fill="none" opacity=".55"/>
      <path d="M90 178 C230 170 350 140 520 70" stroke="url(#${id}g)" stroke-width="3" fill="none" opacity=".9"/></svg>`;
  });
  // TOP 50 portraits, placed where the mockup puts them (centre x, centre y, radius on a 724 x 455 field)
  const F = [[102, 92, 66], [219, 43, 23], [356, 48, 47], [493, 48, 21], [630, 69, 49], [604, 174, 37], [676, 236, 25], [631, 344, 61], [508, 387, 24], [361, 405, 35], [214, 389, 25], [97, 341, 49], [40, 264, 18], [95, 208, 33]];
  const host = document.getElementById("faces");
  const srcs = window.__FACES || [];
  if (host) F.forEach(([x, y, r], i) => {
    const el = document.createElement("div");
    const top = y / 455 * 100;
    el.className = "face" + (top > 26 && top < 80 ? " mid" : top <= 26 ? " tf" : " bf");
    const w = (r * 2 / 724 * 100).toFixed(2) + "%";
    el.style.cssText = `left:${(x / 724 * 100).toFixed(2)}%;--t:${top.toFixed(2)}%;width:${w};--w:${w};--d:${(-(i * 0.7) % 6).toFixed(1)}s`;
    el.innerHTML = `<img alt="" loading="lazy" src="${srcs[i] || ""}">`;
    host.appendChild(el);
  });
})();


(() => {
  // wave seams: each module pours into the next through three drifting layers and a ribbon edge
  function wave(H, base, amps, ph) {
    const W = 1440, pts = [];
    for (let x = 0; x <= 2 * W; x += 24) {
      const t = x / W * 2 * Math.PI;
      pts.push((pts.length ? "L" : "M") + x + " " + (base + amps[0] * Math.sin(t + ph[0]) + amps[1] * Math.sin(2 * t + ph[1]) + amps[2] * Math.sin(3 * t + ph[2])).toFixed(1));
    }
    const line = pts.join(" ");
    return { line, area: `${line} L${2 * W} ${H + 2} L0 ${H + 2} Z` };
  }
  document.querySelectorAll(".seam").forEach((el, i) => {
    const H = +(el.dataset.h || 110), next = el.dataset.next, [t1, t2] = (el.dataset.tones || "#d3eadb,#ffffff").split(",");
    el.style.setProperty("--h", H + "px");
    const svg = inner => `<svg viewBox="0 0 2880 ${H}" preserveAspectRatio="none" aria-hidden="true">${inner}</svg>`;
    const a = wave(H, H * .42, [H * .16, H * .09, H * .05], [i * 1.3, i * 2.1 + 1, i + 2]);
    const b = wave(H, H * .54, [H * .15, H * .08, H * .04], [i * .7 + 2, i * 1.7, i * 2.3 + 1]);
    const c = wave(H, H * .68, [H * .14, H * .07, H * .035], [i * 1.9 + 4, i * .9 + 3, i * 1.4]);
    const lime = el.dataset.line === "lime";
    const edge = `<defs><linearGradient id="wg${i}" gradientUnits="userSpaceOnUse" x1="0" x2="720" y1="0" y2="0" spreadMethod="repeat"><stop offset="0" stop-color="#19b5a5"/><stop offset=".35" stop-color="#2faa5a"/><stop offset=".65" stop-color="#b6e36a"/><stop offset="1" stop-color="#19b5a5"/></linearGradient></defs>
      <path d="${c.area}" fill="${next}"/>
      <path d="${c.line}" fill="none" stroke="${lime ? "#b6e36a" : `url(#wg${i})`}" stroke-width="${lime ? 2.5 : 4}" vector-effect="non-scaling-stroke"/>
      <path d="${c.line}" transform="translate(0 -7)" fill="none" stroke="#f6c544" stroke-width="1.4" opacity="${lime ? 0 : .85}" vector-effect="non-scaling-stroke"/>`;
    const clip = document.createElement("div"); clip.className = "seam-clip";
    clip.innerHTML = `<div class="wv rev" style="--s:${74 + i * 6}s">${svg(`<path d="${a.area}" fill="${t1}"/>`)}</div><div class="wv" style="--s:${52 + i * 5}s">${svg(`<path d="${b.area}" fill="${t2}"/>`)}</div><div class="wv" style="--s:${96 + i * 4}s">${svg(edge)}</div>`;
    el.prepend(clip);
  });
  // upcoming events: the card always shows the next one, and rolls on by itself once a date has passed.
  // To add or change an event, edit this list (Vietnam time). allDay events stay on the card through their day.
  const EVENTS = (window.AGM && AGM.events) || [];   // filled from the Sự kiện entries in WordPress
  (function () {
    const $ = id => document.getElementById(id), p = n => String(n).padStart(2, "0");
    if (!$("evTitle")) return;   // the event card is only on the homepage
    const WD = ["Chủ nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
    const list = EVENTS.map(e => {
      const start = new Date(e.at + ":00+07:00").getTime();
      const end = e.allDay ? new Date(e.at.slice(0, 10) + "T23:59:59+07:00").getTime() : start + 2 * 3600e3;
      return { ...e, start, end };
    }).sort((a, b) => a.start - b.start);
    let cur = null;
    const show = ev => {
      cur = ev;
      const d = new Date(ev.start + 7 * 3600e3);   // read the date parts in Vietnam time
      $("evMonth").textContent = "THÁNG " + (d.getUTCMonth() + 1); $("evDay").textContent = d.getUTCDate(); $("evWd").textContent = WD[d.getUTCDay()];
      $("evTitle").textContent = ev.title;
      $("evDate").textContent = p(d.getUTCDate()) + "/" + p(d.getUTCMonth() + 1) + "/" + d.getUTCFullYear();
      $("evPlaceLi").hidden = !ev.place; if (ev.place) $("evPlace").textContent = ev.place;
      $("cdTimer").setAttribute("aria-label", "Đếm ngược đến " + $("evDate").textContent);
    };
    const tick = () => {
      const now = Date.now(), next = list.find(e => e.end > now);
      if (!next) { $("evTitle").textContent = "Sự kiện mới sẽ sớm cập nhật"; $("evPlaceLi").hidden = true; $("evDate").parentElement.hidden = true; document.querySelector(".event .date").style.visibility = "hidden"; $("cdLabel").textContent = "Chưa có sự kiện mới"; ["cdD", "cdH", "cdM", "cdS"].forEach(id => $(id).textContent = "00"); return; }
      if (next !== cur) show(next);
      let d = next.start - now;
      $("cdLabel").textContent = d > 0 ? "Còn lại đến " + (next.cd || "sự kiện") : "Đang diễn ra hôm nay";
      d = Math.max(0, d);
      const s = Math.floor(d / 1000);
      $("cdD").textContent = p(Math.floor(s / 86400)); $("cdH").textContent = p(Math.floor(s % 86400 / 3600));
      $("cdM").textContent = p(Math.floor(s % 3600 / 60)); $("cdS").textContent = p(s % 60);
    };
    tick(); setInterval(tick, 1000);
  })();
  // a small flock of the hero's folded birds crossing between modules
  document.querySelectorAll(".flock").forEach(el => {
    const b = (x, y, s, d) => `<g transform="translate(${x} ${y}) scale(${s})"><g class="w" style="--fd:${d}s"><path d="M34 0L-30 -26L-10 0Z" fill="#6fbf8f"/><path d="M34 0L-30 26L-10 0Z" fill="#007a3d"/></g></g>`;
    el.innerHTML = `<svg viewBox="-40 -60 240 120" aria-hidden="true">${b(150, 0, .62, 0)}${b(96, -22, .46, .2)}${b(96, 22, .46, .35)}${b(46, -42, .38, .1)}${b(46, 42, .38, .5)}</svg>`;
  });
  // scroll: reveal modules as they arrive, pop the portraits, drift the decor at its own pace
  const reduce = window.matchMedia && matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (!reduce && "IntersectionObserver" in window) {
    const io = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) { e.target.classList.add("in-view"); io.unobserve(e.target); } }), { rootMargin: "0px 0px -8% 0px", threshold: .12 });
    document.querySelectorAll("[data-rv]").forEach((el, i) => { el.classList.add("rv"); el.style.setProperty("--rd", (el.dataset.rv || 0) + "s"); io.observe(el); });
    const cloud = document.getElementById("faces");
    if (cloud) {
      const faces = [...cloud.querySelectorAll(".face")];
      faces.forEach((f, i) => { f.classList.add("pop"); f.style.setProperty("--pd", (i * 0.06).toFixed(2) + "s"); });
      new IntersectionObserver((es, o) => es.forEach(e => { if (e.isIntersecting) { faces.forEach(f => f.classList.add("in-view")); o.disconnect(); } }), { threshold: .25 }).observe(cloud);
    }
    const px = [...document.querySelectorAll("[data-px]")];
    px.forEach(el => el.classList.add("px"));
    let tick = false;
    const run = () => { tick = false; const vh = innerHeight; px.forEach(el => { const r = el.parentElement.getBoundingClientRect(); el.style.setProperty("--p", ((r.top + r.height / 2 - vh / 2) * parseFloat(el.dataset.px)).toFixed(1)); }); };
    addEventListener("scroll", () => { if (!tick) { tick = true; requestAnimationFrame(run); } }, { passive: true });
    run();
  }
})();

// compact header once the page has scrolled past the top
(() => {
  const top = document.querySelector(".top");
  if (!top) return;
  const set = () => top.classList.toggle("is-compact", scrollY > 80);
  addEventListener("scroll", set, { passive: true }); set();
})();

// "Liên hệ chương trình" form
(() => {
  const dlg = document.getElementById("cfDlg");
  if (!dlg || !window.AGM) return;
  const form = dlg.querySelector("form"), msg = dlg.querySelector(".cf-msg"), sub = form.querySelector("[type=submit]");
  document.querySelectorAll("[data-cf-open]").forEach(b => b.addEventListener("click", () => { msg.hidden = true; dlg.showModal(); }));
  dlg.querySelector("[data-cf-close]").addEventListener("click", () => dlg.close());
  form.addEventListener("submit", async e => {
    e.preventDefault(); sub.disabled = true;
    const fd = new FormData(form); fd.append("action", "agm_contact"); fd.append("nonce", AGM.cfNonce);
    try {
      const r = await fetch(AGM.ajax, { method: "POST", body: fd }).then(r => r.json());
      msg.textContent = r.data && r.data.message || "Chưa gửi được, bạn thử lại nhé."; msg.hidden = false;
      if (r.success) { form.reset(); setTimeout(() => dlg.close(), 1800); }
    } catch { msg.textContent = "Chưa gửi được, bạn kiểm tra mạng rồi thử lại nhé."; msg.hidden = false; }
    sub.disabled = false;
  });
})();
