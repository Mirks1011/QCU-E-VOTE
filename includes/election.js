// ===== STATE =====
let currentEditTarget = null;
let currentDeleteTarget = null;

// Election data store
const elections = {
  recent: { name: 'QCU-SSC ELECTION 2027', start: '', end: '' },
  '2026': { name: 'QCU-SSC ELECTION 2026', start: '', end: '' },
  '2025': { name: 'QCU-SSC ELECTION 2025', start: '', end: '' },
  '2024': { name: 'QCU-SSC ELECTION 2024', start: '', end: '' },
};

// =====================
// AUTO STATUS CHECKER
// Runs every 10 seconds
// =====================

function getElectionStatus(start, end) {
  if (!start && !end) return 'upcoming';
  const now  = new Date();
  const s    = start ? new Date(start) : null;
  const e    = end   ? new Date(end)   : null;

  if (e && now >= e)        return 'closed';
  if (s && now >= s)        return 'ongoing';
  return 'upcoming';
}

function getStatusLabel(status) {
  if (status === 'ongoing') return 'Ongoing';
  if (status === 'closed')  return 'Closed';
  return 'Upcoming';
}

function getStatusClass(status) {
  if (status === 'ongoing') return 'status ongoing';
  if (status === 'closed')  return 'status closed-status';
  return 'status upcoming';
}

// Update the Recent card's status badge
function refreshRecentStatus() {
  const data = elections['recent'];
  if (!data || !data.name) return;

  const status    = getElectionStatus(data.start, data.end);
  const statusEl  = document.getElementById('recent-status');
  if (statusEl) {
    statusEl.textContent  = getStatusLabel(status);
    statusEl.className    = getStatusClass(status);
  }

  // If closed → auto-move to Election Closed list
  if (status === 'closed') {
    autoMoveRecentToClosed();
  }
}

function autoMoveRecentToClosed() {
  const data = elections['recent'];
  if (!data || !data.name) return;

  // Move to closed list
  const closedId = 'auto-closed-' + Date.now();
  appendClosedCard(closedId, data.name, data.start, data.end, true);

  // Clear recent slot
  elections['recent'] = { name: '', start: '', end: '' };

  // Update recent card to empty placeholder
  document.getElementById('recent-title').textContent   = '—';
  document.getElementById('recent-date-info').textContent = '';
  const statusEl = document.getElementById('recent-status');
  if (statusEl) { statusEl.textContent = ''; statusEl.className = 'status'; }

  showToast('Election ended and moved to Election Closed! 📋');
}

// Run checker every 10 seconds
setInterval(refreshRecentStatus, 10000);
// Also run immediately on load
window.addEventListener('DOMContentLoaded', () => {
  refreshRecentStatus();
});

