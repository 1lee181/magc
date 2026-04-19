/* ============================================================
   VentureHub - main.js
   Features:
     - Navbar mobile toggle
     - Timeline scroll-reveal (IntersectionObserver)
     - Stats counter animation
     - Seamless ribbon loop (requestAnimationFrame)
     - Image hover gradient that follows the mouse
     - Member sign-up form AJAX
   ============================================================ */

'use strict';

// ── Navbar toggle ───────────────────────────────────────────
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');

if (navToggle) {
  navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
}
document.querySelectorAll('.navbar-links a').forEach(link => {
  link.addEventListener('click', () => navLinks && navLinks.classList.remove('open'));
});

// ── Timeline reveal (Caden) ─────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.2 });

document.querySelectorAll('.timeline-item').forEach(el => revealObserver.observe(el));

// ── Stats counter (Caden) ───────────────────────────────────
function animateCounter(el) {
  const target   = parseInt(el.dataset.target, 10);
  const duration = 1200;
  const step     = target / (duration / 16);
  let current    = 0;
  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      el.textContent = target + '+';
      clearInterval(timer);
    } else {
      el.textContent = Math.floor(current);
    }
  }, 16);
}

const statsObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      animateCounter(entry.target);
      statsObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-num[data-target]').forEach(el => statsObserver.observe(el));

// ── Seamless ribbon (Matthew) ───────────────────────────────
// Strategy: clone items until the FIRST HALF of the track is wider than
// the viewport. Then the snap from pos>=halfW back to 0 is always
// within the first set, so the viewer never sees a gap.
(function initRibbon() {
  const track = document.getElementById('ribbonTrack');
  if (!track) return;

  // Step 1: clone all existing items to fill out the track
  // We'll keep doubling until the natural (pre-transform) width > 3 × window
  function fillTrack() {
    const originals = Array.from(track.children);
    if (originals.length === 0) return;
    // Clone until we have at least 3× the viewport width worth of items
    let safetyLimit = 20;
    while (track.scrollWidth < window.innerWidth * 3 && safetyLimit-- > 0) {
      originals.forEach(item => track.appendChild(item.cloneNode(true)));
    }
  }

  fillTrack();
  window.addEventListener('resize', () => { fillTrack(); halfW = 0; });

  let pos    = 0;
  const speed  = 0.55;
  let paused = false;
  let halfW  = 0;

  function measure() {
    // halfW = half the scrollWidth = one "natural" set length.
    // Since we've cloned to 3×+ viewport, the first half always covers viewport.
    const sw = track.scrollWidth;
    if (sw > 0) halfW = sw / 2;
  }

  track.addEventListener('mouseenter', () => { paused = true; });
  track.addEventListener('mouseleave', () => { paused = false; });

  function tick() {
    if (halfW === 0) measure();

    if (!paused && halfW > 0) {
      pos += speed;
      if (pos >= halfW) {
        pos = pos - halfW;
      }
      track.style.transform = `translateX(-${pos}px)`;
    }
    requestAnimationFrame(tick);
  }

  requestAnimationFrame(tick);
})();

// ── Image hover gradient — true triangle (tip at mouse, base at far corners) ──
document.querySelectorAll('.img-hover-wrap').forEach(wrap => {
  const overlay = wrap.querySelector('.img-hover-overlay');
  if (!overlay) return;

  wrap.addEventListener('mousemove', (e) => {
    const rect = wrap.getBoundingClientRect();
    const mx = (e.clientX - rect.left) / rect.width;
    const my = (e.clientY - rect.top)  / rect.height;

    // Tip of triangle = mouse position
    // Base: two corners on the opposite side of the image from the mouse
    // We pick the two corners that are furthest from the cursor
    // Strategy: rotate 120deg from mouse direction to get two base points at image edges

    // Direction from mouse to image center (inverted = away from mouse)
    const cx = 0.5, cy = 0.5;
    const dx = cx - mx, dy = cy - my;
    const len = Math.sqrt(dx*dx + dy*dy) || 1;
    const nx = dx/len, ny = dy/len; // unit vector pointing away from mouse toward center

    // Two base vertices: rotate nx,ny by ±70 degrees, project far outside image
    const angle = 70 * Math.PI / 180;
    function rotated(vx, vy, a) {
      return [vx*Math.cos(a) - vy*Math.sin(a), vx*Math.sin(a) + vy*Math.cos(a)];
    }

    const scale = 2.0; // extend well beyond the image edges
    const [r1x, r1y] = rotated(nx, ny,  angle);
    const [r2x, r2y] = rotated(nx, ny, -angle);

    const tx1 = ((mx + r1x * scale) * 100).toFixed(1) + '%';
    const ty1 = ((my + r1y * scale) * 100).toFixed(1) + '%';
    const tx2 = ((mx + r2x * scale) * 100).toFixed(1) + '%';
    const ty2 = ((my + r2y * scale) * 100).toFixed(1) + '%';

    overlay.style.setProperty('--mx', (mx * 100).toFixed(1) + '%');
    overlay.style.setProperty('--my', (my * 100).toFixed(1) + '%');
    overlay.style.setProperty('--tx1', tx1);
    overlay.style.setProperty('--ty1', ty1);
    overlay.style.setProperty('--tx2', tx2);
    overlay.style.setProperty('--ty2', ty2);
  });
});

