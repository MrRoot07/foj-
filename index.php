<?php
session_start();
// Basic config
$companyName = "FOJ Express";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo $companyName; ?> · Fast & Reliable Courier Services</title>
  <meta name="description"
    content="<?php echo $companyName; ?> provides same‑day, next‑day, and scheduled courier services with live tracking and exceptional support." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">
  <style>
    :root {
      --bg: #ffffff;
      --panel: #f7f9fc;
      --muted: #556070;
      --text: #0b0d13;
      --brand: #2563eb;
      --brand-2: #06b6d4;
      --ok: #10b981;
      --warn: #f59e0b;
      --danger: #ef4444;
      --ring: 0 0 0 3px rgba(37, 99, 235, .25);
      --radius: 14px;
      --shadow: 0 8px 24px rgba(0, 0, 0, .12), 0 2px 8px rgba(0, 0, 0, .08);
      --shadow-soft: 0 6px 18px rgba(0, 0, 0, .08), inset 0 1px 0 rgba(255, 255, 255, .6);
      --grid-max: 1200px;
    }

    * {
      box-sizing: border-box
    }

    html,
    body {
      margin: 0;
      padding: 0;
      background: var(--bg);
      color: var(--text);
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      scroll-behavior: smooth
    }

    a {
      color: inherit;
      text-decoration: none
    }

    img {
      max-width: 100%;
      display: block
    }

    .container {
      max-width: var(--grid-max);
      margin: 0 auto;
      padding: 0 20px
    }

    /* Header / Nav */
    header {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(255, 255, 255, .85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0, 0, 0, .08);
    }


    .nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 64px
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 800;
      letter-spacing: .2px
    }

    .brand .logo {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--brand), var(--brand-2));
      display: grid;
      place-items: center;
      box-shadow: var(--shadow-soft)
    }

    .brand .logo svg {
      width: 22px;
      height: 22px
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 22px
    }

    .nav-links a {
      opacity: .92
    }

    .nav-cta {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      border: 1px solid rgba(255, 255, 255, .12);
      padding: 10px 14px;
      border-radius: 12px;
      background: transparent;
      color: var(--text);
      font-weight: 600;
      transition: .2s ease;
      box-shadow: none
    }

    .btn:hover {
      transform: translateY(-1px);
      border-color: rgba(255, 255, 255, .22)
    }

    .btn.primary {
      background: linear-gradient(135deg, var(--brand), var(--brand-2));
      border: none;
      box-shadow: var(--shadow)
    }

    .btn.light {
      background: rgba(255, 255, 255, .06)
    }

    .hamburger {
      display: none;
      background: transparent;
      border: none;
      color: var(--text)
    }

    /* Mobile menu */
    @media (max-width: 980px) {
      .nav-links {
        display: none
      }

      .nav-cta {
        display: none
      }

      .hamburger {
        display: inline-flex;
        padding: 8px;
        border-radius: 10px
      }

      .mobile-menu {
        position: fixed;
        inset: 64px 12px auto 12px;
        background: var(--panel);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 16px;
        box-shadow: var(--shadow);
        padding: 14px;
        display: none;
        flex-direction: column;
        gap: 10px
      }

      .mobile-menu a,
      .mobile-menu .btn {
        display: flex
      }

      .mobile-menu.open {
        display: flex
      }
    }

    /* Hero */
    .hero {
      position: relative;
      overflow: hidden
    }

    .hero::before {
      content: "";
      position: absolute;
      inset: -20%;
      background: radial-gradient(60% 60% at 70% 10%, rgba(34, 211, 238, .17), transparent 55%), radial-gradient(50% 50% at 30% 20%, rgba(91, 140, 255, .13), transparent 55%);
      filter: blur(30px)
    }

    .hero .wrap {
      display: grid;
      grid-template-columns: 1.15fr 1fr;
      gap: 36px;
      align-items: center;
      padding: 64px 0
    }

    .eyebrow {
      display: inline-block;
      font-size: 12px;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--muted);
      border: 1px solid rgba(255, 255, 255, .12);
      border-radius: 999px;
      padding: 6px 10px;
      background: rgba(255, 255, 255, .03)
    }

    h1 {
      font-size: clamp(28px, 4vw, 44px);
      line-height: 1.08;
      margin: 16px 0 10px
    }

    .lead {
      font-size: clamp(14px, 1.5vw, 18px);
      color: var(--muted);
      max-width: 60ch
    }

    .cta {
      display: flex;
      gap: 12px;
      margin-top: 18px
    }

    .hero-media {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      background: linear-gradient(160deg, rgba(91, 140, 255, .16), rgba(34, 211, 238, .12));
      border: 1px solid rgba(255, 255, 255, .08);
      box-shadow: var(--shadow)
    }

    .hero-media img {
      aspect-ratio: 16/11;
      object-fit: cover
    }

    /* Trust / Stats */
    .stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin: 40px 0
    }

    .stat {
      background: var(--panel);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 16px;
      padding: 16px;
      box-shadow: var(--shadow-soft)
    }

    .stat h3 {
      margin: 0;
      font-size: 24px
    }

    .stat p {
      margin: 4px 0 0;
      color: var(--muted);
      font-size: 13px
    }

    @media (max-width: 900px) {
      .hero .wrap {
        grid-template-columns: 1fr
      }

      .stats {
        grid-template-columns: repeat(2, 1fr)
      }
    }

    /* Services */
    section {
      padding: 64px 0
    }

    .section-title {
      display: flex;
      align-items: end;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 20px
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px
    }

    .card {
      background: var(--panel);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 18px;
      padding: 18px;
      box-shadow: var(--shadow-soft);
      display: flex;
      flex-direction: column;
      gap: 10px
    }

    .card .icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      background: linear-gradient(135deg, rgba(91, 140, 255, .18), rgba(34, 211, 238, .18))
    }

    .card h4 {
      margin: 6px 0 0
    }

    .card p {
      margin: 0;
      color: var(--muted)
    }

    .mobile-menu {
      display: none;
    }

    @media (max-width: 980px) {
      .cards {
        grid-template-columns: 1fr
      }
    }

    /* About */
    .about {
      display: grid;
      grid-template-columns: 1.1fr 1fr;
      gap: 24px
    }

    .about .panel {
      background: var(--panel);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 18px;
      overflow: hidden
    }

    .about .panel img {
      width: 100%;
      height: 100%;
      min-height: 280px;
      object-fit: cover;
    }


    .about .copy {
      padding: 18px
    }

    @media (max-width: 980px) {
      .about {
        grid-template-columns: 1fr
      }
    }

    /* Gallery */
    .gallery {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 8px
    }

    .gallery img {
      border-radius: 10px;
      height: 140px;
      object-fit: cover
    }

    @media (max-width: 980px) {
      .gallery {
        grid-template-columns: repeat(3, 1fr)
      }
    }

    " + "

    /* Contact */
    .contact {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px
    }

    .contact .panel {
      background: var(--panel);
      border: 1px solid rgba(255, 255, 255, .08);
      border-radius: 18px;
      padding: 18px;
      box-shadow: var(--shadow-soft)
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 12px
    }

    .field input,
    .field textarea,
    .field select {
      background: #ffffff;
      border: 1px solid rgba(0, 0, 0, .12);
      color: var(--text);
    }


    .field input:focus,
    .field textarea:focus {
      box-shadow: var(--ring);
      border-color: transparent
    }

    textarea {
      min-height: 120px;
      resize: vertical
    }

    /* Footer */
    footer {
      border-top: 1px solid rgba(0, 0, 0, .08);
    }
  </style>
