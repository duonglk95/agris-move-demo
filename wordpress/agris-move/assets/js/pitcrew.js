/* Pit Crew: register, look up status, download the crew card, copy share text. */
(() => {
  const P = window.AGP || {}, G = "#0b5d34", LIME = "#b6e34a";
  const FONT = '"Montserrat", system-ui, sans-serif', BODY = '"Be Vietnam Pro", system-ui, sans-serif';
  const done = document.querySelector(".pit-done"); let share = "";
  const post = async (form, action) => {
    const fd = new FormData(form); fd.append("action", action); fd.append("nonce", P.nonce);
    return fetch(P.ajax, { method: "POST", body: fd }).then(r => r.json());
  };
  const card = d => {
    const cv = document.getElementById("pitCard"), c = cv.getContext("2d"), W = 1080, H = 1350;
    const g = c.createLinearGradient(0, 0, 0, H); g.addColorStop(0, "#0e7a42"); g.addColorStop(1, G); c.fillStyle = g; c.fillRect(0, 0, W, H);
    for (let i = 0; i < 14; i++) for (let j = 0; j < 3; j++) if ((i + j) % 2) { c.fillStyle = "rgba(255,255,255,.08)"; c.fillRect(i * 80, 1110 + j * 80, 80, 80); }
    c.textAlign = "center";
    c.fillStyle = "#fff"; c.font = `800 64px ${FONT}`; c.fillText("AgriS", W / 2, 170);
    c.fillStyle = LIME; c.font = `italic 800 60px ${FONT}`; c.fillText("PITSTOP", W / 2, 250);
    c.fillStyle = "rgba(255,255,255,.75)"; c.font = `600 30px ${BODY}`; c.fillText("PIT CREW · VÀO VỤ 2026–2027", W / 2, 310);
    c.fillStyle = "#fff"; c.font = `800 300px ${FONT}`; c.fillText("#" + d.number, W / 2, 680);
    c.font = `800 64px ${FONT}`; c.fillText(d.name, W / 2, 820);
    c.fillStyle = LIME; c.font = `700 40px ${BODY}`; c.fillText(d.center || "", W / 2, 890);
    c.fillStyle = "rgba(255,255,255,.85)"; c.font = `600 30px ${BODY}`; c.fillText(P.tags || "", W / 2, 1060);
    c.textAlign = "left";
  };
  const show = d => {
    done.hidden = false;
    done.querySelector(".pit-ok").textContent = d.again ? "Bạn đã đăng ký trước đó" : "Đăng ký thành công";
    done.querySelector(".pit-num").textContent = "Bạn là Pit Crew thứ " + d.number;
    done.querySelector(".pit-st").innerHTML = "";
    d.tours.forEach(t => { const li = document.createElement("li"); li.innerHTML = "<span></span><b></b>"; li.querySelector("span").textContent = t.tour; li.querySelector("b").textContent = t.status; li.querySelector("b").className = "s-" + t.code; done.querySelector(".pit-st").append(li); });
    share = d.share; card(d); done.scrollIntoView({ behavior: "smooth", block: "start" });
  };
  const reg = document.querySelector("[data-pit]");
  if (reg) reg.addEventListener("submit", async e => {
    e.preventDefault(); const m = reg.querySelector(".tk-msg"), b = reg.querySelector("[type=submit]"); m.hidden = true;
    if (!reg.querySelector("[name='tours[]']:checked")) { m.textContent = "Bạn chọn ít nhất một tour nhé."; m.hidden = false; return; }
    b.disabled = true;
    try { const r = await post(reg, "agm_pit"); if (r.success) { reg.hidden = true; show(r.data); } else { m.textContent = r.data.message; m.hidden = false; } }
    catch { m.textContent = "Chưa gửi được, bạn kiểm tra mạng rồi thử lại nhé."; m.hidden = false; }
    b.disabled = false;
  });
  const chk = document.querySelector("[data-pitcheck]");
  if (chk) chk.addEventListener("submit", async e => {
    e.preventDefault(); const m = chk.querySelector(".tk-msg"); m.hidden = true;
    try { const r = await post(chk, "agm_pit_check"); if (r.success) { r.data.again = true; show(r.data); } else { m.textContent = r.data.message; m.hidden = false; } }
    catch { m.textContent = "Chưa tra cứu được, bạn thử lại nhé."; m.hidden = false; }
  });
  document.getElementById("pitDown").addEventListener("click", () => document.getElementById("pitCard").toBlob(b => { const a = document.createElement("a"); a.href = URL.createObjectURL(b); a.download = "the-pit-crew.png"; a.click(); }, "image/png"));
  document.getElementById("pitCopy").addEventListener("click", async () => { try { await navigator.clipboard.writeText(share); } catch {} document.getElementById("pitCopied").hidden = false; });
})();

// "Khác" role: show its text box
(() => {
  const on = document.querySelector("[data-other]"), box = document.querySelector("[name=role_other]");
  if (!on || !box) return;
  on.addEventListener("change", () => { box.hidden = !on.checked; if (on.checked) box.focus(); });
})();
