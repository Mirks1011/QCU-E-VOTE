const voterType = sessionStorage.getItem('voterType') || 'old';
const btnNew = document.getElementById('btnNew');
const btnOld = document.getElementById('btnOld');

if (voterType === 'new') {
    btnNew.classList.add('btn-solid');
    btnNew.classList.remove('btn-outline');
    btnOld.classList.add('btn-outline');
    btnOld.classList.remove('btn-solid');
} else {
    btnOld.classList.add('btn-solid');
    btnOld.classList.remove('btn-outline');
    btnNew.classList.add('btn-outline');
    btnNew.classList.remove('btn-solid');
}

// Home button → go to OTP verify page
document.getElementById('homeBtn').addEventListener('click', function () {
    window.location.href = '/QCU-E-VOTE/otp/verify';
});

// Next button → go to data privacy page
document.getElementById('nextBtn').addEventListener('click', function () {
    sessionStorage.setItem('voterType', 'old');
    window.location.href = '/QCU-E-VOTE/datapriv';
});