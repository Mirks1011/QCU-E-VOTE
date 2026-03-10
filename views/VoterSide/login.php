<?php
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-login.css">
</head>
<body>
  <div class="login-screen">
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
      <div class="scan-wrapper">
        <div class="pulse-ring"></div>
        <div class="dotted-outer"></div>
        <div class="id-icon-box">
          <svg class="user-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
            <rect x="5" y="20" width="90" height="60" rx="10" ry="10" fill="none" stroke="#F2C94C" stroke-width="5"/>
            <circle cx="50" cy="45" r="14" fill="#F2C94C"/>
            <path d="M20 80 c0-16.57 13.43-30 30-30 s30 13.43 30 30" fill="#F2C94C"/>
          </svg>
        </div>
        <div class="scanner-line"></div>
      </div>

      <div class="instruction-text">
        <div class="serif-title">ENTER YOUR<br>STUDENT ID</div>
      </div>

      <!-- STUDENT ID FORM -->
      <form action="/QCU-E-VOTE/login" method="POST">
        <div class="input-wrapper">
          <input 
            type="text" 
            name="student_id" 
            placeholder="e.g. 2021-00001" 
            maxlength="20"
            required
          />
          <?php if (!empty($error)): ?>
              <p class="error-msg">
                  <?php echo htmlspecialchars($error); ?>
              </p>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn-scan">VERIFY ID</button>
      </form>

      <!-- NEW USER / OLD USER BUTTONS -->
      <div class="user-buttons">
        <button onclick="location.href='newvoter.php'" class="btn-new">New User</button>
        <button onclick="location.href='oldvoter.php'" class="btn-old">Old User</button>
      </div>
    </main>

    <!-- FOOTER -->
    <footer>
      <div class="status-pill">
        <div class="dot"></div>
        SBIT2F
      </div>
      <div class="status-pill">
        <div class="dot"></div>
        Scanner Ready
      </div>
    </footer>
  </div>
</body>
</html>