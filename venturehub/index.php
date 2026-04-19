<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$execs      = $db->query('SELECT * FROM executives ORDER BY display_order ASC')->fetchAll();
$events     = $db->query('SELECT * FROM past_events ORDER BY event_date DESC')->fetchAll();
$partners   = $db->query('SELECT * FROM partners ORDER BY display_order ASC')->fetchAll();
$ribbonItems = array_merge($partners, $partners);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MVCC | McMaster Venture Capital Club</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="icon" type="image/png" href="images/mvcc-logo.png">
  <link rel="stylesheet" href="<?= BASE ?>/css/style.css">
</head>

<body>

  <?php if (isAdmin()): ?>
    <div class="admin-bar">
      Logged in as admin &mdash;
      <a href="<?= BASE ?>/pages/admin/dashboard.php">Admin Panel</a>
      &nbsp;/&nbsp;
      <a href="<?= BASE ?>/api/auth.php?action=logout">Logout</a>
    </div>
  <?php endif; ?>

  <!-- ══════════════════════════════════════════
     NAVBAR
══════════════════════════════════════════ -->
  <nav class="navbar">
    <div class="navbar-inner">
      <a class="navbar-brand" href="<?= BASE ?>/">
        <img src="<?= BASE ?>/images/mvcc-logo.png" alt="MVCC">
      </a>
      <button class="navbar-toggle" id="navToggle" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
      <ul class="navbar-links" id="navLinks">
        <li><a href="#history">History</a></li>
        <li><a href="#events">Events</a></li>
        <li><a href="#team">Team</a></li>
        <li><a href="#join">Join</a></li>
        <li><a href="#partners">Partners</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </div>
  </nav>

  <!-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ -->
  <section id="hero">
    <div class="hero-inner">
      <div class="hero-left">
        <div class="hero-eyebrow">McMaster &mdash; Hamilton, Ontario</div>
        <h1 class="hero-title">
          <span class="hero-watermark" aria-hidden="true">MVCC</span>
          <em>McMaster</em><br>
          Venture<br>
          Capital Club
        </h1>
        <p class="hero-sub">Connecting students with investors. Building the next generation of founders from the ground up.</p>
        <div class="hero-buttons">
          <a href="#join" class="btn btn-primary">Join MVCC</a>
          <a href="#history" class="btn btn-outline">Our Story</a>
        </div>
      </div>

      <div class="hero-right">
        <div class="hero-frame-wrap">
          <div class="hero-frame-border"></div>
          <div class="img-hover-wrap hero-portrait">
            <img src="<?= BASE ?>images/pitch night.JPG" alt="MVCC Pitch Night">
            <div class="img-hover-overlay"></div>
          </div>
        </div>
      </div>
    </div><!-- .hero-inner -->

    <div class="hero-rule"></div>
  </section>

  <!-- ══════════════════════════════════════════
     CLUB HISTORY & STATS — Caden
══════════════════════════════════════════ -->
  <section id="history" class="section">
    <div class="section-inner">
      <div class="section-label">Since Year One</div>
      <h2 class="section-title"><em>Club History</em><br>&amp; Stats</h2>

      <div class="stats-row">
        <div class="stat-box">
          <span class="stat-num" id="stat-members" data-target="100">0</span>
          <div class="stat-label">General Members</div>
        </div>
        <div class="stat-box">
          <span class="stat-num" data-target="5">0</span>
          <div class="stat-label">Years Active</div>
        </div>
        <div class="stat-box">
          <span class="stat-num" id="stat-events" data-target="30">0</span>
          <div class="stat-label">Events Hosted</div>
        </div>
        <div class="stat-box">
          <span class="stat-num" id="stat-execs" data-target="7">0</span>
          <div class="stat-label">Executives</div>
        </div>
      </div>

      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-year">Year 1</div>
          <div class="timeline-text">Forge/Innovation Factory partnerships, MVCC x Forge coaching system, structured scouting + newsletter engine, sponsorships (seed capital base), 10+ startups coached, 1–2 case comps, Waterloo VG ties, 1000+ followers</div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">Year 2</div>
          <div class="timeline-text">Formal deal pipeline + investment memo system, first $5–10k angel facilitation / micro-investments (alumni/sponsor-backed), VC Summit, VC partnerships, 20+ startups coached, 2–3 deals</div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">Year 3</div>
          <div class="timeline-text">Scale structured deal flow (30+ startups), launch angel syndicate (10–15 HNIs), national case competition, transition into repeatable investment process, 3–5 investments</div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">Year 4</div>
          <div class="timeline-text">Expand into deal-scouting model for VC funds, global VC partnerships (Sequoia/a16z), strengthen alumni capital + VC/IB pipeline, newsletter becomes sourcing channel, 5–7 deals, 1000+ subs</div>
        </div>
        <div class="timeline-item">
          <div class="timeline-year">Year 5</div>
          <div class="timeline-text">MVCC Fund I ($250K–$500K) institutionalized microfund, formal investment committee + portfolio system, 10+ portfolio startups, national VC summit, international student VC partnerships</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
     PAST EVENTS — Matthew
