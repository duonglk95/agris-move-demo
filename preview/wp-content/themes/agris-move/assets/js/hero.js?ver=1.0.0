(() => {
  const LW = 1600, LH = 900, DT = 1 / 60;
  const THEMES = {
    A: { ground: "#f3f7f1", display: "Montserrat", dw: 800, title: "#0f2b4c", sub: "#3d5a52", idx: "#007a3d", idxLine: "rgba(0,122,61,.55)", idxMuted: "rgba(15,43,76,.45)",
         ul: ["#007a3d", "#8fd16a"], line: "rgba(0,122,61,.75)", dot: "#007a3d", tagBg: "rgba(255,255,255,.92)", tagStroke: "rgba(0,122,61,.35)", tagText: "#0b5d34",
         ring: "#007a3d", ring2: "#78bf1c", hurt: "rgba(95,115,120,.9)", flight: "31,143,115", stormTint: "126,146,152", tintA: 0.2, bar: [132, 152, 158],
         arrow: ["#00763c", "#1f8c42", "#78bf1c"], push: "0,122,61", pushRing: "120,191,28", kicker: "#0f2b4c", kdot: "#007a3d",
         bird: { n: ["#007a3d", "#6fbf8f"], l: ["#12a04e", "#8fdc7a"] }, glow: 0, shape: "ring", upper: false, wind: "110,130,135", calm: "143,214,170" },
    B: { ground: "#03140f", display: "Plus Jakarta Sans", dw: 800, title: "#ffffff", sub: "rgba(234,246,239,.74)", idx: "#8ef0b2", idxLine: "rgba(142,240,178,.6)", idxMuted: "rgba(234,246,239,.45)",
         ul: ["#8ef0b2", "#ffd45c"], line: "rgba(142,240,178,.7)", dot: "#8ef0b2", tagBg: "rgba(3,24,19,.72)", tagStroke: "rgba(142,240,178,.5)", tagText: "#d8ffe4",
         ring: "#8ef0b2", ring2: "#ffd45c", hurt: "rgba(150,170,172,.85)", flight: "142,240,178", stormTint: "0,8,6", tintA: 0.42, bar: [120, 150, 160],
         arrow: ["#bdf5cf", "#e9fff0", "#ffffff"], push: "142,240,178", pushRing: "255,212,92", kicker: "rgba(234,246,239,.86)", kdot: "#8ef0b2",
         bird: { n: ["#7fe3a6", "#e3fff0"], l: ["#ffc93c", "#fff4c2"] }, glow: 1, shape: "halo", upper: false, wind: "196,232,240", calm: "142,240,178" },
    C: { ground: "#ffffff", display: "Space Grotesk", dw: 700, title: "#0f2b4c", sub: "#44605a", idx: "#007a3d", idxLine: "rgba(0,122,61,.6)", idxMuted: "rgba(15,43,76,.45)",
         ul: ["#007a3d", "#007a3d"], line: "rgba(0,122,61,.85)", dot: "#007a3d", tagBg: "#ffffff", tagStroke: "#7cc49a", tagText: "#007a3d",
         ring: "#007a3d", ring2: "#007a3d", hurt: "#8fa19e", flight: "0,122,61", stormTint: "15,43,76", tintA: 0.05, bar: [175, 188, 192],
         arrow: ["#00763c", "#1f8c42", "#78bf1c"], push: "0,122,61", pushRing: "0,122,61", kicker: "#0f2b4c", kdot: "#007a3d",
         bird: { n: ["#0f2b4c", "#2f5d8a"], l: ["#007a3d", "#7fd38f"] }, glow: 0, shape: "square", upper: true, wind: "15,43,76", calm: "0,122,61" },
  };
  const P = THEMES[window.HERO_THEME] || THEMES.A, PAL = P;
  const DF = (w, px) => `${w} ${px}px "${P.display}", "Segoe UI", sans-serif`;
  // chapter 4 is one continuous move: glide into the arrow, dart off revealing it, MOVE slams in, the flock writes the tagline
  const T = { p2: 6, storm0: 6.4, lead: 7.4, storm1: 11.8, p3: 12, p4: 19, rotEnd: 21.4, merge: 21.6, word: 21.9,
              m1: 23.15, m2: 23.3, m3: 23.45, settle: 26.6, cta: 28.2, hold: 1e9, end: 1e9 };
  const START = 4.2;   // the scattered opening is trimmed: playback joins it shortly before the storm
  const CHAPTERS = [
    { n: "01", label: "Những chuyển động riêng lẻ", a: 0, b: T.p2 },
    { n: "02", label: "Dẫn lối giữa thử thách", a: T.p2, b: T.p3 },
    { n: "03", label: "Cùng nhau vào đội hình", a: T.p3, b: T.p4 },
    { n: "04", label: "Chuyển hóa thành MOVE", a: T.p4, b: T.end },
  ];
  const MSG = ["Lãnh đạo làm gương", "Môi trường tích cực", "Đội ngũ cùng hành động"];
  // each message appears inside the story, at the moment it happens: [text, in, out]
  const STORY = [[MSG[0], 8.3, 13.6], [MSG[1], 14.7, 18.2], [MSG[2], 19.25, 21.35]];
  // why each message: one plain sentence tying it to what the birds are doing on screen
  // the story told in a warmer voice: what the viewer sees, then what it means
  const WHY = [
    ["Khi bầu trời nổi giông, có một cánh chim chọn bay lên trước.", "Không phải để đi một mình, mà để cả đàn nhìn thấy đường."],
    ["Một cơn gió ngược quật tới, có bạn bị hất chệch khỏi hàng.", "Hai đồng đội lập tức bay lên chắn trước, chia gió để bạn kịp lấy lại thăng bằng."],
    ["Không ai bị bỏ lại phía sau.", "Mười lăm đôi cánh, một nhịp đập, cùng vút về phía bình minh."],
  ];
  const TAG = ["Người mở đường", "Chia gió cho đồng đội", "Cùng một nhịp bay"];
  const HURT_TAG = "Bị gió quật chệch hướng";
  // the longer telling shown when a message is opened on the final frame
  const TELL = [
    { kicker: "Lãnh đạo làm gương", title: "Người lãnh đạo tại AgriS tiên phong trong mọi hoạt động, kiến tạo môi trường để đội ngũ phát triển.", body: "Tiên phong trong hành động, chính trực trong quyết định, dám lắng nghe ý kiến khác biệt và tạo cảm giác an toàn để nhân viên lên tiếng. Văn hoá không xây bằng khẩu hiệu, mà hình thành từ những hành vi làm gương mỗi ngày của người lãnh đạo." },
    { kicker: "Môi trường tích cực", title: "Tạo không gian làm việc an toàn, cởi mở để mọi người được lắng nghe, được phát triển và dám đổi mới.", body: "Gìn giữ những giá trị làm nên bản sắc AgriS: tầm nhìn dài hạn, tinh thần trách nhiệm, kỷ luật, bền bỉ và khát vọng tiên phong. Đồng thời gỡ bỏ tâm lý sợ sai, im lặng, thụ động và tư duy phân cấp cứng nhắc, để mọi người trao đổi thẳng thắn, phản biện tích cực và cùng học hỏi." },
    { kicker: "Đội ngũ cùng hành động", title: "Mỗi nhân viên hiểu rõ về tổ chức, chủ động hơn, hợp tác và đổi mới để cùng xây dựng One AgriS.", body: "Tinh thần làm chủ, chủ động đề xuất giải pháp, dám đặt câu hỏi, sẵn sàng hợp tác và liên tục cải tiến là những hành vi được khuyến khích trong giai đoạn mới. Mỗi người không chỉ hoàn thành phần việc của mình mà còn góp vào thành công chung của tập thể." },
  ];
  let POS = [], KICK = [];

  const cv = document.getElementById("cv");
  let ctx = cv.getContext("2d");
  const clamp = (x, a = 0, b = 1) => Math.max(a, Math.min(b, x));
  const prog = (t, a, b) => clamp((t - a) / (b - a));
  const ss = x => x * x * (3 - 2 * x);
  const eio = x => x < .5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2;
  const eo = x => 1 - Math.pow(1 - x, 3);
  const back01 = x => { const c = 2.2; x -= 1; return x <= -1 ? 0 : 1 + (c + 1) * x * x * x + c * x * x; };
  const lerp = (a, b, k) => a + (b - a) * k;
  const angLerp = (a, b, k) => { let d = b - a; while (d > Math.PI) d -= 2 * Math.PI; while (d < -Math.PI) d += 2 * Math.PI; return a + d * k; };
  function rng(seed) { return () => { seed |= 0; seed = seed + 0x6D2B79F5 | 0; let t = Math.imul(seed ^ seed >>> 15, 1 | seed); t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t; return ((t ^ t >>> 14) >>> 0) / 4294967296; }; }

  // ---------- formation geometry ----------
  // a filled triangle: leader at the point, rows of 2, 3, 4, 5 filling it behind
  const APEX = { x: 1120, y: 450 }, SX = 96, SYU = 40, TILT = 0.74;
  const slots = [];
  for (let r = 0; r <= 4; r++) for (let j = 0; j <= r; j++) slots.push({ dx: -r, dy: 2 * j - r });
  // bird i takes slots[i]; stragglers join late, their neighbours slow down to fetch them
  const STRAG = { 10: 12.9, 14: 13.2 };         // the two back corners
  const ESCORT = { 6: 10, 9: 14 };              // escort -> straggler
  // mid-formation gust: these birds are knocked about, their neighbours close in to shield them
  // mid-formation gust: one bird is thrown off line, two teammates fly upwind of it and split the wind
  const HURT = 6, HELP = [3, 7];
  const WD = (() => { return { x: -1, y: 0 }; })();   // headwind: comes from in front of the flock
  const WU = { x: -WD.x, y: -WD.y }, WN = { x: -WD.y, y: WD.x };
  const W = tt => ({
    gust: ss(prog(tt, 13.8, 14.3)) * (1 - ss(prog(tt, 17.3, 18.1))),
    hit: ss(prog(tt, 14.05, 14.55)),
    cover: ss(prog(tt, 14.55, 15.45)) * (1 - ss(prog(tt, 17.0, 18.2))),
    rec: ss(prog(tt, 15.8, 17.3)),
  });
  const gustI = tt => W(tt).gust;
  const hurtTarget = (tt, jit) => { const w = W(tt), sp = slotPos(HURT, tt, jit), k = w.hit * (1 - w.rec); return { x: sp.x - 110 * k, y: sp.y - 48 * k }; };
  function slotPos(i, t, jit) {
    const s = slots[i];
    const bob = Math.sin(t * 1.25 + i * 0.9) * 4;
    return { x: APEX.x + s.dx * SX + jit.x, y: APEX.y + s.dy * SYU * TILT + jit.y + bob };
  }

  // ---------- word layout (filled after fonts load) ----------
  let WORD = null;
  function layoutWord() {
    // the real AgriS MOVE logo; V stroke anchors measured on a 1200-wide copy of the artwork
    const lw = 840, k = lw / 1200, lh = 215 * k, lx = (LW - lw) / 2, ly = 330;   // logo, tagline and the three messages sit as one group around the centre
    const P = (x, y) => ({ x: lx + x * k, y: ly + y * k });
    WORD = { lx, ly, lw, lh, k, base: ly + lh,
             // arrowhead measured on the artwork: tip, back corners; the E's top bar is the shaft
             tip: P(1197, 36), slot: (dx, dy) => P(1195 + dx * 16.5, 35 + dy * 7.2),
             clip: [P(1129, -4), P(1205, 36), P(1121, 70)] };
  }

  // ---------- birds ----------
  let birds, snap, wind, clouds, t, simT;
  function reset() {
    const r = rng(20261111);
    birds = [];
    for (let i = 0; i < slots.length; i++) {
      const home = { x: 180 + r() * 1240, y: 150 + r() * 470 };
      if (i === 0) { home.x = 520; home.y = 560; }
      if (STRAG[i]) { home.x = 150 + r() * 220; home.y = i === 14 ? 690 : 230; }
      const b = {
        i, home,
        rx: 70 + r() * 130, ry: 30 + r() * 70, w: (r() < .5 ? -1 : 1) * (0.22 + r() * 0.35), ph: r() * 6.28,
        size: 0.6 + r() * 0.55, flapRate: 1.1 + r() * 0.9, flap: r() * 6.28, jit: { x: (r() - .5) * 16, y: (r() - .5) * 12 },
        join: STRAG[i] || (i === 0 ? T.lead : 8.4 + (i - 1) * 0.28 + r() * 0.2),
        pos: null, vel: { x: 0, y: 0 }, head: 0, joinW: 0,
      };
      const w0 = wanderTarget(b, 0);
      b.pos = { x: w0.x, y: w0.y };
      const w1 = wanderTarget(b, 0.05);
      b.vel = { x: (w1.x - w0.x) / 0.05, y: (w1.y - w0.y) / 0.05 };
      b.head = Math.atan2(b.vel.y, b.vel.x);
      birds.push(b);
    }
    snap = null; simT = 0; t = 0; ANC = [null, null];
  }
  function wanderTarget(b, tt) {
    const a = b.w * tt + b.ph;
    // everyone flies forward, but out of step: faster flappers creep ahead, slow ones fall back, some drift up or down
    const drift = (b.flapRate - 1.55) * 60;
    return { x: b.home.x + 0.45 * b.rx * Math.cos(a) + tt * drift, y: b.home.y + b.ry * Math.sin(a * 1.3) };
  }
  function stormI(tt) { return ss(prog(tt, T.storm0, T.storm0 + 1.4)) * (1 - ss(prog(tt, T.storm1 - 1.6, T.storm1))); }

  function step() {
    const tt = simT, si = stormI(tt), gi = gustI(tt);
    updateAnchors(); TBOX = textBoxes(tt);
    for (const b of birds) {
      let tgt = wanderTarget(b, tt);
      if (si > 0 && tt < b.join) {   // storm compresses and shoves the scattered birds
        tgt.x += Math.sin(tt * 0.9 + b.i) * 120 * si - 60 * si;
        tgt.y += Math.cos(tt * 1.3 + b.i * 1.7) * 70 * si;
      }
      const jw = ss(prog(tt, b.join, b.join + (b.i === 0 ? 1.6 : 2.2)));
      b.joinW = jw;
      if (jw > 0) {
        const sp = slotPos(b.i, tt, b.i === 0 ? { x: 0, y: 0 } : b.jit);
        if (ESCORT[b.i] !== undefined) {   // escort eases back to meet its straggler, then returns
          const e = Math.sin(Math.PI * prog(tt, 12.3, 14.1));
          sp.x -= 150 * e; sp.y += (b.i === 9 ? 40 : -40) * e;
        }
        if (b.i === HURT) { const h = hurtTarget(tt, b.jit); sp.x = h.x; sp.y = h.y; }
        const hk = HELP.indexOf(b.i);
        if (hk >= 0) {
          const w = W(tt), h = hurtTarget(tt, birds[HURT].jit), side = hk ? 1 : -1;
          const shield = { x: h.x + WU.x * 62 + WN.x * 26 * side, y: h.y + WU.y * 62 + WN.y * 26 * side };
          sp.x = lerp(sp.x, shield.x, w.cover); sp.y = lerp(sp.y, shield.y, w.cover);
        }
        tgt = { x: lerp(tgt.x, sp.x, jw), y: lerp(tgt.y, sp.y, jw) };
      }
      // keep clear of the words: birds steer out of the space around an active message
      for (const bx of TBOX) {
        if (b.i === 0 || b.joinW > 0.5) continue;   // the formation holds its shape; only loose birds make way
        const m = 46;
        if (tgt.x > bx.x0 - m && tgt.x < bx.x1 + m && tgt.y > bx.y0 - m && tgt.y < bx.y1 + m) {
          const up = tgt.y < (bx.y0 + bx.y1) / 2;
          tgt.y = lerp(tgt.y, up ? bx.y0 - m - 10 : bx.y1 + m + 10, bx.a);
        }
      }
      const K = 5.2, C = 3.9;
      let ax = K * (tgt.x - b.pos.x) - C * b.vel.x;
      let ay = K * (tgt.y - b.pos.y) - C * b.vel.y;
      const hurtK = b.i === HURT ? (() => { const w = W(tt); return w.hit * (1 - w.rec) * (1 - 0.6 * w.cover); })() : 0;
      if ((si > 0 && jw < 1) || hurtK > 0) {   // gusts make birds lurch
        const g = Math.max(si * (1 - jw), hurtK * 1.1);
        ax += Math.sin(tt * 3.1 + b.i * 2.1) * 260 * g;
        ay += Math.cos(tt * 2.6 + b.i * 1.3) * 200 * g;
      }
      b.vel.x += ax * DT; b.vel.y += ay * DT;
      b.pos.x += b.vel.x * DT; b.pos.y += b.vel.y * DT;
      const sp = Math.hypot(b.vel.x, b.vel.y);
      const fwd = 170 * (1 - jw);   // the flock travels forward through the sky, so headings point mostly ahead
      const vh = Math.atan2(b.vel.y, b.vel.x + fwd);
      const hw = b.i === 0 ? jw : Math.pow(jw, 0.6);
      let h = angLerp(vh, 0, hw);
      b.head = angLerp(b.head, h, 0.12);
      const rate = b.flapRate * (1 + 0.5 * si * (1 - jw)) * lerp(1, 0.85, jw);
      b.flap += DT * rate * Math.PI * 2;
    }
    simT += DT;
    if (simT >= T.p4 && !snap) snap = birds.map(b => ({ x: b.pos.x, y: b.pos.y, head: b.head }));
  }

  // ---------- drawing ----------
  function poly(pts, fill) {
    ctx.beginPath(); ctx.moveTo(pts[0][0], pts[0][1]);
    for (let j = 1; j < pts.length; j++) ctx.lineTo(pts[j][0], pts[j][1]);
    ctx.closePath(); ctx.fillStyle = fill; ctx.fill();
  }
  // A bird is one folded chevron: two flat facets meeting on a centre crease. Wing beat = the fold opening and closing.
  function drawBird(x, y, s, head, flap, opt) {
    const { lead = 0, alpha = 1, glide = 0, wob = 0 } = opt;
    const span = lerp(0.62 + 0.38 * (0.5 + 0.5 * Math.cos(flap)), 0.8, glide);
    ctx.save();
    ctx.globalAlpha = alpha;
    ctx.translate(x, y); ctx.rotate(head + wob); ctx.scale(s, s);
    const tipX = -30, tipY = 30 * span, notch = -10;
    const cA = lead > 0.5 ? P.bird.l[0] : P.bird.n[0], cB = lead > 0.5 ? P.bird.l[1] : P.bird.n[1];
    if (P.glow) {   // light birds: a fading trail behind, a soft bloom around
      const tg = ctx.createLinearGradient(-130, 0, -12, 0);
      tg.addColorStop(0, "rgba(142,240,178,0)"); tg.addColorStop(1, lead > 0.5 ? "rgba(255,212,92,.55)" : "rgba(142,240,178,.45)");
      ctx.fillStyle = tg; ctx.fillRect(-130, -1.5, 118, 3);
      ctx.shadowColor = lead > 0.5 ? "rgba(255,200,80,.9)" : "rgba(130,240,170,.75)"; ctx.shadowBlur = lead > 0.5 ? 26 : 14;
    }
    poly([[34, 0], [tipX, -tipY], [notch, 0]], cB);
    poly([[34, 0], [tipX, tipY], [notch, 0]], cA);
    ctx.restore();
  }

  const STARS = (() => { const r = rng(42), o = []; for (let i = 0; i < 70; i++) o.push([r() * LW, r() * 560, 0.6 + r() * 1.6, r() * 6]); return o; })();
  function drawBackground(tt) {
    const si = stormI(tt), fin = ss(prog(tt, T.merge, T.word + 1.2));
    if (P === THEMES.B) {
      const g = ctx.createLinearGradient(0, 0, 0, LH);
      g.addColorStop(0, "#02110d"); g.addColorStop(0.55, "#052a2a"); g.addColorStop(1, "#0b4a3a");
      ctx.fillStyle = g; ctx.fillRect(-3000, -3000, LW + 6000, LH + 6000);
      STARS.forEach(([x, y, r, p]) => { ctx.fillStyle = `rgba(220,255,235,${(0.25 + 0.35 * Math.sin(tt * 1.3 + p) ** 2) * (1 - 0.6 * si)})`; ctx.fillRect(x, y, r, r); });
      // dawn: the horizon glow grows as the team comes together
      const dawn = 0.35 + 0.65 * ss(prog(tt, T.p3, T.word + 1));
      const hg = ctx.createRadialGradient(LW / 2, LH + 120, 40, LW / 2, LH + 120, 760);
      hg.addColorStop(0, `rgba(170,240,120,${0.6 * dawn})`); hg.addColorStop(0.45, `rgba(40,170,110,${0.22 * dawn})`); hg.addColorStop(1, "rgba(0,0,0,0)");
      ctx.fillStyle = hg; ctx.fillRect(-3000, 0, LW + 6000, LH + 3000);
      ctx.strokeStyle = "rgba(120,230,180,.22)"; ctx.lineWidth = 2;
      ctx.beginPath(); ctx.ellipse(LW / 2, 980, 1150, 480, -0.05, Math.PI * 1.08, Math.PI * 1.92); ctx.stroke();
      ctx.strokeStyle = "rgba(25,181,165,.25)"; ctx.lineWidth = 1;
      ctx.beginPath(); ctx.ellipse(LW / 2, 1020, 1300, 560, -0.03, Math.PI * 1.06, Math.PI * 1.94); ctx.stroke();
    } else if (P === THEMES.C) {
      ctx.fillStyle = "#ffffff"; ctx.fillRect(-3000, -3000, LW + 6000, LH + 6000);
      ctx.strokeStyle = "rgba(15,43,76,.07)"; ctx.lineWidth = 1;
      ctx.beginPath();
      for (let x = -1200; x <= LW + 1200; x += 100) { ctx.moveTo(x, -2000); ctx.lineTo(x, LH + 2000); }
      for (let y = -1000; y <= LH + 1000; y += 100) { ctx.moveTo(-3000, y); ctx.lineTo(LW + 3000, y); }
      ctx.stroke();
      ctx.save(); ctx.globalAlpha = 0.55 + 0.45 * fin;
      ctx.font = `700 520px "Space Grotesk", "Segoe UI", sans-serif`; ctx.textBaseline = "alphabetic";
      ctx.strokeStyle = "#cfe8d8"; ctx.lineWidth = 2.5; ctx.strokeText("MOVE", -40 - 60 * fin, 960);
      ctx.restore();
    } else {
      ctx.fillStyle = "#f3f7f1"; ctx.fillRect(0, 0, LW, LH);
      ctx.fillStyle = `rgba(230,241,233,${1 - fin})`;
      ctx.beginPath(); ctx.arc(1180 + Math.sin(tt * 0.08) * 30, 980, 620, 0, Math.PI * 2); ctx.fill();
    }
    if (si > 0) { ctx.fillStyle = `rgba(${P.stormTint},${P.tintA * si})`; ctx.fillRect(-3000, -3000, LW + 6000, LH + 6000); }
  }

  function capsule(x, y, len, th, col) {
    ctx.strokeStyle = col; ctx.lineWidth = th; ctx.lineCap = "round";
    ctx.beginPath(); ctx.moveTo(x, y); ctx.lineTo(x + len, y); ctx.stroke();
  }
  function drawStorm(tt) {
    const si = Math.max(stormI(tt), 0.3 * gustI(tt));
    if (si <= 0.001) return;
    // wind as flat rounded bars streaming right to left
    const bars = [[120, 620, 46, 300], [210, 380, 30, 420], [330, 760, 58, 240], [455, 300, 24, 520], [560, 540, 40, 340], [690, 440, 28, 460], [250, 200, 14, 640], [610, 160, 12, 700]];
    bars.forEach(([y, len, th, sp], k) => {
      const x = LW + 400 - ((tt * sp + k * 370) % (LW + 400 + len));
      capsule(x, y, P === THEMES.C ? len * 0.8 : len, P === THEMES.C ? Math.max(2, th * 0.12) : th, `rgba(${P.bar[0] - k * 4},${P.bar[1] - k * 3},${P.bar[2] - k * 2},${(P === THEMES.B ? 0.22 : 0.42 - (k % 3) * 0.08) * si})`);
    });
  }

  function drawFlightLines(tt, a) {
    if (a <= 0.001) return;
    for (let k = 0; k < 9; k++) {
      const yy = 150 + ((k * 83) % 600), len = 60 + (k * 37) % 90;
      const x = LW + 200 - ((tt * (300 + (k % 3) * 80) + k * 311) % (LW + 400));
      capsule(x, yy, len, P === THEMES.C ? 1.5 : 3, `rgba(${P.flight},${(P === THEMES.B ? 0.3 : 0.16) * a})`);
    }
  }

  function leadGlow() {}

  // headwind drawn like the ambient flight lines; once the two helpers are in front, the lines part around them
  // and fan outward like air over a car body, leaving the bird behind them in still air
  function gustDashes(H, F, cover, gust, tt, sc) {
    if (gust <= 0.01) return;
    const C = { x: lerp(H.x, F.x, cover), y: lerp(H.y, F.y, cover) }, R = 88 * sc;
    const bump = s => (s > 0 ? 1 - ss(prog(s, 0, 240 * sc)) : 1 - 0.55 * ss(prog(-s, 140 * sc, 460 * sc)));
    const at = (s, o) => {
      const side = o >= 0 ? 1 : -1, d = R * bump(s) * cover;
      const off = side * Math.sqrt(o * o + d * d);
      return { x: C.x + WU.x * s + WN.x * off, y: C.y + WU.y * s + WN.y * off };
    };
    ctx.save(); ctx.lineCap = "round"; ctx.lineJoin = "round";
    for (let i = 0; i < 13; i++) {
      const o = ((i * 5) % 13 - 6) * 12 * sc + (i % 2 ? 3 : -3) * sc, per = 1.15 + (i % 3) * 0.2;
      const u = ((tt + i * 0.29) / per) % 1, len = (70 + (i * 37) % 60) * sc;
      const head = lerp(620 * sc, -520 * sc, u);
      // before the helpers arrive, the lines near the centre strike the bird and break on it
      const hits = Math.abs(o) < 26 * sc && cover < 0.5;
      if (hits && head < 0) continue;
      const tail = Math.min(head + len, 640 * sc), hd = hits ? Math.max(head, 0) : head;
      let a = gust * ss(prog(u, 0, 0.12)) * (1 - ss(prog(u, 0.82, 1))) * (P === THEMES.B ? 0.5 : 0.35);
      ctx.strokeStyle = `rgba(${P.flight},${a})`; ctx.lineWidth = (P === THEMES.C ? 1.5 : 3) * Math.max(sc, 0.7);
      ctx.beginPath();
      for (let k = 0; k <= 10; k++) { const p = at(lerp(hd, tail, k / 10), o); k ? ctx.lineTo(p.x, p.y) : ctx.moveTo(p.x, p.y); }
      ctx.stroke();
    }
    ctx.restore();
  }
  function drawWind(tt) {
    const w = W(tt);
    if (w.gust <= 0.01 || !POS[HURT]) return;
    const H = POS[HURT], A = POS[HELP[0]], B = POS[HELP[1]];
    if (w.cover > 0.05) {
      const pk = ctx.createRadialGradient(H.x, H.y, 0, H.x, H.y, 80);
      pk.addColorStop(0, `rgba(${P.calm},${0.2 * w.cover})`); pk.addColorStop(1, `rgba(${P.calm},0)`);
      ctx.fillStyle = pk; ctx.beginPath(); ctx.arc(H.x, H.y, 80, 0, Math.PI * 2); ctx.fill();
    }
    const F = A && B ? { x: (A.x + B.x) / 2 + WU.x * 24, y: (A.y + B.y) / 2 + WU.y * 24 } : H;
    gustDashes({ x: H.x + WU.x * 14, y: H.y + WU.y * 14 }, F, w.cover, w.gust, tt, 1);
  }

  // words rise out of a hidden baseline mask one by one, and leave upward the same way
  function kinetic(text, x, y, size, font, color, tIn, tOut, tt, align = "left") {
    ctx.save();
    ctx.font = font; ctx.textAlign = "left"; ctx.textBaseline = "alphabetic";
    const words = text.split(" "), sp = ctx.measureText(" ").width;
    const ws = words.map(w => ctx.measureText(w).width), total = ws.reduce((a, b) => a + b, 0) + sp * (words.length - 1);
    const x0 = align === "center" ? x - total / 2 : x;
    ctx.beginPath(); ctx.rect(x0 - 20, y - size * 1.32, total + 40, size * 1.72); ctx.clip();
    let cx = x0;
    words.forEach((w, i) => {
      const kin = eo(prog(tt, tIn + i * 0.075, tIn + i * 0.075 + 0.62));
      const kout = tOut == null ? 0 : eio(prog(tt, tOut + i * 0.04, tOut + i * 0.04 + 0.42));
      if (kin > 0 && kout < 1) {
        ctx.globalAlpha = Math.min(1, kin * 1.4) * (1 - kout);
        ctx.fillStyle = color;
        ctx.fillText(w, cx, y + size * 1.15 * (1 - kin) - size * 1.15 * kout);
      }
      cx += ws[i] + sp;
    });
    ctx.restore();
    return { x0, total };
  }

  // each message is written into the scene by the birds doing it, and travels with them
  // a soft clear zone behind a line of text: whatever flies behind it is dimmed so the words stay crisp
  // the scene messages: one size, each parked beside the birds it describes and eased so it never jitters
  const TPX = 40, TY = 740, TFONT = () => DF(700, TPX);
  let TBOX = [], TW = [380, 340, 420], ANC = [null, null];
  function measureMessages() { ctx.save(); ctx.font = TFONT(); MSG.forEach((m, j) => { TW[j] = ctx.measureText(m).width; }); ctx.restore(); }
  // all messages ride just below the formation, centred under it, eased so they glide with the flock
  function updateAnchors() {
    let x0 = 1e9, x1 = -1e9, y1 = -1e9;
    for (const b of birds) if (b.i === 0 || (b.joinW > 0.9 && b.i !== HURT)) { x0 = Math.min(x0, b.pos.x); x1 = Math.max(x1, b.pos.x); y1 = Math.max(y1, b.pos.y); }
    const r = { cx: LW / 2, yb: TY };   // each line holds the centre, below the flock
    ANC[0] = ANC[0] ? { cx: lerp(ANC[0].cx, r.cx, 0.05), yb: lerp(ANC[0].yb, r.yb, 0.05) } : r;
    ANC[1] = ANC[0];
  }
  function textBoxes(tt) {
    const out = [];
    STORY.slice(0, 2).forEach(([, a, b], j) => {
      const k = ss(prog(tt, a - 0.4, a + 0.3)) * (1 - ss(prog(tt, b - 0.2, b + 0.4)));
      if (k > 0 && ANC[0]) out.push({ x0: ANC[0].cx - TW[j] / 2 - 20, x1: ANC[0].cx + TW[j] / 2 + 20, y0: ANC[0].yb - TPX, y1: ANC[0].yb + 16, a: k });
    });
    return out;
  }
  function scrim(cx, cy, w, h, a) {
    if (a <= 0) return;
    ctx.save(); ctx.translate(cx, cy); ctx.scale(1, h / w);
    const g = ctx.createRadialGradient(0, 0, 0, 0, 0, w / 2);
    g.addColorStop(0, `rgba(3,22,17,${0.55 * a})`); g.addColorStop(0.62, `rgba(3,22,17,${0.4 * a})`); g.addColorStop(1, "rgba(3,22,17,0)");
    ctx.fillStyle = g; ctx.fillRect(-w / 2, -w / 2, w, w); ctx.restore();
  }
  function sceneLine(text, cx, yb, tIn, tOut, tt, glow) {
    const r = eo(prog(tt, tIn, tIn + 0.8)), out = prog(tt, tOut - 0.8, tOut);
    if (r <= 0 || out >= 1) return;
    ctx.save();
    ctx.font = TFONT(); ctx.textAlign = "left"; ctx.textBaseline = "alphabetic";
    const w = ctx.measureText(text).width, x0 = cx - w / 2;
    // exit: the line flies straight ahead and blurs away
    const go = out * out, dx = 260 * go, fade = 1 - ss(out);
    ctx.fillStyle = "#ffffff"; ctx.shadowColor = `rgba(${glow},.55)`; ctx.shadowBlur = 14;   // a light glow, no dark backing
    if (out > 0) for (let g = 3; g >= 1; g--) { ctx.globalAlpha = 0.18 * fade * Math.min(1, out * 4) / g; ctx.fillText(text, x0 + dx - g * 34 * out, yb); }
    if (out > 0) ctx.filter = `blur(${(7 * go).toFixed(2)}px)`;
    ctx.globalAlpha = Math.min(1, r * 1.6) * fade;
    ctx.fillText(text, x0 + dx, yb);
    ctx.restore();
  }
  function skyText(tt) {
    if (!POS.length) return;
    const [m0, m1, m2] = STORY;
    if (ANC[0]) sceneLine(m0[0], ANC[0].cx, ANC[0].yb, m0[1], m0[2], tt, "255,226,140");
    if (ANC[0]) sceneLine(m1[0], ANC[0].cx, ANC[0].yb, m1[1], m1[2], tt, "142,240,178");
    if (tt > m2[1] - 0.2 && tt < m2[2] + 0.4) {   // on the formation's contrail, just behind its last row
      sceneLine(m2[0], LW / 2, TY, m2[1], m2[2] + 0.2, tt, "142,240,178");
    }
  }

  // thin callouts that tie each message to the birds doing it
  function ring(p, r, a, dashed, col, tt) {
    ctx.save(); ctx.globalAlpha = a; ctx.strokeStyle = col; ctx.lineWidth = P.shape === "square" ? 1.5 : 2;
    if (dashed) { ctx.setLineDash([5, 6]); ctx.lineDashOffset = -tt * 18; }
    if (P.shape === "square") {          // crop marks around the subject
      const q = r * 0.9, c = q * 0.45, pts = [[-1, -1], [1, -1], [1, 1], [-1, 1]];
      ctx.beginPath();
      pts.forEach(([sx, sy]) => { ctx.moveTo(p.x + sx * q, p.y + sy * (q - c)); ctx.lineTo(p.x + sx * q, p.y + sy * q); ctx.lineTo(p.x + sx * (q - c), p.y + sy * q); });
      ctx.stroke();
    } else if (P.shape === "halo" && !dashed) {   // light pooling around the subject
      const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, r * 1.6);
      g.addColorStop(0, col === P.ring2 ? "rgba(255,212,92,.35)" : "rgba(142,240,178,.28)"); g.addColorStop(1, "rgba(0,0,0,0)");
      ctx.fillStyle = g; ctx.beginPath(); ctx.arc(p.x, p.y, r * 1.6, 0, Math.PI * 2); ctx.fill();
      ctx.globalAlpha = a * 0.5; ctx.beginPath(); ctx.arc(p.x, p.y, r, 0, Math.PI * 2); ctx.stroke();
    } else {
      ctx.beginPath(); ctx.arc(p.x, p.y, r, -Math.PI / 2, -Math.PI / 2 + Math.PI * 2 * Math.min(1, a * 1.3)); ctx.stroke();
    }
    ctx.restore();
  }
  function leader(from, to, k, a) {
    if (k <= 0) return;
    const c = { x: from.x + (to.x - from.x) * 0.15, y: to.y + (from.y - to.y) * 0.25 };
    ctx.save(); ctx.globalAlpha = a; ctx.strokeStyle = P.line; ctx.lineWidth = 1.5; if (P === THEMES.C) ctx.setLineDash([2, 7]);
    ctx.beginPath(); ctx.moveTo(from.x, from.y);
    for (let s = 1; s <= 30; s++) {
      const u = s / 30 * k, x = (1 - u) * (1 - u) * from.x + 2 * u * (1 - u) * c.x + u * u * to.x, y = (1 - u) * (1 - u) * from.y + 2 * u * (1 - u) * c.y + u * u * to.y;
      ctx.lineTo(x, y);
    }
    ctx.stroke();
    if (k >= 1) { ctx.setLineDash([]); ctx.fillStyle = P.dot; ctx.beginPath(); ctx.arc(to.x, to.y, 3.5, 0, Math.PI * 2); ctx.fill(); }
    ctx.restore();
  }
  function tag(text, p, a) {
    if (a <= 0) return;
    ctx.save(); ctx.globalAlpha = a;
    if (P.upper) { text = text.toUpperCase(); ctx.font = `700 13px "Space Grotesk", "Segoe UI", sans-serif`; ctx.letterSpacing = "1px"; }
    else ctx.font = `600 16px "Be Vietnam Pro", "Segoe UI", sans-serif`;
    const w = ctx.measureText(text).width + 22;
    ctx.fillStyle = P.tagBg; ctx.strokeStyle = P.tagStroke; ctx.lineWidth = 1;
    ctx.beginPath(); ctx.roundRect(p.x, p.y - 15, w, 30, P.upper ? 0 : 15); ctx.fill(); ctx.stroke();
    ctx.fillStyle = P.tagText; ctx.textBaseline = "middle"; ctx.fillText(text, p.x + 11, p.y + 1);
    ctx.restore();
  }
  const centroid = ids => { const ps = ids.map(i => POS[i]).filter(Boolean); return { x: ps.reduce((a, p) => a + p.x, 0) / ps.length, y: ps.reduce((a, p) => a + p.y, 0) / ps.length }; };
  function annotate(tt) {
    const anchor = { x: 122, y: 690 };
    STORY.forEach(([, a, b], j) => {
      const on = eo(prog(tt, a + 0.4, a + 1.1)) * (1 - ss(prog(tt, b - 0.4, b + 0.1)));
      if (on <= 0 || !POS.length) return;
      const line = eio(prog(tt, a + 0.5, a + 1.3));
      if (j === 0) {
        const L = POS[0];
        ring(L, 46, on, false, P.ring2, tt); ring(L, 60, on * 0.5, true, P.ring, tt);
      } else if (j === 1) {
        const wq = W(tt), H = POS[HURT];
        ring(H, 32, on * 0.9, true, P.hurt, tt);
        const hv = on * ss(prog(wq.cover, 0.4, 1));
        HELP.forEach(i => ring(POS[i], 32, hv, false, P.ring, tt));
      } else {
        const A = POS[0], B = POS[10], C = POS[14];
        if (!A || !B || !C) return;
        const pad = 34, tri = [{ x: A.x + pad * 1.6, y: A.y }, { x: B.x - pad, y: B.y - pad }, { x: C.x - pad, y: C.y + pad }];
        ctx.save(); ctx.globalAlpha = on; ctx.strokeStyle = P.ring; ctx.lineWidth = 2; ctx.setLineDash([8, 7]); ctx.lineDashOffset = -tt * 40;
        ctx.beginPath(); tri.forEach((p, i) => i ? ctx.lineTo(p.x, p.y) : ctx.moveTo(p.x, p.y)); ctx.closePath(); ctx.stroke(); ctx.restore();
      }
    });
  }

  function storyCaption(tt) {
    STORY.forEach(([text, a, b], j) => {
      if (tt < a - 0.1 || tt > b + 0.6) return;
      const X = 112, Y = 760;
      // the message, then two lines of story, then an underline that draws out and retracts
      const { total } = kinetic(text, X, Y, 50, DF(P.dw, 50), P.title, a + 0.12, b - 0.25, tt);
      const ul = eio(prog(tt, a + 0.75, a + 1.35)), ulOut = eio(prog(tt, b - 0.45, b + 0.05));
      if (ul > 0 && ulOut < 1) {
        const x1 = X + total * ulOut, x2 = X + total * ul;
        const g = ctx.createLinearGradient(X, 0, X + total, 0);
        g.addColorStop(0, P.ul[0]); g.addColorStop(1, P.ul[1]);
        ctx.fillStyle = g; ctx.fillRect(x1, Y + 18, Math.max(0, x2 - x1), 3);
      }
    });
  }

  const LOGO = document.getElementById("lm");
  // camera for chapter 4: zoomed into the logo's arrow while the flock lands, then pulling back to the whole logo
  let CAM = { C: { x: 0, y: 0 }, F: { x: 0, y: 0 }, Z: 1 };
  const ZMAX = 6.2;
  function setCam(tt) {
    const zk = eio(prog(tt, T.rotEnd + 0.05, T.word + 0.5));   // a fast pull-back while the birds dart off
    const F = WORD.slot(-2, 0);
    const C0 = { x: 1300 - (WORD.tip.x - F.x) * ZMAX, y: 450 };
    CAM = { F, Z: lerp(ZMAX, 1, zk), C: { x: lerp(C0.x, F.x, zk), y: lerp(C0.y, F.y, zk) } };
  }
  const cam = p => ({ x: CAM.C.x + (p.x - CAM.F.x) * CAM.Z, y: CAM.C.y + (p.y - CAM.F.y) * CAM.Z });
  function camOn() { ctx.save(); ctx.translate(CAM.C.x, CAM.C.y); ctx.scale(CAM.Z, CAM.Z); ctx.translate(-CAM.F.x, -CAM.F.y); }
  function arrowPath() {
    const P = (x, y) => ({ x: WORD.lx + x * WORD.k, y: WORD.ly + y * WORD.k });
    const head = [P(1131, 0), P(1199, 36), P(1125, 65)];
    ctx.beginPath();
    head.forEach((p, j) => j ? ctx.lineTo(p.x, p.y) : ctx.moveTo(p.x, p.y)); ctx.closePath();
    const g = ctx.createLinearGradient(P(1120, 0).x, 0, P(1199, 0).x, 0);
    g.addColorStop(0, PAL.arrow[0]); g.addColorStop(0.55, PAL.arrow[1]); g.addColorStop(1, PAL.arrow[2]);
    return g;
  }
  function drawV(a) {
    if (a <= 0 || !WORD) return;
    camOn(); ctx.globalAlpha = a;
    const vec = clamp((CAM.Z - 1.15) / 0.5);          // crisp vector arrow while zoomed in, the logo artwork once at size
    if (vec > 0) {
      ctx.save(); ctx.globalAlpha = a * vec; ctx.fillStyle = arrowPath(); ctx.fill();
      if (a < 1) { ctx.globalAlpha = (1 - a) * vec; ctx.fillStyle = "#ffffff"; ctx.fill(); }   // the reveal flashes white first
      ctx.restore();
    }
    if (vec < 1 && LOGO.complete) {
      ctx.globalAlpha = a * (1 - vec);
      ctx.beginPath(); WORD.clip.forEach((p, j) => j ? ctx.lineTo(p.x, p.y) : ctx.moveTo(p.x, p.y)); ctx.closePath(); ctx.clip();
      ctx.drawImage(LOGO, WORD.lx, WORD.ly, WORD.lw, WORD.lh);
    }
    ctx.restore();
  }

  // the resolved logo keeps breathing: the arrow gives a small forward push, and a light sweeps across the mark
  let OFF = null;
  function drawLogoAlive(tt) {
    const live = tt - T.settle - 1.4;
    const ph = live > 0 ? (live % 4.8) / 4.8 : 1;
    const nudge = ph < 0.17 ? Math.sin(Math.PI * ph / 0.17) : 0;
    const head = new Path2D(); WORD.clip.forEach((p, j) => j ? head.lineTo(p.x, p.y) : head.moveTo(p.x, p.y)); head.closePath();
    // everything except the arrowhead
    ctx.save();
    const rest = new Path2D(); rest.rect(WORD.lx - 40, WORD.ly - 40, WORD.lw + 120, WORD.lh + 80); rest.addPath(head);
    ctx.clip(rest, "evenodd");
    ctx.drawImage(LOGO, WORD.lx, WORD.ly, WORD.lw, WORD.lh);
    ctx.restore();
    // the arrowhead, pushed forward
    const dx = 13 * nudge;
    if (nudge > 0) {
      for (let k = 0; k < 3; k++) {
        const yy = WORD.tip.y + (k - 1) * 9, len = (34 + k * 10) * nudge;
        ctx.fillStyle = `rgba(${P.push},${0.45 * nudge})`;
        ctx.fillRect(WORD.tip.x - 62 + dx - len, yy - 1, len, 2);
      }
      const rr = 14 + 46 * ph / 0.17;
      ctx.strokeStyle = `rgba(${P.pushRing},${0.5 * (1 - ph / 0.17)})`; ctx.lineWidth = 2;
      ctx.beginPath(); ctx.arc(WORD.tip.x + dx, WORD.tip.y, rr, 0, Math.PI * 2); ctx.stroke();
    }
    ctx.save(); ctx.translate(dx, 0); ctx.clip(head);
    ctx.drawImage(LOGO, WORD.lx, WORD.ly, WORD.lw, WORD.lh);
    ctx.restore();
    // light sweep, clipped to the logo's own pixels
    const sw = live > -0.4 ? ((live + 0.4) % 7) / 1.3 : 2;
    if (sw < 1) {
      const W2 = Math.ceil(WORD.lw * 2), H2 = Math.ceil(WORD.lh * 2);
      if (!OFF) { OFF = document.createElement("canvas"); OFF.width = W2; OFF.height = H2; }
      const o = OFF.getContext("2d");
      o.globalCompositeOperation = "source-over"; o.clearRect(0, 0, W2, H2);
      o.drawImage(LOGO, 0, 0, W2, H2);
      o.globalCompositeOperation = "source-in";
      const bx = -0.3 * W2 + 1.6 * W2 * eio(sw);
      const g = o.createLinearGradient(bx - 160, 0, bx + 160, H2);
      g.addColorStop(0, "rgba(255,255,255,0)"); g.addColorStop(0.5, "rgba(255,255,255,0.75)"); g.addColorStop(1, "rgba(255,255,255,0)");
      o.fillStyle = g; o.fillRect(0, 0, W2, H2);
      ctx.drawImage(OFF, WORD.lx, WORD.ly, WORD.lw, WORD.lh);
    }
  }

  // every push of the arrow releases a small triangle of birds that tears off to the right: the logo keeps moving
  function launch(tt) {
    const live = tt - T.settle - 1.4;
    if (live <= 0) return;
    const ph = (live % 4.8);
    if (ph > 2.2) return;
    const k = Math.pow(ph / 2.2, 1.7), fade = 1 - ss(prog(ph, 1.6, 2.2));
    const o = { x: WORD.tip.x + 6 + k * (LW - WORD.tip.x + 220), y: WORD.tip.y - 40 * Math.sin(Math.PI * k) };
    const mini = [[0, 0], [-1, -1], [-1, 1], [-2, -2], [-2, 0], [-2, 2]];
    for (let j = 0; j < 5; j++) {
      const len = (60 + j * 18) * Math.min(1, ph * 3);
      ctx.fillStyle = `rgba(${P.push},${(P === THEMES.B ? 0.4 : 0.22) * fade})`;
      ctx.fillRect(o.x - 70 - len, o.y + (j - 2) * 11, len, 2);
    }
    mini.forEach(([dx, dy], i) => drawBird(o.x + dx * 26, o.y + dy * 13, lerp(0.32, 0.5, k), -0.08 * Math.sin(Math.PI * k), tt * 9 + i, { lead: i === 0 ? 1 : 0, alpha: Math.min(1, ph * 4) * fade }));
  }

  const backOut = x => { const c = 1.9; x -= 1; return 1 + (c + 1) * x * x * x + c * x * x; };
  // MOVE (artwork x 395-1130, y above the tagline) slams in from the left with a speed lean and ghost trail; AgriS rises after it
  function drawLogoEnter(tt) {
    const k = WORD.k, X = x => WORD.lx + x * k, Y = y => WORD.ly + y * k;
    const u = prog(tt, T.word - 0.1, T.word + 0.42), base = Y(140);
    const piece = (x0, x1, y0, y1, dx, dy, skew, a) => {
      if (a <= 0.003) return;
      ctx.save();
      const clip = new Path2D(); clip.rect(X(x0), Y(y0), X(x1) - X(x0), Y(y1) - Y(y0));
      ctx.translate(dx, dy); ctx.transform(1, 0, skew, 1, -skew * base, 0);
      ctx.clip(clip); ctx.globalAlpha = a;
      ctx.drawImage(LOGO, WORD.lx, WORD.ly, WORD.lw, WORD.lh);
      ctx.restore();
    };
    if (u > 0) {
      const p = backOut(u), off = -300 * (1 - p), sk = -0.42 * (1 - eo(u));
      for (let g = 3; g >= 1; g--) piece(395, 1124, -30, 146, off - g * 55 * (1 - u), 0, sk * 1.15, 0.16 * (1 - u) / g);
      piece(395, 1124, -30, 146, off, 0, sk, Math.min(1, u * 4));
    }
    const v = prog(tt, T.word + 0.2, T.word + 0.75);
    piece(-30, 395, -30, 250, -40 * (1 - eo(v)), 0, 0, eo(v));
    piece(395, 1124, 146, 250, 0, 0, 0, 1);
    piece(1124, 1240, 146, 250, 0, 0, 0, 1);   // the tagline area (kept hidden by the caller until the flock passes)
  }
  // the landing: a soft flash behind the mark and speed lines thrown off to the left of MOVE
  function impact(tt) {
    const q = prog(tt, T.word + 0.25, T.word + 1.1);
    if (q <= 0 || q >= 1) return;
    const k = WORD.k, cx = WORD.lx + 760 * k, cy = WORD.ly + 70 * k;
    const f = Math.sin(Math.PI * Math.min(1, q * 1.6));
    const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, 420);
    g.addColorStop(0, `rgba(${P.push},${0.22 * f})`); g.addColorStop(1, `rgba(${P.push},0)`);
    ctx.fillStyle = g; ctx.fillRect(cx - 420, cy - 420, 840, 840);
    for (let j = 0; j < 7; j++) {
      const yy = WORD.ly + (12 + j * 19) * k, x0 = WORD.lx + (400 - (j % 3) * 18 + 0.42 * (140 - (12 + j * 19))) * k;
      const len = (120 + (j * 53) % 140) * eo(q), fade = 1 - ss(prog(q, 0.35, 1));
      const lg = ctx.createLinearGradient(x0 - len, 0, x0, 0);
      lg.addColorStop(0, `rgba(${P.push},0)`); lg.addColorStop(1, `rgba(${P.push},${0.7 * fade})`);
      ctx.fillStyle = lg; ctx.fillRect(x0 - 14 - len, yy, len, 2.5);
    }
  }
  // after the landing the mark sits in a slow, breathing pool of light
  function logoGlow(tt) {
    const a = ss(prog(tt, T.word + 0.3, T.word + 1.5)) * (0.75 + 0.25 * Math.sin((tt - T.word) * 1.3));
    if (a <= 0) return;
    const cx = WORD.lx + WORD.lw / 2, cy = WORD.ly + WORD.lh * 0.45;
    ctx.save(); ctx.translate(cx, cy); ctx.scale(1, 0.42);
    const g = ctx.createRadialGradient(0, 0, 0, 0, 0, 640);
    g.addColorStop(0, `rgba(${P.calm},${0.16 * a})`); g.addColorStop(1, `rgba(${P.calm},0)`);
    ctx.fillStyle = g; ctx.fillRect(-640, -640, 1280, 1280); ctx.restore();
  }

  // "CREATE FORWARD" sits under MOVE on the artwork (x 400-1100, y 148-215 at 1200 wide)
  const tagBox = () => { const k = WORD.k; return { x0: WORD.lx + 395 * k, x1: WORD.lx + 1105 * k, y0: WORD.ly + 146 * k, y1: WORD.ly + 218 * k }; };
  const TAGT = [T.word + 1.15, T.word + 2.45];
  const tagReveal = tt => { const tg = tagBox(); return lerp(tg.x0 - 30, tg.x1 + 30, eio(prog(tt, TAGT[0], TAGT[1]))); };
  function tagFlock(tt, tg, rx) {
    const u = prog(tt, TAGT[0] - 0.35, TAGT[1] + 0.9);
    if (u <= 0 || u >= 1) return;
    const cy = (tg.y0 + tg.y1) / 2, after = prog(tt, TAGT[1], TAGT[1] + 0.9);
    const lx = after > 0 ? rx + 260 * after * after : rx + (u < 0.15 ? -60 * (1 - u / 0.15) : 0) + 18;
    const ly = cy - 4 - 150 * after * after, fade = 1 - after, ent = Math.min(1, u / 0.12);
    // a light trail drawn along the line just revealed
    const tr = ctx.createLinearGradient(tg.x0 - 30, 0, lx, 0);
    tr.addColorStop(0, `rgba(${P.push},0)`); tr.addColorStop(1, `rgba(${P.push},${0.55 * fade * ent})`);
    ctx.fillStyle = tr; ctx.fillRect(Math.max(tg.x0 - 30, lx - 360), cy + 22, Math.min(360, lx - tg.x0 + 30), 2);
    const tri = [[0, 0], [-1, -1], [-1, 1], [-2, -2], [-2, 0], [-2, 2], [-3, -1], [-3, 1]];
    tri.forEach(([dx, dy], i) => drawBird(lx + dx * 24, ly + dy * 11, i ? 0.34 : 0.42, -0.5 * after, tt * 10 + i, { lead: i ? 0 : 1, alpha: fade * ent }));
  }

  function drawWord(tt) {
    if (!WORD) return;
    launch(tt);
    const w = tt > T.word - 0.1 ? 1 : 0;
    const cta = document.querySelector(".hero-cta");
    if (cta) { const on = tt > T.cta && tt < T.hold + 0.4; cta.classList.toggle("on", on); cta.firstElementChild.tabIndex = on ? 0 : -1; }
    document.getElementById("corner").style.opacity = String(1 - ss(prog(tt, T.rotEnd, T.merge)) * (1 - prog(tt, T.hold + 1, T.end)));
    if (w > 0 && LOGO.complete) {
      camOn(); logoGlow(tt);
      const tg = tagBox(), rx = tagReveal(tt);
      if (rx < tg.x1) {   // the tagline stays hidden until the flock flies over it
        const hide = new Path2D(); hide.rect(WORD.lx - 60, WORD.ly - 60, WORD.lw + 160, WORD.lh + 120);
        hide.rect(Math.max(rx, tg.x0), tg.y0, tg.x1 - Math.max(rx, tg.x0) + 4, tg.y1 - tg.y0);
        ctx.clip(hide, "evenodd");
      }
      if (tt < T.word + 0.95) drawLogoEnter(tt); else drawLogoAlive(tt);
      ctx.restore();
      camOn(); impact(tt); tagFlock(tt, tg, rx); ctx.restore();
    }
    // a quiet single line above the logo, like a kicker: the three messages joined by small green dots
    ctx.font = `600 25px "Be Vietnam Pro", "Segoe UI", sans-serif`;
    ctx.textBaseline = "alphabetic"; ctx.textAlign = "left";
    const widths = MSG.map(m => ctx.measureText(m).width);
    const gap = 46, total = widths.reduce((a, b) => a + b, 0) + gap * 2;
    let x = (LW - total) / 2;
    const y = WORD.ly + WORD.lh + 84;   // the three messages sit under the tagline
    MSG.forEach((m, j) => {
      KICK[j] = { x: x - 10, y: y - 32, w: widths[j] + 20, h: 46, on: tt > [T.m1, T.m2, T.m3][j] + 0.6 };
      // the three lines written by the birds fly in and land here, one after another
      const a0 = [T.m1, T.m2, T.m3][j] - 0.9, u = prog(tt, a0, a0 + 0.9);
      if (u > 0) {
        const e = eo(u), sx = x - 380 + j * 40, sy = y + 190 + j * 46, sc = lerp(1.5, 1, e), sk = -0.28 * (1 - e);
        const draw = (px, py, al) => { ctx.save(); ctx.globalAlpha = al; ctx.translate(px, py); ctx.scale(sc, sc); ctx.transform(1, 0, sk, 1, 0, 0);
          ctx.font = `600 25px "Be Vietnam Pro", "Segoe UI", sans-serif`; ctx.fillStyle = P.kicker; ctx.textBaseline = "alphabetic"; ctx.fillText(m, 0, 0); ctx.restore(); };
        if (u < 1) for (let g = 2; g >= 1; g--) { const eg = eo(Math.max(0, u - g * 0.04)); draw(lerp(sx, x, eg), lerp(sy, y, eg), 0.07 * (1 - u)); }
        draw(lerp(sx, x, e), lerp(sy, y, e), Math.min(1, u * 3));
      }
      if (j < 2) {
        const sk = back01(prog(tt, [T.m2, T.m3][j] - 0.15, [T.m2, T.m3][j] + 0.35));
        if (sk > 0) { ctx.fillStyle = P.kdot; ctx.beginPath(); ctx.arc(x + widths[j] + gap / 2, y - 8, 4.5 * sk, 0, Math.PI * 2); ctx.fill(); }
      }
      x += widths[j] + gap;
    });
  }

  function render(tt) {
    renderCore(tt);
    if (tt < T.rotEnd + 0.5) { annotate(tt); skyText(tt); }   // rings mark who the line is about
  }
  function renderCore(tt) {
    if (tt < T.rotEnd) document.getElementById("corner").style.opacity = "1";
    drawBackground(tt);
    drawStorm(tt);
    const fl = ss(prog(tt, T.p3 - 1, T.p3 + 1)) * (1 - ss(prog(tt, T.p4 + 0.5, T.rotEnd)));
    drawFlightLines(tt, Math.max(fl, 2.4 * Math.sin(Math.PI * prog(tt, T.p4 - 0.2, T.rotEnd + 0.4)), 0.9 * ss(prog(tt, T.settle - 1, T.settle + 1))));

    if (tt < T.p4 || !snap) {
      const si = stormI(tt);
      const lg = ss(prog(tt, T.lead, T.lead + 1.2)) * (1 - ss(prog(tt, T.p4 + 1, T.rotEnd)));
      leadGlow(birds[0].pos.x, birds[0].pos.y, lg);
      drawWind(tt);
      const order = birds.slice().sort((a, b) => a.pos.y - b.pos.y);
      for (const b of order) {
        const settle = b.joinW;
        const size = lerp(b.size, b.i === 0 ? 1.0 : 0.78 + (b.pos.y - APEX.y) / 2400, settle);
        const wq = W(tt), wob = si * (1 - settle) * 0.55 * Math.sin(tt * 4.2 + b.i * 1.9) + (b.i === HURT ? 1.25 * wq.hit * (1 - wq.rec) * (1 - 0.45 * wq.cover) * Math.sin(tt * 6.5) : 0);
        const squash = lerp(1, 0.86, ss(prog(tt, T.p3 - 0.5, T.p3 + 1.5)));
        drawBird(b.pos.x, b.pos.y, size, b.head, b.flap, { lead: b.i === 0 ? ss(prog(tt, T.lead, T.lead + 1)) : 0, wob, squash });
        POS[b.i] = { x: b.pos.x, y: b.pos.y };
      }
      return;
    }

    // chapter 4: the arrow formation glides into the logo's arrow, the birds settle into it, then the logo unrolls
    const e = eio(prog(tt, T.p4, T.rotEnd));
    const m = ss(prog(tt, T.rotEnd - 0.05, T.rotEnd + 0.45));
    setCam(tt);
    drawV(m);
    const order = [...slots.keys()].sort((a, b) => slots[a].dy - slots[b].dy);
    for (const i of order) {
      const tgt = cam(WORD.slot(slots[i].dx, slots[i].dy));
      const lag = e * 1.25 + slots[i].dx * 0.06;                  // the leader arrives first, the rows follow
      const k = eio(clamp(lag));
      // then every bird darts forward out of the arrow, leader first, and the arrow is left behind them
      const d = clamp((tt - (T.rotEnd - 0.2 + (-slots[i].dx) * 0.045)) / 0.8), dd = Math.pow(d, 2.1);
      const x = lerp(snap[i].x, tgt.x, k) + 2300 * dd, y = lerp(snap[i].y, tgt.y, k) + slots[i].dy * 26 * dd;
      if (d >= 1) continue;
      const head = angLerp(snap[i].head, 0, k);
      const size = lerp(i === 0 ? 1.0 : 0.8, 0.25 * CAM.Z, k) * lerp(1, 0.7, d);
      const flap = birds[i].flap + (tt - T.p4) * 1.3 * Math.PI * 2;
      const al = 1 - ss(prog(d, 0.55, 1));
      if (d > 0) {
        const len = (60 + 520 * d) * Math.max(0.4, size), g = ctx.createLinearGradient(x - len, y, x, y);
        g.addColorStop(0, `rgba(${P.push},0)`); g.addColorStop(1, `rgba(${P.push},${0.55 * al})`);
        ctx.fillStyle = g; ctx.fillRect(x - len, y - 1.5, len, 3);
      }
      drawBird(x, y, size, head, flap, { lead: i === 0 ? 1 : 0, alpha: al, glide: Math.max(d, ss(prog(tt, T.p4 + 0.8, T.rotEnd))), squash: lerp(0.86, 1, k) });
      POS[i] = { x, y };
    }
    drawWord(tt);
  }

  // ---------- timing & controls ----------
  const fade = tt => {
    const a = prog(tt, T.hold, T.end);
    if (a > 0) { ctx.fillStyle = `rgba(244,247,241,${ss(a)})`; ctx.fillRect(0, 0, LW, LH); }
  };
  function seek(target) {
    reset();
    while (simT < Math.min(target, T.p4 + DT)) step();
    t = target;
  }
  let playing = true, last = null, acc = 0;
  const playBtn = document.getElementById("play");
  const chapEl = document.getElementById("chapters");
  CHAPTERS.forEach((c, j) => {
    const btn = document.createElement("button");
    btn.className = "chap"; btn.type = "button"; btn.id = "chap" + j;
    btn.innerHTML = `<b>${c.n}</b><span class="lbl">${c.label}</span><span class="bar"><i></i></span>`;
    btn.addEventListener("click", () => { seek(c.a); setPlaying(true); });
    chapEl.appendChild(btn);
  });
  function setPlaying(p) { playing = p; playBtn.textContent = p ? "Tạm dừng" : "Phát"; last = null; }
  playBtn.addEventListener("click", () => setPlaying(!playing));
  document.getElementById("replay").addEventListener("click", () => { seek(START); setPlaying(true); });

  function resize() {
    const r = cv.getBoundingClientRect(), dpr = Math.min(2, window.devicePixelRatio || 1);
    cv.width = Math.round(r.width * dpr); cv.height = Math.round(r.height * dpr);
  }
  window.addEventListener("resize", resize);

  function updateRail() {
    CHAPTERS.forEach((c, j) => {
      const el = document.getElementById("chap" + j);
      const on = t >= c.a && t < c.b;
      el.setAttribute("aria-current", on ? "true" : "false");
      el.querySelector(".bar i").style.width = (prog(t, c.a, c.b) * 100).toFixed(1) + "%";
    });
  }
  function applyView() {
    const s = Math.min(cv.width / LW, cv.height / LH), ox = (cv.width - LW * s) / 2, oy = (cv.height - LH * s) / 2;
    ctx.setTransform(1, 0, 0, 1, 0, 0); ctx.fillStyle = P.ground; ctx.fillRect(0, 0, cv.width, cv.height);
    ctx.setTransform(s, 0, 0, s, ox, oy);
    const css = cv.width / (cv.clientWidth || cv.width);
    const cta = document.querySelector(".hero-cta");
    if (cta) { const on = t > T.cta && t < T.hold + 0.4; cta.classList.toggle("on", on); cta.firstElementChild.tabIndex = on ? 0 : -1; }
    if (cta && WORD) cta.style.setProperty("--cta-y", ((oy + (WORD.ly + WORD.lh + 64) * s) / css).toFixed(1) + "px");
    KICK.forEach((r, j) => {
      const el = document.getElementById("k" + j);
      if (!el || !r) return;
      el.style.left = ((ox + r.x * s) / css).toFixed(1) + "px"; el.style.top = ((oy + r.y * s) / css).toFixed(1) + "px";
      el.style.width = (r.w * s / css).toFixed(1) + "px"; el.style.height = (r.h * s / css).toFixed(1) + "px";
      const on = t > [T.m1, T.m2, T.m3][j] + 0.6 && t < T.hold;   // from the clock, so a replay hides them again
      el.classList.toggle("on", on); el.tabIndex = on ? 0 : -1;
    });
  }
  function frame(now) {
    if (playing) {
      if (last !== null) {
        acc += Math.min(0.1, (now - last) / 1000);
        while (acc >= DT) {
          acc -= DT;
          if (t < T.p4) step();
          t += DT;
          if (t >= T.end) { seek(START); }
        }
      }
      last = now;
    }
    applyView();
    render(t); fade(t);
    updateRail();
    requestAnimationFrame(frame);
  }
  // ---------- the small story frame opened from the final messages ----------
  const MINI = { W: 520, H: 280 };
  function miniScene(c, j, tt) {
    const main = ctx; ctx = c.getContext("2d");
    const k = c.width / MINI.W;
    ctx.setTransform(k, 0, 0, k, 0, 0);
    const g = ctx.createLinearGradient(0, 0, 0, MINI.H);
    g.addColorStop(0, "#03140f"); g.addColorStop(1, "#0a3f33");
    ctx.fillStyle = g; ctx.fillRect(0, 0, MINI.W, MINI.H);
    const hg = ctx.createRadialGradient(MINI.W / 2, MINI.H + 60, 10, MINI.W / 2, MINI.H + 60, 300);
    hg.addColorStop(0, "rgba(170,240,120,.45)"); hg.addColorStop(1, "rgba(0,0,0,0)");
    ctx.fillStyle = hg; ctx.fillRect(0, 0, MINI.W, MINI.H);
    const streaks = (n, speed, a) => { for (let i = 0; i < n; i++) { const yy = 20 + (i * 47) % 240, len = 40 + (i * 29) % 70; const x = MINI.W + 80 - ((tt * speed + i * 137) % (MINI.W + 160)); ctx.fillStyle = `rgba(142,240,178,${a})`; ctx.fillRect(x, yy, len, 1.5); } };
    if (j === 0) {
      const L = 5.2, u = tt % L, go = ss(prog(u, 0.5, 3.6));
      streaks(10, 120, 0.12 * (1 - go) + 0.05);
      const lx = lerp(110, 390, go), ly = lerp(205, 120, go);
      const scat = [[60, 70, 2.6], [170, 60, -0.4], [250, 200, 3.4], [330, 230, 0.7], [440, 70, 2.2], [90, 150, 4.0]];
      const halo = ctx.createRadialGradient(lx, ly, 0, lx, ly, 70); halo.addColorStop(0, `rgba(255,212,92,${0.35 * go + 0.1})`); halo.addColorStop(1, "rgba(0,0,0,0)");
      ctx.fillStyle = halo; ctx.beginPath(); ctx.arc(lx, ly, 70, 0, Math.PI * 2); ctx.fill();
      scat.forEach(([x, y, h], i) => {
        const f = ss(prog(u, 1.0 + i * 0.22, 3.9 + i * 0.08)), side = i % 2 ? 1 : -1, rank = 1 + (i >> 1);
        const tx = lx - 52 * rank, ty = ly + side * 26 * rank;
        drawBird(lerp(x, tx, f), lerp(y, ty, f), 0.42, angLerp(h, -0.26, f), tt * 9 + i, {});
      });
      drawBird(lx, ly, 0.56, lerp(-0.1, -0.26, go), tt * 8, { lead: 1 });
    } else if (j === 1) {
      const u = tt % 6, hit = ss(prog(u, 0.6, 1.0)), cover = ss(prog(u, 1.1, 1.9)) * (1 - ss(prog(u, 4.6, 5.6))), rec = ss(prog(u, 2.4, 3.8));
      const gust = ss(prog(u, 0.3, 0.7)) * (1 - ss(prog(u, 4.8, 5.6)));
      const base = [[0, 0], [-1, -1], [-1, 1], [-2, -2], [-2, 0], [-2, 2]].map(([dx, dy], i) => ({ x: 360 + dx * 64, y: 160 + dy * 30 + Math.sin(tt * 1.4 + i) * 3 }));
      const k = hit * (1 - rec), Hp = { x: base[3].x - 52 * k, y: base[3].y - 22 * k };
      const help = [1, 4].map((i, n) => { const side = n ? 1 : -1; return { x: lerp(base[i].x, Hp.x + WU.x * 38 + WN.x * 17 * side, cover), y: lerp(base[i].y, Hp.y + WU.y * 38 + WN.y * 17 * side, cover) }; });
      const S = { x: (help[0].x + help[1].x) / 2, y: (help[0].y + help[1].y) / 2 }, C = { x: lerp(Hp.x + WU.x * 12, S.x, cover), y: lerp(Hp.y + WU.y * 12, S.y, cover) };
      if (cover > 0.05) { const pk = ctx.createRadialGradient(Hp.x, Hp.y, 0, Hp.x, Hp.y, 54); pk.addColorStop(0, `rgba(142,240,178,${0.25 * cover})`); pk.addColorStop(1, "rgba(0,0,0,0)"); ctx.fillStyle = pk; ctx.beginPath(); ctx.arc(Hp.x, Hp.y, 54, 0, Math.PI * 2); ctx.fill(); }
      gustDashes({ x: Hp.x + WU.x * 8, y: Hp.y + WU.y * 8 }, { x: S.x + WU.x * 16, y: S.y + WU.y * 16 }, cover, gust, tt, 0.6);
      base.forEach((p, i) => {
        if (i === 3) drawBird(Hp.x, Hp.y, 0.42, 0, tt * 8 + i, { wob: 1.2 * k * (1 - 0.5 * cover) * Math.sin(tt * 6.5) });
        else if (i === 1 || i === 4) { const h = help[i === 1 ? 0 : 1]; drawBird(h.x, h.y, 0.42, 0, tt * 9 + i, {}); }
        else drawBird(p.x, p.y, i === 0 ? 0.5 : 0.42, 0, tt * 8 + i, { lead: i === 0 ? 1 : 0 });
      });
    } else {
      const u = (tt % 3.4) / 3.4;
      streaks(16, 420, 0.22);
      const cx = lerp(-60, MINI.W + 160, Math.pow(u, 1.4));
      for (let r = 0; r <= 3; r++) for (let q = 0; q <= r; q++) drawBird(cx - r * 52, 140 + (2 * q - r) * 26, r === 0 ? 0.5 : 0.4, 0, tt * 10 + r + q, { lead: r === 0 ? 1 : 0 });
    }
    ctx = main;
  }
  // hovering (or focusing) a final message opens a small preview right under it; leaving closes it
  (function storyPreview() {
    const pop = document.getElementById("story");
    if (!pop) return;
    const mc = document.getElementById("mini"), stage = document.getElementById("stage");
    let raf = 0, t0 = 0, cur = -1, hideT = 0;
    const reduce = window.matchMedia && matchMedia("(prefers-reduced-motion: reduce)").matches;
    const loop = now => { if (!t0) t0 = now; miniScene(mc, cur, (now - t0) / 1000); raf = requestAnimationFrame(loop); };
    const place = btn => {
      const sr = stage.getBoundingClientRect(), br = btn.getBoundingClientRect(), w = pop.offsetWidth, h = pop.offsetHeight;
      let left = br.left - sr.left + br.width / 2 - w / 2;
      left = Math.max(12, Math.min(sr.width - w - 12, left));
      let top = br.bottom - sr.top + 12;
      if (top + h > sr.height - 12) top = Math.max(12, br.top - sr.top - h - 12);
      pop.style.left = left + "px"; pop.style.top = top + "px";
    };
    const show = j => {
      clearTimeout(hideT);
      const btn = document.getElementById("k" + j);
      if (cur !== j) {
        cur = j; t0 = 0;
        document.getElementById("storyKicker").textContent = TELL[j].kicker;
        document.getElementById("storyTitle").textContent = TELL[j].title;
        document.getElementById("storyBody").textContent = TELL[j].body;
        const dpr = Math.min(2, window.devicePixelRatio || 1);
        mc.width = MINI.W * dpr; mc.height = MINI.H * dpr;
      }
      pop.hidden = false; place(btn);
      requestAnimationFrame(() => pop.classList.add("on"));
      btn.setAttribute("aria-expanded", "true");
      cancelAnimationFrame(raf);
      if (reduce) miniScene(mc, j, 3); else raf = requestAnimationFrame(loop);
    };
    const hide = (now) => {
      clearTimeout(hideT);
      hideT = setTimeout(() => {
        pop.classList.remove("on"); cancelAnimationFrame(raf);
        [0, 1, 2].forEach(j => document.getElementById("k" + j)?.setAttribute("aria-expanded", "false"));
        setTimeout(() => { if (!pop.classList.contains("on")) { pop.hidden = true; cur = -1; } }, 260);
      }, now ? 0 : 140);
    };
    [0, 1, 2].forEach(j => {
      const b = document.getElementById("k" + j);
      if (!b) return;
      b.addEventListener("mouseenter", () => show(j));
      b.addEventListener("mouseleave", () => hide());
      b.addEventListener("focus", () => show(j));
      b.addEventListener("blur", () => hide());
      b.addEventListener("click", () => (cur === j && pop.classList.contains("on")) ? hide(true) : show(j));   // touch screens
    });
    pop.addEventListener("mouseenter", () => clearTimeout(hideT));
    document.getElementById("replay").addEventListener("click", () => hide(true));
    document.querySelector(".scroll")?.addEventListener("click", e => { e.preventDefault(); const to = document.querySelector(e.currentTarget.getAttribute("href")); to ? to.scrollIntoView({ behavior: "smooth" }) : window.scrollBy({ top: window.innerHeight, behavior: "smooth" }); });
    pop.addEventListener("mouseleave", () => hide());
    document.addEventListener("keydown", e => { if (e.key === "Escape") hide(true); });
  })();

  window.__seek = (x) => { seek(x); setPlaying(false); applyView(); render(t); fade(t); updateRail(); };

  const reduce = window.matchMedia && matchMedia("(prefers-reduced-motion: reduce)").matches;
  const start = () => {
    layoutWord(); resize(); measureMessages(); seek(START);
    if (reduce) { seek(30); setPlaying(false); }
    requestAnimationFrame(frame);
  };
  Promise.race([
    Promise.all([document.fonts.load(DF(P.dw, 100)), document.fonts.load('500 30px "Be Vietnam Pro"')]),
    new Promise(r => setTimeout(r, 1500)),
  ]).then(start, start);
})();
