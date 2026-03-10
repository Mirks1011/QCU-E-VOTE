document.getElementById('updateProfileBtn').addEventListener('click', function () {
    sessionStorage.setItem('voterType', 'old');
    window.location.href = '/QCU-E-VOTE/update';
});

// Next button → data privacy page
document.getElementById('nextBtn').addEventListener('click', function () {
    sessionStorage.setItem('voterType', 'old');
    window.location.href = '/QCU-E-VOTE/datapriv';
})