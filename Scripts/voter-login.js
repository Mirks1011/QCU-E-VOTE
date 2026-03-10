// Current voter data (defaults)
let voterData = {
  gender: 'Male',
  age: '19',
  course: 'BSIT'
};

// Check if returning from update-account page with new data
const params = new URLSearchParams(window.location.search);
if (params.get('gender')) voterData.gender = params.get('gender');
if (params.get('age'))    voterData.age    = params.get('age');
if (params.get('course')) voterData.course = params.get('course');

// Render current data
function renderData() {
  document.getElementById('display-gender').textContent = voterData.gender;
  document.getElementById('display-age').textContent    = voterData.age;
  document.getElementById('display-course').textContent = voterData.course;
}

renderData();

// Update Profile button — go to update-account page with current data
document.getElementById('updateProfileBtn').addEventListener('click', function () {
  const query = new URLSearchParams(voterData);
  window.location.href = 'update-account.html?' + query.toString();
});

// Next button — proceed (placeholder)
document.getElementById('nextBtn').addEventListener('click', function () {
  alert('Proceeding to voting...');
});