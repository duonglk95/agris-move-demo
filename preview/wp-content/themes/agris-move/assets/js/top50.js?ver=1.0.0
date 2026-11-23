// phases drive the status card, the timeline marker and the main button (Vietnam time)
  (function () {
    const at = d => new Date(d + "+07:00").getTime();
    const P = [
      { from: at("2026-11-01T00:00:00"), to: at("2026-12-01T00:00:00"), label: "Mở nhận hồ sơ sau", title: "Nộp hồ sơ từ 01/12/2026", target: "to", cta: ["Xem cách tham gia", "#tham-gia"] },
      { from: at("2026-12-01T00:00:00"), to: at("2027-03-01T00:00:00"), label: "Hạn nộp hồ sơ còn", title: "Đang nhận hồ sơ, đề cử", target: "to", cta: ["Nộp hồ sơ ngay", "#tham-gia"] },
      { from: at("2027-03-01T00:00:00"), to: at("2027-05-01T00:00:00"), label: "Bình chọn kết thúc sau", title: "Đang bình chọn", target: "to", cta: ["Bình chọn ngay", "#binh-chon"] },
      { from: at("2027-05-01T00:00:00"), to: at("2027-05-16T00:00:00"), label: "Công bố kết quả sau", title: "Công bố ngày 16/05/2027", target: "to", cta: ["Xem giải thưởng", "#giai-thuong"] },
      { from: at("2027-05-16T00:00:00"), to: Infinity, label: "Mùa 1 đã có kết quả", title: "TOP 50 MOVERS 2027", target: null, cta: ["Xem giải thưởng", "#giai-thuong"] },
    ];
    const $ = id => document.getElementById(id), pad = n => String(n).padStart(2, "0");
    let cur = null;
    const tick = () => {
      const now = Date.now(), ph = P.find(p => now < p.to) || P[P.length - 1];
      if (ph !== cur) {
        cur = ph;
        $("phaseLabel").textContent = ph.label; $("phaseTitle").textContent = ph.title;
        $("ctaMain").firstChild.textContent = ph.cta[0] + " "; $("ctaMain").href = ph.cta[1];
        $("phaseTimer").hidden = !ph.target;
        document.querySelectorAll(".timeline li[data-from]").forEach(li => {
          const f = at(li.dataset.from + "T00:00:00"), t = at(li.dataset.to + "T23:59:59");
          li.classList.toggle("done", now > t); li.classList.toggle("now", now >= f && now <= t);
        });
      }
      if (!ph.target) return;
      const s = Math.max(0, Math.floor((ph.to - now) / 1000));
      $("cD").textContent = pad(Math.floor(s / 86400)); $("cH").textContent = pad(Math.floor(s % 86400 / 3600));
      $("cM").textContent = pad(Math.floor(s % 3600 / 60)); $("cS").textContent = pad(s % 60);
    };
    tick(); setInterval(tick, 1000);
  })();
