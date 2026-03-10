// Pre-fill from URL params (passed from oldvoter page)
const params = new URLSearchParams(window.location.search);
if (params.get('gender'))   document.getElementById('gender').value   = params.get('gender');
if (params.get('birthday')) document.getElementById('birthday').value = params.get('birthday');
if (params.get('course'))   document.getElementById('course').value   = params.get('course');

// Auto-calculate age from birthday
function calculateAge(birthdayValue) {
  if (!birthdayValue) return '';
  const today    = new Date();
  const birthDate = new Date(birthdayValue);
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age > 0 ? age : '';
}

// Run on load if birthday param exists
if (params.get('birthday')) {
  document.getElementById('age').value = calculateAge(params.get('birthday'));
}

// Recalculate whenever birthday changes
document.getElementById('birthday').addEventListener('change', function () {
  document.getElementById('age').value = calculateAge(this.value);
});

// On submit
document.getElementById('updateForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const gender   = document.getElementById('gender').value;
  const birthday = document.getElementById('birthday').value;
  const age      = document.getElementById('age').value;
  const course   = document.getElementById('course').value;

  if (!gender || !birthday || !course) {
    document.querySelectorAll('.field-group').forEach(function (g) {
      const input = g.querySelector('input:not([readonly]), select');
      if (input && !input.value) {
        g.classList.add('shake');
        setTimeout(function () { g.classList.remove('shake'); }, 500);
      }
    });
    return;
  }

  // Redirect back to oldvoter with updated data
  const query = new URLSearchParams({ gender, birthday, age, course });
  window.location.href = 'oldvoter.html?' + query.toString();
});