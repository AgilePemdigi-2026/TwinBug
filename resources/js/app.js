const toast = document.querySelector('[data-toast]');
const toastTitle = toast?.querySelector('[data-toast-title]');
const toastMessage = toast?.querySelector('[data-toast-message]');
let toastTimer;

function hideToast() {
    if (!toast) {
        return;
    }

    toast.classList.add('pointer-events-none', 'translate-y-3', 'opacity-0');
    toast.classList.remove('translate-y-0', 'opacity-100');
}

function showToast(title, message) {
    if (!toast) {
        return;
    }

    toastTitle.textContent = title;
    toastMessage.textContent = message;
    toast.classList.remove('pointer-events-none', 'translate-y-3', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    clearTimeout(toastTimer);
    toastTimer = setTimeout(hideToast, 4500);
}

if (toast) {
    toast.querySelector('[data-toast-close]')?.addEventListener('click', hideToast);

    if (toast.dataset.visible === 'true') {
        toastTimer = setTimeout(hideToast, 4500);
    }
}

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const input = document.getElementById(button.dataset.passwordToggle);
    const showIcon = button.querySelector('[data-password-show]');
    const hideIcon = button.querySelector('[data-password-hide]');

    if (!input) {
        return;
    }

    button.addEventListener('click', () => {
        const isVisible = input.type === 'text';

        input.type = isVisible ? 'password' : 'text';
        button.setAttribute('aria-pressed', String(!isVisible));
        button.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
        showIcon?.classList.toggle('hidden', !isVisible);
        hideIcon?.classList.toggle('hidden', isVisible);
    });
});

document.querySelectorAll('[data-demo-email]').forEach((button) => {
    button.addEventListener('click', () => {
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        if (!email || !password) {
            return;
        }

        email.value = button.dataset.demoEmail;
        password.value = button.dataset.demoPassword;
        email.dispatchEvent(new Event('input', { bubbles: true }));
        password.dispatchEvent(new Event('input', { bubbles: true }));
        showToast(`Akses demo ${button.dataset.demoName}`, 'Kredensial demo telah diisi. Tekan Masuk untuk melanjutkan.');
        email.focus();
    });
});

const strengthInput = document.querySelector('[data-strength-input]');
const strengthRoot = document.querySelector('[data-strength]');

if (strengthInput && strengthRoot) {
    const bars = [...strengthRoot.querySelectorAll('[data-strength-bar]')];
    const label = strengthRoot.querySelector('[data-strength-label]');
    const colors = ['bg-divider', 'bg-danger', 'bg-warning', 'bg-success', 'bg-success'];
    const labels = ['Belum diisi', 'Lemah', 'Sedang', 'Kuat', 'Sangat kuat'];
    const labelColors = ['text-body/70', 'text-danger', 'text-warning', 'text-success', 'text-success'];

    const calculateLevel = (value) => {
        if (!value) {
            return 0;
        }

        let score = 0;

        if (value.length >= 8) score++;
        if (value.length >= 12) score++;
        if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
        if (/\d/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        return Math.max(1, Math.min(score, 4));
    };

    const updateStrength = () => {
        const level = calculateLevel(strengthInput.value);

        bars.forEach((bar, index) => {
            bar.classList.remove(...colors);
            bar.classList.add(colors[index < level ? level : 0]);
        });

        if (label) {
            label.textContent = labels[level];
            label.classList.remove(...labelColors);
            label.classList.add(labelColors[level]);
        }
    };

    strengthInput.addEventListener('input', updateStrength);
    updateStrength();
}
