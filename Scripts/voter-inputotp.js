const boxes    = document.querySelectorAll('.otp-box');
const errorMsg = document.getElementById('errorMsg');

// ── Auto-focus and jump between boxes ──
boxes.forEach(function (box, index) {
    box.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value) {
            this.classList.add('filled');
            this.classList.remove('error');
            errorMsg.textContent = '';
            if (index < boxes.length - 1) {
                boxes[index + 1].focus();
            }
        } else {
            this.classList.remove('filled');
        }
    });

    box.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && !this.value && index > 0) {
            boxes[index - 1].focus();
            boxes[index - 1].value = '';
            boxes[index - 1].classList.remove('filled');
        }
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight', 'Delete'].includes(e.key)) {
            e.preventDefault();
        }
    });

    box.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        pasted.split('').forEach(function (digit, i) {
            if (boxes[index + i]) {
                boxes[index + i].value = digit;
                boxes[index + i].classList.add('filled');
            }
        });
        const nextEmpty = Array.from(boxes).findIndex(function (b) { return !b.value; });
        if (nextEmpty !== -1) boxes[nextEmpty].focus();
        else boxes[boxes.length - 1].focus();
    });
});

boxes[0].focus();

// ── Proceed — validate on frontend before submitting ──
document.getElementById('otpForm').addEventListener('submit', function (e) {
    const entered = Array.from(boxes).map(function (b) { return b.value; }).join('');

    if (entered.length < 6) {
        e.preventDefault(); // Stop form submit if incomplete
        errorMsg.textContent = 'Please enter all 6 digits.';
        boxes.forEach(function (b) {
            if (!b.value) b.classList.add('error');
        });
        return;
    }

    // All 6 digits filled — let the form POST to OtpVerifyController
});