══════════════════════════════════════════ -->
  <section id="events" class="section section-alt">
    <div class="section-inner">
      <div class="section-label">What We Have Built</div>
      <h2 class="section-title"><em>Past & Future</em> Events</h2>
      <p class="section-lead">A look at the rooms we have created over the years.</p>

      <?php if (empty($events)): ?>
        <p style="color:var(--taupe); font-size:0.9rem;">No events to display yet.</p>
      <?php else: ?>
        <div class="cards-grid">
          <?php foreach ($events as $e): ?>
            <div class="card">
              <?php if (!empty($e['photo_url'])): ?>
                <div class="img-hover-wrap">
                  <img class="card-img" src="<?= htmlspecialchars($e['photo_url']) ?>" alt="<?= htmlspecialchars($e['title']) ?>">
                  <div class="img-hover-overlay"></div>
                </div>
              <?php else: ?>
                <div class="card-img-placeholder">No Photo</div>
              <?php endif; ?>
              <div class="card-body">
                <div class="card-title"><?= htmlspecialchars($e['title']) ?></div>
                <div class="card-meta">
                  <?php if ($e['event_date']): echo date('M j, Y', strtotime($e['event_date']));
                  endif; ?>
                  <?php if ($e['location']): ?> &nbsp;&mdash;&nbsp; <?= htmlspecialchars($e['location']) ?><?php endif; ?>
                </div>
                <?php if ($e['description']): ?>
                  <div class="card-text"><?= nl2br(htmlspecialchars($e['description'])) ?></div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
     EXECUTIVE MEMBERS — Aleesha
══════════════════════════════════════════ -->
  <section id="team" class="section section-sand">
    <div class="section-inner">
      <div class="section-label">The People</div>
      <h2 class="section-title"><em>Meet</em> the Team</h2>
      <p class="section-lead">The executives running MVCC this year.</p>

      <?php if (empty($execs)): ?>
        <p style="color:var(--taupe); font-size:0.9rem;">No executives to display yet.</p>
      <?php else: ?>
        <div class="exec-grid">
          <?php foreach ($execs as $ex): ?>
            <div class="exec-card">
              <?php if (!empty($ex['photo_url'])): ?>
                <div class="img-hover-wrap" style="width:80px;height:80px;border-radius:50%;margin:0 auto 1rem;border:2px solid var(--sand);">
                  <img class="exec-photo" style="border:none;margin:0;" src="<?= htmlspecialchars($ex['photo_url']) ?>" alt="<?= htmlspecialchars($ex['name']) ?>">
                  <div class="img-hover-overlay"></div>
                </div>
              <?php else: ?>
                <div class="exec-photo-placeholder"><?= htmlspecialchars(mb_substr($ex['name'], 0, 1)) ?></div>
              <?php endif; ?>
              <div class="exec-name"><?= htmlspecialchars($ex['name']) ?></div>
              <div class="exec-role"><?= htmlspecialchars($ex['role'] ?? '') ?></div>
              <?php if (!empty($ex['bio'])): ?>
                <div class="exec-bio"><?= htmlspecialchars($ex['bio']) ?></div>
              <?php endif; ?>
              <?php if (!empty($ex['linkedin_url']) || !empty($ex['instagram_url'])): ?>
                <div class="exec-social">
                  <?php if (!empty($ex['linkedin_url'])): ?>
                    <a href="<?= htmlspecialchars($ex['linkedin_url']) ?>" target="_blank" rel="noopener">LinkedIn</a>
                  <?php endif; ?>
                  <?php if (!empty($ex['instagram_url'])): ?>
                    <a href="<?= htmlspecialchars($ex['instagram_url']) ?>" target="_blank" rel="noopener">Instagram</a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
     SIGN-UP FORM — Gurehmat
