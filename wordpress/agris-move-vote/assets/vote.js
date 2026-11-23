/* AgriS MOVE voting: sign-in dialog, love buttons, filter box. Talks to admin-ajax.php. */
(() => {
  if (!window.AMV) return;
  const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const dlg = $("#amv-dialog");
  let me = { person: null, voted: [], counts: null, state: "open" }, pending = null;

  const call = async (action, data = {}, method = "POST") => {
    const body = new URLSearchParams({ action, nonce: AMV.nonce, ...data });
    const url = method === "GET" ? `${AMV.ajax}?${body}` : AMV.ajax;
    const res = await fetch(url, { method, credentials: "same-origin", headers: method === "POST" ? { "Content-Type": "application/x-www-form-urlencoded" } : {}, body: method === "POST" ? body : undefined });
    let json = null;
    try { json = await res.json(); } catch (e) { /* not json */ }
    if (!json) throw { message: "Không kết nối được máy chủ. Hãy thử lại." };
    if (!json.success) throw json.data || { message: "Có lỗi xảy ra. Hãy thử lại." };
    return json.data;
  };

  const fmt = n => Number(n || 0).toLocaleString("vi-VN");
  const paint = () => {
    $$(".amv-bar").forEach(bar => {
      const who = $(".amv-who", bar);
      who.hidden = !me.person;
      if (me.person) who.innerHTML = `Xin chào <b></b>${me.person.unit ? " · " : ""}<span></span>`, $("b", who).textContent = me.person.name, $("span", who).textContent = me.person.unit || "";
      $(".amv-signin", bar).hidden = !!me.person || me.state !== "open";
      $(".amv-signout", bar).hidden = !me.person;
    });
    $$(".amv-love").forEach(b => {
      const id = +b.dataset.mover, on = me.voted.includes(id);
      b.classList.toggle("on", on);
      b.setAttribute("aria-pressed", on ? "true" : "false");
      b.disabled = me.state !== "open";
      if (me.counts) $(".amv-n", b).textContent = fmt(me.counts[id]);
    });
  };

  const openLogin = then => {
    pending = then || null;
    if (!dlg) return;
    $(".amv-err", dlg).hidden = true;
    dlg.showModal ? dlg.showModal() : dlg.setAttribute("open", "");
    setTimeout(() => $("#amv-name").focus(), 30);
  };

  const vote = async btn => {
    if (!me.person) return openLogin(() => vote(btn));
    btn.classList.add("busy");
    try {
      const r = await call("amv_vote", { mover: btn.dataset.mover });
      const id = +btn.dataset.mover;
      me.voted = r.on ? [...new Set([...me.voted, id])] : me.voted.filter(x => x !== id);
      if (me.counts && r.count !== null) me.counts[id] = r.count;
      paint();
      if (r.on) { btn.classList.remove("pop"); void btn.offsetWidth; btn.classList.add("pop"); }
    } catch (e) {
      if (e.login) return openLogin(() => vote(btn));
      alertBar(e.message);
    } finally { btn.classList.remove("busy"); }
  };

  const alertBar = msg => {
    $$(".amv-bar").forEach(bar => {
      let n = $(".amv-flash", bar);
      if (!n) { n = document.createElement("p"); n.className = "amv-flash"; n.setAttribute("role", "status"); bar.appendChild(n); }
      n.textContent = msg;
      clearTimeout(n._t); n._t = setTimeout(() => n.remove(), 5000);
    });
  };

  document.addEventListener("click", e => {
    const love = e.target.closest(".amv-love");
    if (love) { e.preventDefault(); vote(love); return; }
    if (e.target.closest(".amv-signin")) { openLogin(); return; }
    if (e.target.closest(".amv-signout")) {
      call("amv_logout").then(d => { me = { ...me, ...d }; paint(); }).catch(err => alertBar(err.message));
    }
  });

  if (dlg) {
    const form = $(".amv-form", dlg), err = $(".amv-err", dlg), list = $("#amv-names");
    let t = 0;
    $("#amv-name").addEventListener("input", e => {
      clearTimeout(t);
      const q = e.target.value.trim();
      if (q.length < 2) { list.innerHTML = ""; return; }
      t = setTimeout(() => call("amv_find", { q }, "GET").then(rows => {
        list.innerHTML = "";
        rows.forEach(r => { const o = document.createElement("option"); o.value = r.name; if (r.unit) o.label = r.unit; list.appendChild(o); });
      }).catch(() => {}), 220);
    });
    form.addEventListener("submit", async e => {
      if (e.submitter && e.submitter.value === "cancel") return;
      e.preventDefault();
      const go = $(".amv-go", form);
      err.hidden = true; go.disabled = true; go.textContent = "Đang kiểm tra…";
      try {
        const d = await call("amv_login", { name: form.name.value, msnv: form.msnv.value, phone: form.phone.value });
        me = { ...me, ...d };
        dlg.close ? dlg.close() : dlg.removeAttribute("open");
        form.reset(); paint();
        if (pending) { const p = pending; pending = null; p(); }
      } catch (x) {
        err.textContent = x.message; err.hidden = false;
      } finally { go.disabled = false; go.textContent = "Đăng nhập"; }
    });
    dlg.addEventListener("click", e => { if (e.target === dlg) dlg.close(); });
  }

  // filter box
  const q = $("#amv-q");
  if (q) q.addEventListener("input", () => {
    const k = q.value.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "").replace(/đ/g, "d").trim();
    let shown = 0;
    $$(".amv-card").forEach(c => { const hit = !k || c.dataset.q.includes(k); c.hidden = !hit; shown += hit; });
    const none = $(".amv-none"); if (none) none.hidden = shown > 0;
  });

  call("amv_me", {}, "GET").then(d => { me = { ...me, ...d }; paint(); }).catch(() => paint());
})();
