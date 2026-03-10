<?php
AuthMiddleware::requireLogin();   // for login-only pages
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-oldvoter.css">
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

  <main>
    <div class="left-panel">
      <div class="welcome-block">
        <h2>Welcome<br><span>QCUians</span></h2>
        <div class="divider"></div>
      </div>
      <div class="btn-group">
        <button class="btn btn-outline">New Voter</button>
        <button class="btn btn-solid">Old Voter</button>
      </div>
    </div>

    <div class="right-panel">
      <div class="voter-card">
        <div class="card-inner">
          <div class="card-top">
            <h3>Welcome Back, <?php echo htmlspecialchars($voters_id); ?>!</h3>
             <div class="student-no">Student No: <?php echo htmlspecialchars($student_id); ?></div>
          </div>
          <div class="card-body">
            <div class="avatar-wrap">
              <div class="avatar-bg">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
                  <circle cx="28" cy="22" r="12" fill="rgba(255,255,255,0.7)"/>
                  <path d="M6 52c0-12.15 9.85-22 22-22s22 9.85 22 22" fill="rgba(255,255,255,0.7)"/>
                </svg>
              </div>
              <div class="verified-badge">
                <svg viewBox="0 0 12 12" fill="none">
                  <path d="M2 6l3 3 5-5" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
            <div class="info-fields">
              <div class="field-row">
                <span class="field-label">Gender</span>
                 <div class="field-value" id="display-gender"><?php echo htmlspecialchars($gender); ?></div>
              </div>
              <div class="field-row">
                <span class="field-label">Age</span>
                <div class="field-value" id="display-age"><?php echo htmlspecialchars($age); ?></div>
              </div>
              <div class="field-row">
                <span class="field-label">Course</span>
                <div class="field-value" id="display-course"><?php echo htmlspecialchars($course); ?></div>
              </div>
            </div>
          </div>
          <div class="card-actions">
            <button class="btn-update" id="updateProfileBtn">Update Profile</button>
            <button class="btn-next" id="nextBtn">
              Next
              <svg viewBox="0 0 14 14" fill="none">
                <path d="M3 7h8M7 3l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="status-pill"><div class="dot"></div>SBIT2F</div>
    <div class="status-pill"><div class="dot"></div>Scanner Ready</div>
  </footer>

</div>
<script src="/QCU-E-VOTE/Scripts/voter-oldvoter.js"></script>
</body>
</html>