</head>

<body>
  <header>
    <div class="container nav">
      <a href="#home" class="brand" aria-label="<?php echo $companyName; ?> Home">
        <span class="logo" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M3 12h9" />
            <path d="M12 6l6 6-6 6" />
          </svg>
        </span>
        <span><?php echo $companyName; ?></span>
      </a>
      <nav class="nav-links" aria-label="Primary">
        <a href="#services">Services</a>
        <a href="#about">About</a>
        <a href="#gallery">Gallery</a>
        <a href="#contact">Contact</a>
      </nav>
      <div class="nav-cta">
        <?php if (isset($_SESSION['auth'])): ?>
          <div class="nav-cta">
            <a class="btn" href="tracking.php">Tracking</a>
            <a class="btn primary" href="request.php">Request</a>
            <a class="btn light" href="logout.php">Logout</a>
          </div>
        <?php else: ?>
          <div class="nav-cta">
            <a class="btn light" href="login.php">Log in</a>
            <a class="btn primary" href="register.php">Register</a>
          </div>
        <?php endif; ?>

      </div>
      <button class="hamburger" aria-label="Open menu" onclick="toggleMenu()">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M3 6h18M3 12h18M3 18h18" />
        </svg>
      </button>
    </div>
    <div id="mobileMenu" class="mobile-menu container" role="dialog" aria-modal="true" aria-label="Mobile menu">
      <?php if (isset($_SESSION['auth'])): ?>
        <a class="btn primary" href="request.php">Request</a>
        <a class="btn light" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn light" href="login.php">Log in</a>
        <a class="btn primary" href="register.php">Register</a>
      <?php endif; ?>

    </div>
  </header>

  <main id="home" class="hero">
    <div class="container wrap">
      <div>
        <span class="eyebrow">Fast • Secure • Nationwide</span>
        <h1>
          Express courier services that move at your speed
        </h1>
        <p class="lead">
          <?php echo $companyName; ?> delivers parcels and documents with precision logistics, live tracking, and
          support that actually answers.
        </p>
        <div class="cta">
          <a href="#services" class="btn primary">View Services</a>
          <a href="#contact" class="btn">Get a Quote</a>
        </div>
        <div class="stats">
          <div class="stat">
            <h3>10K+</h3>
            <p>Parcels delivered monthly</p>
          </div>
          <div class="stat">
            <h3>99.8%</h3>
            <p>On‑time performance</p>
          </div>
          <div class="stat">
            <h3>24/7</h3>
            <p>Customer support</p>
          </div>
          <div class="stat">
            <h3>180+</h3>
            <p>Covered cities</p>
          </div>
        </div>
      </div>
      <div class="hero-media">
        <img src="./assets/images/2.jpg"
          alt="Courier loading parcels into a delivery van" />
      </div>
    </div>
  </main>

  <section id="services">
    <div class="container">
      <div class="section-title">
        <h2>Our Services</h2>
        <a href="#contact" class="btn">Request a custom quote</a>
      </div>
      <div class="cards">
        <article class="card">
          <div class="icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
              <path d="M3 7h13l5 5v5a2 2 0 0 1-2 2H3z" />
              <path d="M16 7v6h6" />
            </svg>
          </div>
          <h4>Same‑Day Delivery</h4>
          <p>Priority pickup within hours and direct‑to‑door delivery across the city with live tracking.</p>
        </article>
        <article class="card">
          <div class="icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
              <path d="M3 3h18v4H3zM3 17h18v4H3z" />
              <path d="M3 11h18" />
            </svg>
          </div>
          <h4>Next‑Day & Scheduled</h4>
          <p>Reliable next‑day and recurring routes for businesses with predictable logistics needs.</p>
        </article>
        <article class="card">
          <div class="icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
              <path d="M21 15V6a2 2 0 0 0-2-2h-4" />
              <path d="M3 7v11a2 2 0 0 0 2 2h9" />
              <path d="M3 7l5-5h6l-5 5z" />
            </svg>
          </div>
          <h4>Documents & Secure</h4>
          <p>Chain‑of‑custody handling for legal, financial, and sensitive shipments with signature capture.</p>
        </article>
      </div>
    </div>
  </section>

  <section id="about">
    <div class="container about">
      <div class="panel">
        <img src="./assets/images/1.jpg"
          alt="Team coordinating deliveries in a modern operations center" />
      </div>
      <div class="panel copy">
        <h2>About <?php echo $companyName; ?></h2>
        <p>
          Founded by logistics veterans, <?php echo $companyName; ?> blends human expertise with smart routing to
          deliver exceptional reliability. Our network spans regional hubs and partner carriers, with a single service
          level agreement you can trust.
        </p>
        <ul>
          <li>Transparent pricing and SLAs</li>
          <li>Real‑time parcel tracking</li>
          <li>Insured shipments with proof of delivery</li>
        </ul>
        <div class="cta">
          <a href="#contact" class="btn primary">Work with us</a>
          <a href="#gallery" class="btn">See operations</a>
        </div>
      </div>
    </div>
  </section>

  <!-- <section id="gallery">
    <div class="container">
      <div class="section-title">
        <h2>In the Field</h2>
        <span style="color:var(--muted);font-size:14px">Snapshots from daily operations</span>
      </div>
      <div class="gallery">
        <img src="https://images.unsplash.com/photo-1541417904950-b855846fe074?q=80&w=1200&auto=format&fit=crop"
          alt="Stacked parcels in a warehouse" />
        <img src="https://images.unsplash.com/photo-1554631221-f9603e6808be?q=80&w=1200&auto=format&fit=crop"
          alt="Delivery scooter on a city street" />
        <img src="https://images.unsplash.com/photo-1529070538774-1843cb3265df?q=80&w=1200&auto=format&fit=crop"
          alt="Driver scanning a package" />
        <img src="https://images.unsplash.com/photo-1515615192409-3c89a377f8a1?q=80&w=1200&auto=format&fit=crop"
          alt="Highway logistics at sunset" />
        <img src="https://images.unsplash.com/photo-1511688878353-3a2f5be94cd5?q=80&w=1200&auto=format&fit=crop"
          alt="Forklift moving pallets" />
        <img src="https://images.unsplash.com/photo-1541732844-79a16a5c71f8?q=80&w=1200&auto=format&fit=crop"
          alt="Courier handing a parcel to a customer" />
      </div>
    </div>
  </section> -->

