<?php
AuthMiddleware::requireLogin();   // for login-only pages
AuthMiddleware::requireOtpDisplayed();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — Enter OTP</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-inputotp.css">
</head>
<body>
<div class="screen">
  <main>
    <!-- LEFT PANEL -->
    <div class="left-panel">
      <div class="logo-wrap">
        <img src="/QCU-E-VOTE/assets/images/qcu-logo.svg" alt="QCU Logo" />
      </div>
      <h2 class="system-name">QCU-SSC E-VOTE SYSTEM</h2>
      <p class="system-sub">One Account. One Vote. <span class="secured">QCU Secured.</span></p>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
      <div class="otp-section">
        <h1 class="enter-title">ENTER OTP</h1>
        <p class="tagline">"Tahanan ng husay at talino"</p>

        <!-- Error message from controller -->
        <?php if (!empty($error)): ?>
          <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Form posts to OtpVerifyController -->
        <form id="otpForm" action="/QCU-E-VOTE/otp/verify" method="POST">
          <div class="otp-inputs" id="otpInputs">
            <input class="otp-box" type="text" name="otp_1" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            <input class="otp-box" type="text" name="otp_2" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            <input class="otp-box" type="text" name="otp_3" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            <input class="otp-box" type="text" name="otp_4" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            <input class="otp-box" type="text" name="otp_5" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
            <input class="otp-box" type="text" name="otp_6" maxlength="1" inputmode="numeric" pattern="[0-9]" required>
          </div>
          <p class="error-msg" id="errorMsg"></p>
          <button type="submit" class="btn-proceed" id="proceedBtn">PROCEED</button>
        </form>

      </div>
    </div>
  </main>
</div>
<script src="/QCU-E-VOTE/Scripts/voter-inputotp.js"></script>
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.go(1);
    });
</script>
</body>
</html>