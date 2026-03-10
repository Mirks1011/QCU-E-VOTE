<?php
AuthMiddleware::requireLogin();   // for login-only pages

// handle missing vote summary
$vote_summary = $_SESSION['vote_summary'] ?? [];
$ballot       = $_SESSION['ballot']       ?? [];
$election     = $_SESSION['election']     ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — Vote Submitted</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-voted.css">
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
    <div class="card">
      <!-- Card Header -->
      <div class="card-header">
        <div class="seal">
          <svg viewBox="0 0 80 80" fill="none">
            <circle cx="40" cy="40" r="36" fill="#F2C94C" stroke="#8B4513" stroke-width="3"/>
            <circle cx="40" cy="40" r="28" fill="none" stroke="#8B4513" stroke-width="1.5" stroke-dasharray="3 2"/>
            <circle cx="40" cy="32" r="10" fill="#8B4513"/>
            <path d="M18 62c0-12.15 9.85-22 22-22s22 9.85 22 22" fill="#8B4513"/>
          </svg>
        </div>
        <div class="card-title-block">
          <h2 class="card-title"><?php echo htmlspecialchars($election['ELECTION_TITLE'] ?? 'QCU SSC ELECTION'); ?></h2>
          <div class="success-badge">
            <svg viewBox="0 0 14 14" fill="none">
              <circle cx="7" cy="7" r="6" fill="#22c55e"/>
              <path d="M4 7l2 2 4-4" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Votes Submitted
          </div>
        </div>
      </div>

      <div class="divider"></div>

      <!-- Message -->
      <p class="success-msg">
        You have successfully cast your votes.<br>
        Your votes have been securely recorded in the system<br>
        and can no longer be modified.
      </p>

      <!-- Vote Summary -->
      <div class="summary-section">
        <h3 class="summary-title">Your Vote Summary</h3>
        <div class="summary-list">
          <?php foreach ($ballot as $item):
              $position    = $item['position'];
              $position_id = $position['POSITION_ID'];
              $candidate_id = $vote_summary[$position_id] ?? null;
              $candidate   = null;

              if ($candidate_id !== null) {
                  foreach ($item['candidates'] as $c) {
                      if ($c['CANDIDATE_ID'] == $candidate_id) {
                          $candidate = $c;
                          break;
                      }
                  }
              }
          ?>
          <div class="summary-item <?php echo $candidate_id === null ? 'skipped' : ''; ?>">
            <div class="summary-position"><?php echo htmlspecialchars($position['POSITION_NAME']); ?></div>
            <div class="summary-candidate">
              <?php if ($candidate_id === null): ?>
                <span class="skipped-label">Skipped</span>
              <?php else: ?>
                <?php echo htmlspecialchars($candidate['NAME'] ?? '—'); ?>
                <span class="summary-party"><?php echo htmlspecialchars($candidate['PARTYLIST'] ?? ''); ?></span>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="divider"></div>

      <p class="thank-msg">
        Thank you for participating in the Supreme Student Council Election.
      </p>

      <button class="btn-thankyou" id="thankYouBtn">THANK YOU</button>
    </div>
  </main>
</div>
<script src="/QCU-E-VOTE/Scripts/voter-voted.js"></script>
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.go(1);
    });
</script>
</body>
</html>