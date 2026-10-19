/**
 * TwinBug - Multimodal Bug Intelligence System
 * Authentication & Interaction Logic
 */

document.addEventListener('DOMContentLoaded', () => {
  // Elements - Navigation / Views
  const loginView = document.getElementById('view-login');
  const registerView = document.getElementById('view-register');
  const toRegisterLinks = document.querySelectorAll('.js-goto-register');
  const toLoginLinks = document.querySelectorAll('.js-goto-login');

  // Elements - Forms
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');

  // Elements - Demo Quick Access
  const demoReporterBtn = document.getElementById('btn-demo-reporter');
  const demoDeveloperBtn = document.getElementById('btn-demo-developer');
  const loginEmailInput = document.getElementById('login-email');
  const loginPasswordInput = document.getElementById('login-password');

  // Elements - Role Selection (Register)
  const roleCards = document.querySelectorAll('.role-card');
  const selectedRoleInput = document.getElementById('selected-role');

  // Elements - Password Strength
  const registerPasswordInput = document.getElementById('register-password');
  const registerConfirmPasswordInput = document.getElementById('register-confirm-password');
  const strengthStatusText = document.getElementById('strength-status');
  const strengthBars = document.querySelectorAll('.strength-bar');

  // Elements - Password Visibility Toggles
  const togglePasswordBtns = document.querySelectorAll('.btn-toggle-password');

  // Elements - Toast
  const toast = document.getElementById('toast-notification');
  const toastTitle = document.getElementById('toast-title');
  const toastDesc = document.getElementById('toast-desc');
  let toastTimeout = null;

  /* --------------------------------------------------------------------------
     1. View Routing & Hash Management
     -------------------------------------------------------------------------- */
  const dashboardView = document.getElementById('view-dashboard');
  const forgotPasswordView = document.getElementById('view-forgot-password');
  const resetPasswordView = document.getElementById('view-reset-password');
  const toForgotLinks = document.querySelectorAll('.js-goto-forgot');

  const allViews = [loginView, registerView, dashboardView, forgotPasswordView, resetPasswordView];

  function switchView(viewName) {
    allViews.forEach(v => {
      if (v) {
        v.classList.remove('active');
        v.style.display = 'none';
      }
    });

    if (viewName === 'register') {
      registerView.style.display = 'flex';
      requestAnimationFrame(() => registerView.classList.add('active'));
      if (window.location.hash !== '#register') window.location.hash = 'register';
      document.title = 'TwinBug - Buat Akun';
    } else if (viewName === 'dashboard') {
      dashboardView.style.display = 'flex';
      requestAnimationFrame(() => dashboardView.classList.add('active'));
      if (window.location.hash !== '#dashboard') window.location.hash = 'dashboard';
      document.title = 'TwinBug - Dashboard';
    } else if (viewName === 'forgot-password') {
      forgotPasswordView.style.display = 'flex';
      requestAnimationFrame(() => forgotPasswordView.classList.add('active'));
      if (window.location.hash !== '#forgot-password') window.location.hash = 'forgot-password';
      document.title = 'TwinBug - Lupa Kata Sandi';
    } else if (viewName === 'reset-password') {
      resetPasswordView.style.display = 'flex';
      requestAnimationFrame(() => resetPasswordView.classList.add('active'));
      if (window.location.hash !== '#reset-password') window.location.hash = 'reset-password';
      document.title = 'TwinBug - Atur Ulang Kata Sandi';
    } else {
      loginView.style.display = 'flex';
      requestAnimationFrame(() => loginView.classList.add('active'));
      if (window.location.hash !== '#login') window.location.hash = 'login';
      document.title = 'TwinBug - Masuk ke Akun';
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  toRegisterLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      switchView('register');
    });
  });

  toLoginLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      switchView('login');
    });
  });

  toForgotLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      switchView('forgot-password');
    });
  });

  const logoutBtn = document.getElementById('btn-dashboard-logout');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', async () => {
      if (window.TwinBugSupabase) {
        await window.TwinBugSupabase.logout();
      }
      showToast('Berhasil Keluar', 'Sesi Supabase Anda telah diakhiri.');
      switchView('login');
    });
  }

  // Display user in dashboard view
  function showDashboard(user, profile) {
    const name = (profile && profile.full_name) || (user.user_metadata && user.user_metadata.full_name) || user.email.split('@')[0];
    const role = (profile && profile.role) || (user.user_metadata && user.user_metadata.role) || 'reporter';

    document.getElementById('dashboard-user-greeting').textContent = `Selamat datang kembali di sistem intelijen bug multimodal.`;
    document.getElementById('dashboard-user-name').textContent = name;
    document.getElementById('dashboard-user-email').textContent = user.email;
    const roleBadge = document.getElementById('dashboard-user-role');
    roleBadge.textContent = role.toUpperCase();
    if (role === 'developer') {
      roleBadge.style.background = 'rgba(70, 110, 150, 0.15)';
      roleBadge.style.color = '#2E5276';
    } else {
      roleBadge.style.background = 'rgba(74, 124, 89, 0.15)';
      roleBadge.style.color = '#4A7C59';
    }

    switchView('dashboard');
  }

  // Handle Supabase auth state change (like PASSWORD_RECOVERY)
  if (window.TwinBugSupabase && window.TwinBugSupabase.client) {
    window.TwinBugSupabase.client.auth.onAuthStateChange((event, session) => {
      if (event === 'PASSWORD_RECOVERY') {
        switchView('reset-password');
        showToast('Mode Pemulihan', 'Tautan pemulihan valid. Silakan buat kata sandi baru Anda.');
      }
    });
  }

  // Check URL parameters / hash
  function checkRouteFromUrl() {
    const hash = window.location.hash.replace('#', '');
    const search = window.location.search;
    
    if (hash.includes('type=recovery') || search.includes('type=recovery') || hash === 'reset-password') {
      switchView('reset-password');
    } else if (hash === 'forgot-password') {
      switchView('forgot-password');
    } else if (hash === 'register') {
      switchView('register');
    } else if (hash === 'dashboard') {
      switchView('dashboard');
    } else {
      switchView('login');
    }
  }

  window.addEventListener('hashchange', checkRouteFromUrl);
  checkRouteFromUrl();

  /* --------------------------------------------------------------------------
     2. Password Visibility Toggle
     -------------------------------------------------------------------------- */
  const EYE_ICON_SVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
  const EYE_OFF_ICON_SVG = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path><line x1="2" x2="22" y1="2" y2="22"></line></svg>`;

  togglePasswordBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (!input) return;

      if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = EYE_OFF_ICON_SVG;
        btn.setAttribute('aria-label', 'Sembunyikan kata sandi');
      } else {
        input.type = 'password';
        btn.innerHTML = EYE_ICON_SVG;
        btn.setAttribute('aria-label', 'Tampilkan kata sandi');
      }
    });
  });

  /* --------------------------------------------------------------------------
     3. Role Selector (Reporter vs Developer)
     -------------------------------------------------------------------------- */
  roleCards.forEach(card => {
    function selectRole() {
      roleCards.forEach(c => {
        c.classList.remove('active');
        c.setAttribute('aria-checked', 'false');
      });
      card.classList.add('active');
      card.setAttribute('aria-checked', 'true');
      const role = card.getAttribute('data-role');
      if (selectedRoleInput) {
        selectedRoleInput.value = role;
      }
    }

    card.addEventListener('click', selectRole);
    card.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        selectRole();
      }
    });
  });

  /* --------------------------------------------------------------------------
     4. Password Strength Evaluation
     -------------------------------------------------------------------------- */
  function evaluatePasswordStrength(password) {
    if (!password || password.length === 0) {
      return { score: 0, label: 'Belum terisi', colorClass: '' };
    }

    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) score++;

    if (score <= 1) {
      return { score: 1, label: 'Lemah', colorClass: 'filled-red' };
    } else if (score === 2) {
      return { score: 2, label: 'Cukup', colorClass: 'filled-orange' };
    } else if (score === 3) {
      return { score: 3, label: 'Kuat', colorClass: 'filled-green' };
    } else {
      return { score: 4, label: 'Sangat Kuat', colorClass: 'filled-green' };
    }
  }

  if (registerPasswordInput) {
    registerPasswordInput.addEventListener('input', (e) => {
      const val = e.target.value;
      const result = evaluatePasswordStrength(val);

      if (strengthStatusText) {
        strengthStatusText.textContent = result.label;
        if (result.score >= 3) {
          strengthStatusText.style.color = '#007C00';
        } else if (result.score === 2) {
          strengthStatusText.style.color = '#F0AD4E';
        } else if (result.score === 1) {
          strengthStatusText.style.color = '#D9534F';
        } else {
          strengthStatusText.style.color = '#705C30';
        }
      }

      strengthBars.forEach((bar, index) => {
        bar.className = 'strength-bar';
        if (index < result.score) {
          bar.classList.add(result.colorClass);
        }
      });
    });
  }

  /* --------------------------------------------------------------------------
     5. Demo Quick Access Autofill (Connected to seeded Supabase accounts)
     -------------------------------------------------------------------------- */
  if (demoReporterBtn) {
    demoReporterBtn.addEventListener('click', () => {
      loginEmailInput.value = 'reporter.demo@gmail.com';
      loginPasswordInput.value = 'TwinBug@Demo2025!';
      showToast('Akses Demo Reporter', 'Kredensial demo Reporter Supabase telah diisi. Klik "Masuk ke Sistem".');
      loginEmailInput.focus();
    });
  }

  if (demoDeveloperBtn) {
    demoDeveloperBtn.addEventListener('click', () => {
      loginEmailInput.value = 'developer.demo@gmail.com';
      loginPasswordInput.value = 'TwinBug@Demo2025!';
      showToast('Akses Demo Developer', 'Kredensial demo Developer Supabase telah diisi. Klik "Masuk ke Sistem".');
      loginEmailInput.focus();
    });
  }

  /* --------------------------------------------------------------------------
     6. Form Submission Handlers with Real Supabase Backend Integration
     -------------------------------------------------------------------------- */
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = loginEmailInput.value.trim();
      const password = loginPasswordInput.value;

      if (!email || !password) {
        showToast('Validasi Gagal', 'Mohon isi email dan kata sandi Anda.');
        return;
      }

      const submitBtn = loginForm.querySelector('.btn-primary-cta');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span>Menghubungi Supabase...</span>`;

      try {
        const authData = await window.TwinBugSupabase.login({ email, password });
        const user = authData.user;
        let profile = null;

        try {
          profile = await window.TwinBugSupabase.getProfile(user.id);
        } catch (profileErr) {
          console.warn('Profile fetch warning:', profileErr);
        }

        const displayName = (profile && profile.full_name) || (user.user_metadata && user.user_metadata.full_name) || email;
        const role = (profile && profile.role) || (user.user_metadata && user.user_metadata.role) || 'reporter';

        showToast('Berhasil Masuk!', `Selamat datang, ${displayName}! Role: ${role.toUpperCase()}`);
        
        setTimeout(() => {
          showDashboard(user, profile);
        }, 600);
      } catch (err) {
        console.error('Login error:', err);
        showToast('Gagal Masuk', err.message || 'Kredensial tidak valid atau akun belum terdaftar.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const name = document.getElementById('register-name').value.trim();
      const email = document.getElementById('register-email').value.trim();
      const password = registerPasswordInput.value;
      const confirmPassword = registerConfirmPasswordInput.value;
      const agreed = document.getElementById('register-terms').checked;
      const role = selectedRoleInput ? selectedRoleInput.value : 'reporter';

      if (!name || !email || !password) {
        showToast('Pendaftaran Gagal', 'Harap lengkapi semua kolom wajib.');
        return;
      }

      if (password !== confirmPassword) {
        showToast('Kata Sandi Tidak Cocok', 'Konfirmasi kata sandi harus sama dengan kata sandi yang dibuat.');
        registerConfirmPasswordInput.focus();
        return;
      }

      if (!agreed) {
        showToast('Persetujuan Dibutuhkan', 'Harap menyetujui Ketentuan Layanan TwinBug sebelum mendaftar.');
        return;
      }

      const submitBtn = registerForm.querySelector('.btn-primary-cta');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span>Mendaftarkan ke Supabase...</span>`;

      try {
        const regData = await window.TwinBugSupabase.register({
          email,
          password,
          fullName: name,
          role
        });

        showToast('Akun Berhasil Dibuat!', `Akun ${name} (${role.toUpperCase()}) telah terdaftar di Supabase! Mengalihkan ke login...`);
        
        setTimeout(() => {
          switchView('login');
          loginEmailInput.value = email;
          loginPasswordInput.value = password;
          loginPasswordInput.focus();
        }, 1200);
      } catch (err) {
        console.error('Register error:', err);
        showToast('Pendaftaran Gagal', err.message || 'Gagal mendaftar ke Supabase. Periksa format email.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  }

  /* --------------------------------------------------------------------------
     6b. Password Reset & Recovery Handlers
     -------------------------------------------------------------------------- */
  const forgotForm = document.getElementById('forgot-password-form');
  const resetForm = document.getElementById('reset-password-form');
  const resetPasswordInput = document.getElementById('reset-new-password');
  const resetConfirmPasswordInput = document.getElementById('reset-confirm-password');
  const resetStrengthStatusText = document.getElementById('reset-strength-status');
  const resetStrengthBars = document.querySelectorAll('.reset-bar');

  // Strength tracker for new reset password
  if (resetPasswordInput) {
    resetPasswordInput.addEventListener('input', (e) => {
      const val = e.target.value;
      const result = evaluatePasswordStrength(val);

      if (resetStrengthStatusText) {
        resetStrengthStatusText.textContent = result.label;
        if (result.score >= 3) {
          resetStrengthStatusText.style.color = '#007C00';
        } else if (result.score === 2) {
          resetStrengthStatusText.style.color = '#F0AD4E';
        } else if (result.score === 1) {
          resetStrengthStatusText.style.color = '#D9534F';
        } else {
          resetStrengthStatusText.style.color = '#705C30';
        }
      }

      resetStrengthBars.forEach((bar, index) => {
        bar.className = 'strength-bar reset-bar';
        if (index < result.score) {
          bar.classList.add(result.colorClass);
        }
      });
    });
  }

  // Handle Request Password Reset Email Form
  if (forgotForm) {
    forgotForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const email = document.getElementById('forgot-email').value.trim();

      if (!email) {
        showToast('Validasi Gagal', 'Mohon masukkan email Anda.');
        return;
      }

      const submitBtn = forgotForm.querySelector('.btn-primary-cta');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span>Mengirim ke Supabase...</span>`;

      try {
        await window.TwinBugSupabase.requestPasswordReset(email);
        showToast('Tautan Pemulihan Dikirim!', `Tautan pemulihan kata sandi telah dikirim ke ${email}. Periksa kotak masuk Anda.`);
      } catch (err) {
        console.error('Password reset request error:', err);
        showToast('Gagal Mengirim', err.message || 'Gagal mengirim email pemulihan. Pastikan email terdaftar.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  }

  // Handle Save New Password Form
  if (resetForm) {
    resetForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const newPassword = resetPasswordInput.value;
      const confirmPassword = resetConfirmPasswordInput.value;

      if (!newPassword || newPassword.length < 8) {
        showToast('Validasi Gagal', 'Kata sandi minimal harus 8 karakter.');
        resetPasswordInput.focus();
        return;
      }

      if (newPassword !== confirmPassword) {
        showToast('Kata Sandi Tidak Cocok', 'Konfirmasi kata sandi harus sama persis.');
        resetConfirmPasswordInput.focus();
        return;
      }

      const submitBtn = resetForm.querySelector('.btn-primary-cta');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = `<span>Menyimpan ke Supabase...</span>`;

      try {
        await window.TwinBugSupabase.updatePassword(newPassword);
        showToast('Kata Sandi Berhasil Diperbarui!', 'Kata sandi baru Anda telah aktif. Mengalihkan ke halaman login...');

        setTimeout(() => {
          switchView('login');
          loginPasswordInput.value = '';
          loginPasswordInput.focus();
        }, 1500);
      } catch (err) {
        console.error('Password update error:', err);
        showToast('Pembaruan Gagal', err.message || 'Gagal memperbarui kata sandi. Tautan pemulihan mungkin sudah kedaluwarsa.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
      }
    });
  }

  /* --------------------------------------------------------------------------
     7. Toast Helper
     -------------------------------------------------------------------------- */
  function showToast(title, description) {
    if (!toast) return;
    if (toastTimeout) clearTimeout(toastTimeout);

    toastTitle.textContent = title;
    toastDesc.textContent = description;
    toast.classList.add('show');

    toastTimeout = setTimeout(() => {
      toast.classList.remove('show');
    }, 4500);
  }
});
