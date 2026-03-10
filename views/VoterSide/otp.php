<?php
// Guard
AuthMiddleware::requireLogin();   // for login-only pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — OTP</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-otp.css">
</head>
<body>
<div class="screen">

  <!-- HEADER -->
  <div class="qcu-header">
    <div class="header-container">
      <div class="logo-circle">
        <img src="/QCU-E-VOTE/assets/images/qcu-logo.svg" alt="QCU Logo" />
      </div>
      <div class="header-info">
        <h1>QCU-SSC E-VOTE SYSTEM</h1>
        <p>One Account. One Vote. <span class="secured">QCU Secured</span></p>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <main>
    <!-- LEFT -->
    <div class="left-panel">
      <div class="welcome-block">
        <h2>Welcome<br><span>QCUians</span></h2>
        <div class="divider"></div>
      </div>
      <div class="btn-group">
        <button class="btn btn-outline" id="btnNew">New Voter</button>
        <button class="btn btn-solid" id="btnOld">Old Voter</button>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
      <div class="otp-card">
        <div class="card-inner">
          <h3 class="otp-label">Your OTP is:</h3>
          <div class="otp-code" id="otpCode">
            <?php echo htmlspecialchars($otp); ?>
          </div>
          <div class="otp-info">
            <div class="monitor-icon">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="6" width="40" height="28" rx="4" fill="none" stroke="rgba(26,26,26,0.5)" stroke-width="2.5"/>
                <path d="M16 42h16M24 34v8" stroke="rgba(26,26,26,0.5)" stroke-width="2.5" stroke-linecap="round"/>
              </svg>
            </div>
            <p class="otp-desc">Use this OTP to open the Voting System Portal in your designated device</p>
            <p class="otp-expiry">Expires at: <?php echo htmlspecialchars($expires_at); ?></p>
          </div>
          <button class="btn-home" id="homeBtn">Home</button>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="status-pill"><div class="dot"></div>SBIT2F</div>
    <div class="status-pill"><div class="dot"></div>Scanner Ready</div>
  </footer>

</div>
<script src="/QCU-E-VOTE/Scripts/voter-otp.js"></script>
<!-- Prevent back button -->
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.go(1);
    });
</script>
</body>
</html>