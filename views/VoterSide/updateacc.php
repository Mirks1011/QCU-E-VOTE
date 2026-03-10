<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QCU-SSC E-Vote System — Update Account</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/QCU-E-VOTE/assets/css/voter-updateacc.css">
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
    <div class="form-wrap">
      <div class="form-card">
        <div class="card-header">
          <h2>Update<br><span>Account</span></h2>
          <div class="header-divider"></div>
        </div>
        <form id="updateForm" class="update-form">

          <!-- Gender -->
          <div class="field-group">
            <label for="gender">Gender</label>
            <div class="select-wrap">
              <select id="gender" name="gender">
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

          <!-- Birthday -->
          <div class="field-group">
            <label for="birthday">Birthday</label>
            <input type="date" id="birthday" name="birthday">
          </div>

          <!-- Age (auto-calculated, read-only) -->
          <div class="field-group">
            <label for="age">Age</label>
            <div class="age-display">
              <input type="text" id="age" name="age" readonly placeholder="Auto-calculated">
              <span class="age-badge">Auto</span>
            </div>
          </div>

          <!-- Course -->
          <div class="field-group">
            <label for="course">Course</label>
            <div class="select-wrap">
              <select id="course" name="course">
                <option value="" disabled selected></option>
                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
                <option value="BSED">BSED</option>
                <option value="BSBA">BSBA</option>
                <option value="BSN">BSN</option>
                <option value="BSCE">BSCE</option>
                <option value="BSME">BSME</option>
                <option value="BSA">BSA</option>
              </select>
              <svg class="chevron" viewBox="0 0 12 12" fill="none">
                <path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>

          <button type="submit" class="btn-update">Update</button>
        </form>
      </div>
    </div>
  </main>

  <footer>
    <div class="status-pill"><div class="dot"></div>SBIT2F</div>
    <div class="status-pill"><div class="dot"></div>Scanner Ready</div>
  </footer>

</div>
<script src="/../Scripts/voter-updateacc.js"></script>
</body>
</html>