<section id="contact" style="background:#f9fafc;">
  <div class="container contact" style="max-width:1100px;margin:auto;padding:60px 20px;">
    <div class="section-title" style="text-align:center;margin-bottom:40px;">
      <h2 style="font-size:32px;margin-bottom:10px;">Get in Touch</h2>
      <p style="color:#6b7280;">Our team is here to help you with any questions or delivery needs.</p>
    </div>

    <div class="contact-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
      
      <!-- Contact Form -->
      <div class="contact-form" style="background:#fff;padding:30px;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.06);border:1px solid rgba(0,0,0,.05);">
        <h3 style="margin-top:0;margin-bottom:10px;">Send us a Message</h3>
        <p style="margin-top:0;margin-bottom:25px;color:#6b7280;">We usually respond within one business day.</p>

        <form method="post" action="#" onsubmit="event.preventDefault(); alert('Thank you! We will contact you shortly.'); this.reset();">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="field" style="display:flex;flex-direction:column;">
              <label style="font-weight:500;margin-bottom:6px;">Name</label>
              <input type="text" name="name" required placeholder="John Doe"
                     style="padding:12px;border-radius:10px;border:1px solid #e5e7eb;">
            </div>
            <div class="field" style="display:flex;flex-direction:column;">
              <label style="font-weight:500;margin-bottom:6px;">Email</label>
              <input type="email" name="email" required placeholder="you@example.com"
                     style="padding:12px;border-radius:10px;border:1px solid #e5e7eb;">
            </div>
          </div>

          <div class="field" style="display:flex;flex-direction:column;margin-top:15px;">
            <label style="font-weight:500;margin-bottom:6px;">Service Type</label>
            <select name="service" required style="padding:12px;border-radius:10px;border:1px solid #e5e7eb;">
              <option value="">Select a service</option>
              <option>Same-Day Delivery</option>
              <option>Next-Day & Scheduled</option>
              <option>Documents & Secure</option>
            </select>
          </div>

          <div class="field" style="display:flex;flex-direction:column;margin-top:15px;">
            <label style="font-weight:500;margin-bottom:6px;">Message</label>
            <textarea name="message" rows="4" required placeholder="Tell us more..."
                      style="padding:12px;border-radius:10px;border:1px solid #e5e7eb;resize:vertical;"></textarea>
          </div>

          <button type="submit" class="btn primary"
                  style="margin-top:20px;width:100%;padding:12px 20px;font-weight:600;
                         background:linear-gradient(135deg,#2563eb,#06b6d4);
                         color:#fff;border:none;border-radius:10px;cursor:pointer;">
            Send Message
          </button>
        </form>
      </div>

      <!-- Contact Details -->
      <div class="contact-info" style="background:#fff;padding:30px;border-radius:16px;box-shadow:0 8px 24px rgba(0,0,0,.06);border:1px solid rgba(0,0,0,.05);">
        <h3 style="margin-top:0;">Contact Information</h3>
        <p style="color:#6b7280;margin-bottom:25px;">Reach out directly through any of the channels below.</p>

        <div style="margin-bottom:18px;">
          <h4 style="margin:0 0 4px;">Head Office</h4>
          <p style="margin:0;color:#6b7280;">123 Logistics Avenue, Suite 400<br>Metro City, Country</p>
        </div>

        <div style="margin-bottom:18px;">
          <h4 style="margin:0 0 4px;">Support</h4>
          <p style="margin:0;color:#6b7280;">+966 550772943<br>support@foj.com</p>
        </div>

        <div style="margin-bottom:18px;">
          <h4 style="margin:0 0 4px;">Business Hours</h4>
          <p style="margin:0;color:#6b7280;">Mon–Sat: 8:00–20:00<br>Sun: 10:00–16:00</p>
        </div>

        <div style="margin-top:24px;display:flex;gap:10px;flex-wrap:wrap;">
          <a href="#" class="btn" style="border:1px solid #e5e7eb;padding:10px 16px;border-radius:10px;text-decoration:none;">Track Parcel</a>
          <a href="login.php" class="btn primary" style="padding:10px 16px;text-decoration:none;">Client Portal</a>
        </div>
      </div>
    </div>
  </div>
</section>


  <footer>
    <div class="container"
      style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
      <div style="display:flex;align-items:center;gap:10px">
        <span class="logo" aria-hidden="true" style="width:28px;height:28px"></span>
        <span><?php echo $companyName; ?> © <?php echo date('Y'); ?></span>
      </div>
      <div style="display:flex;gap:14px">
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>
        <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'})">Back to top</a>
      </div>
    </div>
  </footer>

  <script>
    function toggleMenu(force) {
      const el = document.getElementById('mobileMenu');
      const isOpen = typeof force === 'boolean' ? force : !el.classList.contains('open');
      el.classList.toggle('open', isOpen);
    }
  </script>
</body>

</html>