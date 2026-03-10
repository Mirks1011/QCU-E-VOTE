<?php
AuthMiddleware::requireLogin();   // for login-only pages
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — New Voter</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-newvoter.css">
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
        <button class="btn btn-solid">New Voter</button>
        <button class="btn btn-outline" onclick="window.location.href='oldvoter.php'">Old Voter</button>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right-panel">
      <div class="voter-card">
        <div class="card-inner">

          <div class="card-top">
            <div class="avatar-wrap">
              <div class="avatar-bg">
                <svg width="36" height="36" viewBox="0 0 56 56" fill="none">
                  <circle cx="28" cy="22" r="12" fill="rgba(255,255,255,0.7)"/>
                  <path d="M6 52c0-12.15 9.85-22 22-22s22 9.85 22 22" fill="rgba(255,255,255,0.7)"/>
                </svg>
              </div>
            </div>
            <div>
              <!-- Displays voters_id from session -->
              <h3>Welcome, <?php echo htmlspecialchars($voters_id); ?>!</h3>
              <p class="card-subtitle">Let's fill out something about yourself!</p>
            </div>
          </div>

          <!-- Form posts to NewVoterController.php -->
          <form action="/QCU-E-VOTE/new-voter" method="POST">

            <div class="field-group">
              <label for="gender">Gender <span class="req">*</span></label>
              <div class="select-wrap">
                <select id="gender" name="gender" required>
                  <option value="" disabled selected></option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                  <option value="Other">Other</option>
                </select>
                <svg class="chevron" viewBox="0 0 12 12" fill="none">
                  <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>

            <div class="field-group">
              <label for="birthday">Birthday <span class="req">*</span></label>
              <input type="date" id="birthday" name="birthday" required>
            </div>

            <div class="field-group">
              <label for="age">Age</label>
              <div class="age-display">
                <input type="text" id="age" name="age" readonly placeholder="Auto-calculated">
                <span class="age-badge">Auto</span>
              </div>
            </div>

            <div class="field-group">
              <label for="course">Course <span class="req">*</span></label>
              <div class="select-wrap">
              <select id="course_id" name="course_id" required>
                  <option value="" disabled selected>Please fill out this field.</option>
                  <?php foreach ($courses as $course): ?>
                      <option value="<?php echo $course['COURSE_ID']; ?>">
                          <?php echo htmlspecialchars($course['COURSE_CODE']); ?>
                      </option>
                  <?php endforeach; ?>
              </select>
                <svg class="chevron" viewBox="0 0 12 12" fill="none">
                  <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>

            <!-- Error message display -->
            <?php if (isset($_GET['error'])): ?>
              <p class="error-msg"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>

            <button type="submit" class="btn-submit">Submit</button>

          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    <div class="status-pill"><div class="dot"></div>System Online</div>
    <div class="status-pill"><div class="dot"></div>Scanner Ready</div>
  </footer>

</div>
<script src="/QCU-E-VOTE/Scripts/voter-newvoter.js"></script>
</body>
</html>