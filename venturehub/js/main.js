/* ============================================================
   VentureHub - main.js
   Handles: timeline reveal, stats counter, sign-up form AJAX,
            navbar mobile toggle
   ============================================================ */

'use strict';

// ── Navbar toggle (mobile) ──────────────────────────────────
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');

if (navToggle) {
    navToggle.addEventListener('click', () => {
        navLinks.classList.toggle('open');
    });
}

// Close mobile menu when a link is clicked
document.querySelectorAll('.navbar-links a').forEach(link => {
    link.addEventListener('click', () => navLinks.classList.remove('open'));
});

// ── IntersectionObserver: timeline reveal (Caden) ──────────
const timelineItems = document.querySelectorAll('.timeline-item');

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.2 });

timelineItems.forEach(item => revealObserver.observe(item));

// ── Stats counter animation (Caden) ────────────────────────
const statNums = document.querySelectorAll('.stat-num[data-target]');

function animateCounter(el) {
    const target = parseInt(el.dataset.target, 10);
    const duration = 1200;
    const step = target / (duration / 16);
    let current = 0;

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

statNums.forEach(el => statsObserver.observe(el));

// ── Member Sign-Up Form (Gurehmat) ─────────────────────────
const emailInput   = document.getElementById('memberEmail');
const emailMsg     = document.getElementById('emailMsg');
const joinSubmit   = document.getElementById('joinSubmit');
const formAlert    = document.getElementById('formAlert');

let emailValid = false;  // tracks async email check result
let emailTimer = null;

// Real-time email duplicate check via AJAX
if (emailInput) {
    emailInput.addEventListener('input', () => {
        clearTimeout(emailTimer);
        emailMsg.className = 'field-msg';
        emailMsg.textContent = '';
        emailValid = false;

        const val = emailInput.value.trim();
        if (!val || !val.includes('@')) return;

        emailMsg.textContent = 'Checking...';
        emailMsg.className = 'field-msg';
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
                    emailMsg.textContent = '';
                    emailMsg.style.display = 'none';
                    emailValid = true; // allow submit if check fails
                });
        }, 500);
    });
}

// Form submission
if (joinSubmit) {
    joinSubmit.addEventListener('click', () => {
        const name    = document.getElementById('memberName').value.trim();
        const email   = document.getElementById('memberEmail').value.trim();
        const program = document.getElementById('memberProgram').value.trim();
        const year    = document.getElementById('memberYear').value;

        // Client-side validation
        if (!name) { showFormAlert('Please enter your full name.', 'error'); return; }
        if (!email || !email.includes('@')) { showFormAlert('Please enter a valid email address.', 'error'); return; }
        if (!program) { showFormAlert('Please enter your program.', 'error'); return; }
        if (!year) { showFormAlert('Please select your year of study.', 'error'); return; }
        if (!emailValid) { showFormAlert('Please wait for the email check to complete, or try a different email.', 'error'); return; }

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
                    // Reset form
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
