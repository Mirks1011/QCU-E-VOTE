let currentTab  = 0;
const totalTabs = ballotData.length;
const votes     = {};
// Restore saved progress on page load
Object.assign(votes, savedProgress);

// Save progress to session via AJAX
function saveProgress() {
    fetch('/QCU-E-VOTE/vote/progress', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(votes)
    });
}

function updateCounter() {
    const voted   = Object.values(votes).filter(v => v !== null).length;
    const percent = Math.round((voted / totalTabs) * 100);

    document.getElementById('counterCurrent').textContent = voted;
    document.getElementById('counterFill').style.width    = percent + '%';

    const list = document.getElementById('votedList');
    list.innerHTML = '';

    ballotData.forEach(function (item, index) {
        const position    = item.position;
        const positionId  = position.POSITION_ID;
        const candidateId = votes[positionId];

        if (candidateId === undefined) return;

        const candidate = item.candidates.find(c => c.CANDIDATE_ID === candidateId);
        const isSkipped = candidateId === null;

        const entry = document.createElement('div');
        entry.className = 'voted-entry' + (isSkipped ? ' skipped' : '');
        entry.title     = 'Click to go back to this position';

        entry.innerHTML = `
            <div class="voted-entry-position">${position.POSITION_NAME}</div>
            <div class="voted-entry-name">${isSkipped ? 'Skipped' : candidate ? candidate.NAME : '—'}</div>
        `;

        entry.addEventListener('click', function () {
            currentTab = index;
            renderTab(index);
        });

        list.appendChild(entry);
    });
}

function renderTab(index) {
    const item       = ballotData[index];
    const position   = item.position;
    const candidates = item.candidates;

    document.getElementById('positionTitle').textContent = position.POSITION_NAME;

    document.querySelectorAll('.tab').forEach(function (tab, i) {
        tab.classList.toggle('active', i === index);
    });

    const grid = document.getElementById('candidatesGrid');
    grid.innerHTML = '';

    candidates.forEach(function (candidate) {
        const isVoted = votes[position.POSITION_ID] === candidate.CANDIDATE_ID;

        const card = document.createElement('div');
        card.className = 'candidate-card' + (isVoted ? ' voted' : '');
        card.dataset.candidateId = candidate.CANDIDATE_ID;
        card.style.cursor = 'pointer';

        card.innerHTML = `
            <div class="voted-check">
                <svg viewBox="0 0 10 10" fill="none">
                    <path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <img src="${candidate.IMAGE}" 
                 alt="${candidate.NAME}" 
                 class="candidate-img"
                 onerror="this.src='/QCU-E-VOTE/assets/images/candidates/default.jpg'">
            <div class="candidate-name">${candidate.NAME}</div>
            <div class="candidate-party">${candidate.PARTYLIST}</div>
            <div class="candidate-campaign">${candidate.CAMPAIGN}</div>
        `;

        card.addEventListener('click', function () {
            const alreadyVoted = votes[position.POSITION_ID] === candidate.CANDIDATE_ID;
            if (alreadyVoted) {
                delete votes[position.POSITION_ID];
            } else {
                votes[position.POSITION_ID] = candidate.CANDIDATE_ID;
            }
            renderTab(index);
            updateCounter(); // update on card click
            saveProgress(); //SAVES PROGRESS
        });

        grid.appendChild(card);
    });
}

// Tab click
document.querySelectorAll('.tab').forEach(function (tab) {
    tab.addEventListener('click', function () {
        currentTab = parseInt(this.dataset.tab);
        renderTab(currentTab);
    });
});

// Skip button
document.getElementById('skipBtn').addEventListener('click', function () {
    const position = ballotData[currentTab].position;
    votes[position.POSITION_ID] = null;
    updateCounter(); // update on skip
    saveProgress(); //SAVES PROGRESS

    if (currentTab < totalTabs - 1) {
        currentTab++;
        renderTab(currentTab);
    } else {
        submitVotes();
    }
});

// Next button
document.getElementById('nextBtn').addEventListener('click', function () {
    updateCounter(); // update on next
    saveProgress(); //SAVES PROGRESS

    if (currentTab < totalTabs - 1) {
        currentTab++;
        renderTab(currentTab);
    } else {
        submitVotes();
    }
});

function submitVotes() {
    // POST votes as JSON to VoteSubmitController
    const form  = document.createElement('form');
    form.method = 'POST';
    form.action = '/QCU-E-VOTE/vote/submit';

    const input = document.createElement('input');
    input.type  = 'hidden';
    input.name  = 'votes';
    input.value = JSON.stringify(votes);

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

renderTab(0);
updateCounter();