// =====================
// DATE VALIDATION UTILS
// =====================
function getNowDateTimeLocal() {
  const now = new Date();
  now.setSeconds(0, 0);
  const pad = n => String(n).padStart(2, '0');
  return `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
}

function validateDates(startVal, endVal, isRequired = true) {
  const now = new Date();
  now.setSeconds(0, 0);

  if (isRequired && !startVal) return { valid: false, message: 'Start date/time is required.', field: 'start' };
  if (isRequired && !endVal)   return { valid: false, message: 'End date/time is required.',   field: 'end'   };

  if (startVal) {
    const s = new Date(startVal);
    if (s < now) return { valid: false, message: 'Start date/time must be today or in the future.', field: 'start' };
  }

  if (endVal) {
    const e = new Date(endVal);
    if (e < now) return { valid: false, message: 'End date/time must be today or in the future.', field: 'end' };
  }

  if (startVal && endVal) {
    if (new Date(endVal) <= new Date(startVal))
      return { valid: false, message: 'End date/time must be after start date/time.', field: 'end' };
  }

  return { valid: true, message: '', field: null };
}

function lockPastDates(startInputId, endInputId) {
  const nowStr  = getNowDateTimeLocal();
  const startEl = document.getElementById(startInputId);
  const endEl   = document.getElementById(endInputId);

  startEl.min = nowStr;
  endEl.min   = nowStr;

  // Remove old listener before adding (avoid duplicates)
  const newStart = startEl.cloneNode(true);
  startEl.parentNode.replaceChild(newStart, startEl);

  newStart.addEventListener('change', function () {
    if (this.value) {
      endEl.min = this.value;
      if (endEl.value && endEl.value <= this.value) endEl.value = '';
    } else {
      endEl.min = nowStr;
    }
  });
}

function showFormError(errorId, message, inputId = null) {
  const errEl = document.getElementById(errorId);
  errEl.textContent = message;
  errEl.closest('.modal').querySelectorAll('.form-input').forEach(el => el.classList.remove('invalid'));
  if (inputId) {
    const inp = document.getElementById(inputId);
    if (inp) inp.classList.add('invalid');
  }
}

function clearFormError(errorId) {
  const errEl = document.getElementById(errorId);
  if (errEl) {
    errEl.textContent = '';
    errEl.closest('.modal').querySelectorAll('.form-input').forEach(el => el.classList.remove('invalid'));
  }
}

// =====================
// RECENT ELECTION LOGIC
// =====================
function promoteToRecent(id, data) {
  const oldRecent = elections['recent'];

  if (oldRecent && oldRecent.name) {
    const oldId = 'demoted-' + Date.now();
    elections[oldId] = { ...oldRecent };
    appendClosedCard(oldId, oldRecent.name, oldRecent.start, oldRecent.end);
  }

  elections['recent'] = { ...data };
  updateRecentCard(data.name, data.start, data.end);

  if (id !== 'new') {
    const oldCard = document.getElementById(`card-${id}`);
    if (oldCard) {
      oldCard.classList.add('removing');
      setTimeout(() => oldCard.remove(), 350);
    }
    delete elections[id];
  }

  // Refresh status badge immediately
  refreshRecentStatus();
}

function updateRecentCard(name, start, end) {
  const match   = name.match(/(\d{4})$/);
  const titleEl = document.getElementById('recent-title');

  if (match) {
    const base = name.slice(0, name.length - match[0].length).trim();
    titleEl.innerHTML = `${base}<br/>${match[0]}`;
  } else {
    titleEl.textContent = name;
  }

  const dateInfo = document.getElementById('recent-date-info');
  if (start || end) {
    dateInfo.innerHTML =
      (start ? `📅 Start: <strong>${formatDateTime(start)}</strong>` : '') +
      (start && end ? '&nbsp; | &nbsp;' : '') +
      (end ? `🏁 End: <strong>${formatDateTime(end)}</strong>` : '');
  } else {
    dateInfo.textContent = '';
  }

  // Update status badge right away
  const status   = getElectionStatus(start, end);
  const statusEl = document.getElementById('recent-status');
  if (statusEl) {
    statusEl.textContent = getStatusLabel(status);
    statusEl.className   = getStatusClass(status);
  }
}

function appendClosedCard(id, name, start, end, highlight = false) {
  elections[id] = { name, start, end };
  const list    = document.getElementById('closed-list');

  const card    = document.createElement('div');
  card.className = 'election-card closed' + (highlight ? ' just-closed' : '');
  card.id        = `card-${id}`;
  card.dataset.id = id;

  const dateText = (start || end)
    ? `<div class="election-meta" style="font-size:12px;margin-top:2px;">
        ${start ? '📅 ' + formatDateTime(start) : ''}
        ${start && end ? ' → ' : ''}
        ${end ? formatDateTime(end) : ''}
       </div>`
    : '';

  card.innerHTML = `
    <div>
      <div class="election-title-sm" id="title-${id}">${name}</div>
      ${dateText}
    </div>
    <div class="card-actions">
      <button class="icon-btn" title="Delete" onclick="openDeleteModal('${id}')">🗑️</button>
      <button class="icon-btn" title="Edit" onclick="openEditModal('${id}')">✏️</button>
    </div>
  `;

  card.style.opacity   = '0';
  card.style.transform = 'translateY(10px)';
  list.prepend(card); // prepend so newest closed is on top
  requestAnimationFrame(() => {
    card.style.transition = 'opacity 0.35s, transform 0.35s';
    card.style.opacity    = '1';
    card.style.transform  = 'translateY(0)';
  });

  // Remove highlight after 3s
  if (highlight) setTimeout(() => card.classList.remove('just-closed'), 3000);
}

// =====================
// ADD MODAL
// =====================
function openAddModal() {
  document.getElementById('add-name').value  = '';
  document.getElementById('add-start').value = '';
  document.getElementById('add-end').value   = '';
  clearFormError('add-error');
  openModal('addModal');
  lockPastDates('add-start', 'add-end');
}

function saveAdd() {
  const name  = document.getElementById('add-name').value.trim();
  const start = document.getElementById('add-start').value;
  const end   = document.getElementById('add-end').value;

  if (!name) { showFormError('add-error', 'Election name is required.', 'add-name'); return; }

  const dateCheck = validateDates(start, end, true);
  if (!dateCheck.valid) {
    showFormError('add-error', dateCheck.message,
      dateCheck.field === 'start' ? 'add-start' :
      dateCheck.field === 'end'   ? 'add-end'   : null);
    return;
  }

  promoteToRecent('new', { name, start, end });
  closeAllModals();
  showToast('Election added and set as Recent! 🎉');
}

// =====================
// EDIT MODAL
// =====================
function openEditModal(target) {
  currentEditTarget = target;
  const data = elections[target] || {};
  document.getElementById('edit-name').value  = data.name  || '';
  document.getElementById('edit-start').value = data.start || '';
  document.getElementById('edit-end').value   = data.end   || '';
  clearFormError('edit-error');
  openModal('editModal');
  lockPastDates('edit-start', 'edit-end');
}

function saveEdit() {
  const name  = document.getElementById('edit-name').value.trim();
  const start = document.getElementById('edit-start').value;
  const end   = document.getElementById('edit-end').value;

  if (!name) { showFormError('edit-error', 'Election name is required.', 'edit-name'); return; }

  const dateCheck = validateDates(start, end, false);
  if (!dateCheck.valid) {
    showFormError('edit-error', dateCheck.message,
      dateCheck.field === 'start' ? 'edit-start' :
      dateCheck.field === 'end'   ? 'edit-end'   : null);
    return;
  }

  elections[currentEditTarget] = { name, start, end };

  if (currentEditTarget === 'recent') {
    updateRecentCard(name, start, end);
  } else {
    const titleEl = document.getElementById(`title-${currentEditTarget}`);
    if (titleEl) titleEl.textContent = name;

    const card = document.getElementById(`card-${currentEditTarget}`);
    if (card && (start || end)) {
      let metaEl = card.querySelector('.election-meta');
      if (!metaEl) {
        metaEl = document.createElement('div');
        metaEl.className = 'election-meta';
        metaEl.style.cssText = 'font-size:12px;margin-top:2px;';
        card.querySelector('div').appendChild(metaEl);
      }
      metaEl.innerHTML =
        (start ? '📅 ' + formatDateTime(start) : '') +
        (start && end ? ' → ' : '') +
        (end ? formatDateTime(end) : '');
    }
  }

  closeAllModals();
  showToast('Election updated successfully! ✅');
}

// =====================
// DELETE MODAL
// =====================
function openDeleteModal(target) {
  currentDeleteTarget = target;
  const data = elections[target];
  document.getElementById('delete-label').textContent =
    `Are you sure you want to delete "${data ? data.name : target}"? This action cannot be undone.`;
  openModal('deleteModal');
}

function confirmDelete() {
  const target = currentDeleteTarget;
  const card   = document.getElementById(`card-${target}`);
  if (card) {
    card.classList.add('removing');
    setTimeout(() => card.remove(), 350);
  }
  delete elections[target];
  closeAllModals();
  showToast('Election deleted. 🗑️');
}

// =====================
// MODAL HELPERS
// =====================
function openModal(id) {
  document.getElementById('overlay').classList.add('active');
  document.body.classList.add('modal-open');
  const modal = document.getElementById(id);
  modal.style.display = 'block';
  requestAnimationFrame(() => requestAnimationFrame(() => modal.classList.add('active')));
}

function closeAllModals() {
  ['editModal', 'addModal', 'deleteModal'].forEach(id => {
    const m = document.getElementById(id);
    m.classList.remove('active');
    setTimeout(() => { if (!m.classList.contains('active')) m.style.display = 'none'; }, 300);
  });
  document.getElementById('overlay').classList.remove('active');
  document.body.classList.remove('modal-open');
}

// =====================
// TOAST
// =====================
function showToast(message, isError = false) {
  let toast = document.getElementById('toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toast';
    toast.className = 'toast';
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.className   = 'toast' + (isError ? ' error' : '');
  requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3500);
}

// =====================
// FORMAT DATE
// =====================
function formatDateTime(dtStr) {
  if (!dtStr) return '';
  return new Date(dtStr).toLocaleString('en-PH', {
    month: 'short', day: 'numeric', year: 'numeric',
    hour: '2-digit', minute: '2-digit'
  });
}

// =====================
// SEARCH
// =====================
document.getElementById('searchInput').addEventListener('input', function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.election-card').forEach(card => {
    card.style.opacity = card.textContent.toLowerCase().includes(q) ? '1' : '0.3';
  });
});

// =====================
// ESC TO CLOSE
// =====================
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllModals(); });