// ── Member Sign-Up Form (Gurehmat) ─────────────────────────
const emailInput = document.getElementById('memberEmail');
const emailMsg   = document.getElementById('emailMsg');
const joinSubmit = document.getElementById('joinSubmit');
const formAlert  = document.getElementById('formAlert');

let emailValid = false;
let emailTimer = null;

if (emailInput) {
  emailInput.addEventListener('input', () => {
    clearTimeout(emailTimer);
    emailMsg.className = 'field-msg';
    emailMsg.textContent = '';
    emailValid = false;

    const val = emailInput.value.trim();
    if (!val || !val.includes('@')) return;

    emailMsg.textContent = 'Checking...';
    emailMsg.style.display = 'block';
    emailMsg.style.color = '#999';

    emailTimer = setTimeout(() => {
      const fd = new FormData();
      fd.append('action', 'check_email');
      fd.append('email', val);

      fetch(BASE + '/api/members.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
          if (data.taken) {
            emailMsg.textContent = 'This email is already registered.';
            emailMsg.className = 'field-msg error';
            emailValid = false;
          } else {
            emailMsg.textContent = 'Email is available.';
            emailMsg.className = 'field-msg ok';
            emailValid = true;
          }
        })
        .catch(() => {
          emailMsg.style.display = 'none';
          emailValid = true;
        });
    }, 500);
  });
}

if (joinSubmit) {
  joinSubmit.addEventListener('click', () => {
    const name    = document.getElementById('memberName').value.trim();
    const email   = document.getElementById('memberEmail').value.trim();
    const program = document.getElementById('memberProgram').value.trim();
    const year    = document.getElementById('memberYear').value;

    if (!name)                      { showFormAlert('Please enter your full name.', 'error'); return; }
    if (!email || !email.includes('@')) { showFormAlert('Please enter a valid email address.', 'error'); return; }
    if (!program)                   { showFormAlert('Please enter your program.', 'error'); return; }
    if (!year)                      { showFormAlert('Please select your year of study.', 'error'); return; }
    if (!emailValid)                { showFormAlert('Please wait for the email check, or try a different address.', 'error'); return; }

    joinSubmit.disabled = true;
    joinSubmit.innerHTML = 'Submitting <span class="spinner"></span>';

    const fd = new FormData();
    fd.append('action',  'submit');
    fd.append('name',    name);
    fd.append('email',   email);
    fd.append('program', program);
    fd.append('year',    year);
    fd.append('why',     document.getElementById('memberWhy').value.trim());

    fetch(BASE + '/api/members.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          showFormAlert('Thanks for submitting. We will be in touch soon.', 'success');
          ['memberName','memberEmail','memberProgram','memberYear','memberWhy']
            .forEach(id => { document.getElementById(id).value = ''; });
          emailMsg.className = 'field-msg';
          emailMsg.textContent = '';
          emailValid = false;
        } else {
          showFormAlert(data.error || 'Something went wrong. Please try again.', 'error');
        }
      })
      .catch(() => showFormAlert('Network error. Please try again.', 'error'))
      .finally(() => {
        joinSubmit.disabled = false;
        joinSubmit.textContent = 'Submit';
      });
  });
}

function showFormAlert(msg, type) {
  formAlert.textContent = msg;
  formAlert.className = 'form-alert ' + type;
  formAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}