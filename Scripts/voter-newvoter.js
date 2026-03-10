// Auto-calculate age from birthday
function calculateAge(birthdayValue) {
  if (!birthdayValue) return '';
  const today     = new Date();
  const birthDate = new Date(birthdayValue);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age > 0 ? age : '';
}

document.getElementById('birthday').addEventListener('change', function () {
  document.getElementById('age').value = calculateAge(this.value);
});

// Form submit
document.getElementById('newVoterForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const gender   = document.getElementById('gender').value;
  const birthday = document.getElementById('birthday').value;
  const course   = document.getElementById('course').value;
  let valid = true;

  // Validate required fields
  [
    { id: 'gender',   val: gender },
    { id: 'birthday', val: birthday },
    { id: 'course',   val: course }
  ].forEach(function (field) {
    const group = document.getElementById(field.id).closest('.field-group');
    if (!field.val) {
      group.classList.add('shake');
      setTimeout(function () { group.classList.remove('shake'); }, 500);
      valid = false;
    }
  });

  if (!valid) return;

  // Populate age before submitting
  document.getElementById('age').value = calculateAge(birthday);

  // POST to NewVoterController.php
  this.submit();
});