══════════════════════════════════════════ -->
  <section id="join" class="section section-alt">
    <div class="section-inner" style="text-align:center;">
      <div class="section-label">Get Involved</div>
      <h2 class="section-title"><em>Join</em> MVCC</h2>
      <p class="section-lead" style="margin-left:auto;margin-right:auto;">Fill out the interest form below and we will be in touch.</p>

      <div class="form-wrapper">
        <div class="form-alert" id="formAlert"></div>

        <div class="form-group">
          <label for="memberName">Full Name</label>
          <input type="text" id="memberName" placeholder="Your name" required>
        </div>
        <div class="form-group">
          <label for="memberEmail">Email Address</label>
          <input type="email" id="memberEmail" placeholder="you@mcmaster.ca" required>
          <div class="field-msg" id="emailMsg"></div>
        </div>
        <div class="form-group">
          <label for="memberProgram">Program</label>
          <input type="text" id="memberProgram" placeholder="e.g. Engineering, Commerce">
        </div>
        <div class="form-group">
          <label for="memberYear">Year of Study</label>
          <select id="memberYear">
            <option value="">Select year</option>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
            <option value="5">5th Year</option>
          </select>
        </div>
        <div class="form-group">
          <label for="memberWhy">Why do you want to join? (optional)</label>
          <textarea id="memberWhy" placeholder="Tell us a little about yourself..."></textarea>
        </div>

        <button class="btn btn-primary" id="joinSubmit" style="width:100%; margin-top:0.5rem;">Submit</button>
      </div>
    </div>
  </section>

  <!-- ══════════════════════════════════════════
     PARTNERS RIBBON — Matthew
══════════════════════════════════════════ -->
  <section id="partners" class="section">
    <div class="section-inner">
      <div class="section-label">Who Backs Us</div>
      <h2 class="section-title"><em>Partners</em> &amp; Startups</h2>
      <p class="section-lead">The companies and startups that support MVCC.</p>
    </div>

    <?php if (!empty($ribbonItems)): ?>
      <div class="ribbon-track-wrapper">
        <div class="ribbon-track" id="ribbonTrack">
          <?php foreach ($ribbonItems as $p): ?>
            <a class="ribbon-item" href="<?= htmlspecialchars($p['website_url'] ?: '#') ?>" target="_blank" rel="noopener">
              <?php if (!empty($p['logo_url'])): ?>
                <img class="ribbon-logo" src="<?= htmlspecialchars($p['logo_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
              <?php else: ?>
                <div class="ribbon-logo-placeholder">Logo</div>
              <?php endif; ?>
              <span class="ribbon-name"><?= htmlspecialchars($p['name']) ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php else: ?>
      <div class="section-inner">
        <p style="color:var(--taupe); font-size:0.9rem;">No partners to display yet.</p>
      </div>
    <?php endif; ?>
  </section>

  <!-- ══════════════════════════════════════════
     CONTACT — Aleesha
══════════════════════════════════════════ -->
  <section id="contact">
    <div class="section-label">Say Hello</div>
    <h2 class="section-title"><em>Get</em> In Touch</h2>
    <p>Have a question or want to get involved? Send us an email.</p>
    <a href="mailto:mvcc.connect@gmail.com" class="btn btn-gold">Contact Us</a>
    <div class="contact-links">
      <a href="mailto:mvcc.connect@gmail.com">mvcc.connect@gmail.com</a>
      <span style="opacity:0.3;">|</span>
      <a href="https://www.instagram.com/macventurecapital" target="_blank">instagram.com/macventurecapital</a>
      <span style="opacity:0.3;">|</span>
      <a href="https://www.linkedin.com/company/macventurecapital/" target="_blank">linkedin.com/company/macventurecapital</a>
    </div>
  </section>

  <footer>
    &copy; <?= date('Y') ?> McMaster Venture Capital Club &mdash; Hamilton, Ontario
  </footer>

  <script>
    const BASE = "<?= BASE ?>";
  </script>
  <script src="<?= BASE ?>/js/main.js"></script>
</body>

</html>