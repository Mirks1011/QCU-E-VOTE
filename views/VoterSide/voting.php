<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — Voting</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-voting.css">
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

  <!-- dynamically generated from DB -->
  <div class="tabs-bar" id="tabsBar">
    <?php foreach ($ballot as $index => $item): ?>
      <button class="tab <?php echo $index === 0 ? 'active' : ''; ?>"
              data-tab="<?php echo $index; ?>">
        <?php echo ($index + 1) . '. ' . htmlspecialchars($item['position']['POSITION_NAME']); ?>
      </button>
    <?php endforeach; ?>
  </div>

  <!-- CONTENT -->
  <main>
    <!-- LEFT: Position info + actions -->
    <div class="left-panel">
        <div class="position-info">
            <h2 class="position-title" id="positionTitle">
                <?php echo htmlspecialchars($ballot[0]['position']['POSITION_NAME']); ?>
            </h2>
        </div>

        <!-- Vote counter -->
        <div class="vote-counter" id="voteCounter">
            <div class="counter-label">Votes Cast</div>
            <div class="counter-display">
                <span class="counter-current" id="counterCurrent">0</span>
                <span class="counter-sep">/</span>
                <span class="counter-total"><?php echo count($ballot); ?></span>
            </div>
            <div class="counter-bar">
                <div class="counter-fill" id="counterFill" style="width: 0%"></div>
            </div>
            <!-- Clickable voted list -->
            <div class="voted-list" id="votedList"></div>
        </div>

        <div class="left-actions">
            <button class="btn-skip" id="skipBtn">
                <svg viewBox="0 0 18 18" fill="none">
                    <circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6 9h6M9 6l3 3-3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Skip Vote
            </button>
            <button class="btn-next" id="nextBtn">NEXT →</button>
        </div>
    </div>

    <!-- RIGHT: Candidates grid -->
    <div class="right-panel">
      <div class="candidates-grid" id="candidatesGrid"></div>
    </div>
  </main>

</div>

<!----PREVENTS BACK----->
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.go(1);
    });
</script>
<!--SAVES PROGRESS WHEN REFRESHED-->
<script>
    const ballotData    = <?php echo json_encode($ballot); ?>;
    const savedProgress = <?php echo isset($_SESSION['vote_progress']) ? json_encode($_SESSION['vote_progress']) : '{}'; ?>;
</script>
<script src="/QCU-E-VOTE/Scripts/voter-voting.js"></script>
</body>
</html>