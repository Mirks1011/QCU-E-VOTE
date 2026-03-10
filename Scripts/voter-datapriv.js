const agreeCheck = document.getElementById('agreeCheck');
const confirmBtn = document.getElementById('confirmBtn');

// Reset checkbox and button state on every page load
window.addEventListener('pageshow', function () {
    agreeCheck.checked = false;
    confirmBtn.classList.remove('active');
    confirmBtn.disabled = true;
});

// Checkbox toggle
agreeCheck.addEventListener('change', function () {
    if (this.checked) {
        confirmBtn.classList.add('active');
        confirmBtn.disabled = false;
    } else {
        confirmBtn.classList.remove('active');
        confirmBtn.disabled = true;
    }
});

// Clean route to OTP
confirmBtn.addEventListener('click', function () {
    window.location.href = '/QCU-E-VOTE/otp';
});