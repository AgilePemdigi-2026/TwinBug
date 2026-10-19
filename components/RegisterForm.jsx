'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { authService } from '../lib/supabase';
import { useToast } from './Toast';

export default function RegisterForm({ onSwitchView }) {
  const router = useRouter();
  const { showToast } = useToast();

  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('reporter'); // 'reporter' | 'developer'
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [agreeTerms, setAgreeTerms] = useState(true);
  const [loading, setLoading] = useState(false);

  // Evaluate Password Strength
  const evaluatePassword = (pwd) => {
    if (!pwd) return { score: 0, label: 'Belum terisi', colorClass: '', color: 'var(--color-text-muted)' };
    let score = 0;
    if (pwd.length >= 8) score++;
    if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
    if (/[0-9]/.test(pwd)) score++;
    if (/[^a-zA-Z0-9]/.test(pwd)) score++;

    if (score <= 1) return { score: 1, label: 'Lemah', colorClass: 'filled-red', color: 'var(--color-strength-weak)' };
    if (score === 2) return { score: 2, label: 'Sedang', colorClass: 'filled-orange', color: 'var(--color-strength-medium)' };
    if (score === 3) return { score: 3, label: 'Kuat', colorClass: 'filled-green', color: 'var(--color-strength-active)' };
    return { score: 4, label: 'Sangat Kuat', colorClass: 'filled-green', color: 'var(--color-strength-active)' };
  };

  const strength = evaluatePassword(password);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!fullName.trim()) {
      showToast('Form Belum Lengkap', 'Nama lengkap wajib diisi.', 'error');
      return;
    }
    if (!email.trim()) {
      showToast('Form Belum Lengkap', 'Email wajib diisi.', 'error');
      return;
    }
    if (password.length < 8) {
      showToast('Kata Sandi Lemah', 'Kata sandi minimal harus 8 karakter.', 'error');
      return;
    }
    if (password !== confirmPassword) {
      showToast('Kata Sandi Tidak Cocok', 'Konfirmasi kata sandi tidak sama dengan kata sandi utama.', 'error');
      return;
    }
    if (!agreeTerms) {
      showToast('Persetujuan Diperlukan', 'Harap setujui Syarat & Ketentuan Layanan TwinBug.', 'error');
      return;
    }

    setLoading(true);
    try {
      await authService.register({
        email,
        password,
        fullName,
        role,
      });

      showToast(
        'Pendaftaran Berhasil!',
        `Akun ${fullName} (${role.toUpperCase()}) berhasil dibuat. Silakan masuk.`,
        'success'
      );

      if (onSwitchView) {
        onSwitchView('login');
      } else {
        router.push('/login');
      }
    } catch (err) {
      console.error('Registration error:', err);
      showToast('Pendaftaran Gagal', err.message || 'Terjadi kesalahan saat mendaftar.', 'error');
    } finally {
      setLoading(false);
    }
  };

  return (
    <section id="view-register" className="auth-view active" aria-labelledby="register-heading">
      <div className="auth-card auth-card--register">

        {/* Card Header & Branding */}
        <div className="card-header">
          <div className="badge-pill badge-pill--register">
            <span className="badge-dot" aria-hidden="true"></span>
            <span>Pendaftaran Akun Baru</span>
          </div>

          <h1 id="register-heading" className="card-title card-title--large">Buat Akun TwinBug</h1>
        </div>

        {/* Registration Form */}
        <form id="register-form" className="auth-form" onSubmit={handleSubmit} autoComplete="on">

          {/* Row 1: Full Name & Company Email */}
          <div className="form-grid-2col">
            
            {/* Nama Lengkap */}
            <div className="form-group">
              <div className="form-label-row">
                <label htmlFor="register-name" className="form-label--register">Nama Lengkap</label>
                <span className="label-hint">Wajib</span>
              </div>
              <div className="input-wrapper">
                <span className="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                  </svg>
                </span>
                <input 
                  type="text" 
                  id="register-name" 
                  name="name" 
                  className="form-input form-input--register" 
                  placeholder="Nama lengkap Anda" 
                  value={fullName}
                  onChange={(e) => setFullName(e.target.value)}
                  required
                  autoComplete="name"
                />
              </div>
            </div>

            {/* Email Perusahaan */}
            <div className="form-group">
              <div className="form-label-row">
                <label htmlFor="register-email" className="form-label--register">Email</label>
                <span className="label-hint">Domain Kerja</span>
              </div>
              <div className="input-wrapper">
                <span className="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                    <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                    <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                    <path d="M10 6h4"></path>
                    <path d="M10 10h4"></path>
                    <path d="M10 14h4"></path>
                    <path d="M10 18h4"></path>
                  </svg>
                </span>
                <input 
                  type="email" 
                  id="register-email" 
                  name="email" 
                  className="form-input form-input--register" 
                  placeholder="nama@perusahaan.com" 
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  autoComplete="email"
                />
              </div>
            </div>

          </div>

          {/* Row 2: Role Selector (Bespoke Interactive Radio Cards) */}
          <div className="role-selector-container">
            <label className="form-label--register">Role</label>
            
            <div className="role-cards-grid">
              
              {/* Role 1: Reporter */}
              <div 
                className={`role-card ${role === 'reporter' ? 'active' : ''}`}
                onClick={() => setRole('reporter')}
                tabIndex="0" 
                role="radio" 
                aria-checked={role === 'reporter'} 
                aria-label="Role Reporter"
              >
                <div className="role-card-icon-box">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <path d="m9 14 2 2 4-4"></path>
                  </svg>
                </div>
                <div className="role-card-info">
                  <span className="role-card-name">Reporter</span>
                  <span className="role-card-radio-indicator">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                  </span>
                </div>
              </div>

              {/* Role 2: Developer */}
              <div 
                className={`role-card ${role === 'developer' ? 'active' : ''}`}
                onClick={() => setRole('developer')}
                tabIndex="0" 
                role="radio" 
                aria-checked={role === 'developer'} 
                aria-label="Role Developer"
              >
                <div className="role-card-icon-box">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                    <path d="m7 10 2 2-2 2"></path>
                    <path d="M13 14h4"></path>
                    <path d="M8 21h8"></path>
                    <path d="M12 17v4"></path>
                  </svg>
                </div>
                <div className="role-card-info">
                  <span className="role-card-name">Developer</span>
                  <span className="role-card-radio-indicator">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                      <path d="M20 6 9 17l-5-5"></path>
                    </svg>
                  </span>
                </div>
              </div>

            </div>
          </div>

          {/* Row 3: Password Fields Section (2 Columns) */}
          <div className="form-grid-2col">
            
            {/* Kata Sandi */}
            <div className="form-group">
              <label htmlFor="register-password" className="form-label--register">Kata Sandi</label>
              <div className="input-wrapper">
                <span className="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                  </svg>
                </span>
                <input 
                  type={showPassword ? 'text' : 'password'} 
                  id="register-password" 
                  name="new-password" 
                  className="form-input form-input--register has-toggle" 
                  placeholder="••••••••••••" 
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  autoComplete="new-password"
                />
                <button 
                  type="button" 
                  className="btn-toggle-password" 
                  onClick={() => setShowPassword(!showPassword)}
                  aria-label={showPassword ? "Sembunyikan kata sandi" : "Tampilkan kata sandi"}
                >
                  {showPassword ? (
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                      <path d="m9.88 9.88 4.24 4.24m-1.41-4.24 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                      <line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                  ) : (
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  )}
                </button>
              </div>
            </div>

            {/* Konfirmasi Kata Sandi */}
            <div className="form-group">
              <label htmlFor="register-confirm-password" className="form-label--register">Konfirmasi Kata Sandi</label>
              <div className="input-wrapper">
                <span className="input-icon">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                  </svg>
                </span>
                <input 
                  type={showConfirmPassword ? 'text' : 'password'} 
                  id="register-confirm-password" 
                  name="confirm-password" 
                  className="form-input form-input--register has-toggle" 
                  placeholder="••••••••••••" 
                  value={confirmPassword}
                  onChange={(e) => setConfirmPassword(e.target.value)}
                  required
                  autoComplete="new-password"
                />
                <button 
                  type="button" 
                  className="btn-toggle-password" 
                  onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                  aria-label={showConfirmPassword ? "Sembunyikan kata sandi" : "Tampilkan kata sandi"}
                >
                  {showConfirmPassword ? (
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                      <path d="m9.88 9.88 4.24 4.24m-1.41-4.24 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                      <line x1="2" y1="2" x2="22" y2="22"></line>
                    </svg>
                  ) : (
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                      <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                      <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                  )}
                </button>
              </div>
            </div>

          </div>

          {/* Password Strength Tracker */}
          <div className="password-strength-card">
            <div className="strength-header-row">
              <span className="strength-label">Kekuatan Kata Sandi:</span>
              <span id="strength-status" className="strength-status-text" style={{ color: strength.color }}>
                {strength.label}
              </span>
            </div>
            <div className="strength-bars-row" aria-label="Indikator kekuatan kata sandi">
              {[1, 2, 3, 4].map((i) => (
                <div 
                  key={i} 
                  className={`strength-bar ${i <= strength.score ? strength.colorClass : ''}`}
                ></div>
              ))}
            </div>
            <p className="strength-requirements-text">
              Karakter minimal 8, kombinasi huruf besar-kecil, angka, &amp; simbol (@, #, $).
            </p>
          </div>

          {/* Agreement Checkbox */}
          <label className="checkbox-row">
            <input 
              type="checkbox" 
              id="register-terms" 
              className="checkbox-input" 
              checked={agreeTerms}
              onChange={(e) => setAgreeTerms(e.target.checked)}
              required
            />
            <span className="checkbox-custom">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
              </svg>
            </span>
            <span className="checkbox-label-text">
              Saya menyetujui Ketentuan Layanan &amp; Kebijakan Privasi TwinBug terkait pemrosesan dataset telemetri bug.
            </span>
          </label>

          {/* Submit Button */}
          <button type="submit" id="btn-register-submit" className="btn-primary-cta btn-primary-cta--register" disabled={loading}>
            <span>{loading ? 'Mendaftarkan Akun...' : 'Daftar Akun Baru'}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </button>

          {/* Footer Switch Link */}
          <div className="card-footer-switch">
            <span>Sudah memiliki akun?</span>
            {onSwitchView ? (
              <button
                type="button"
                onClick={() => onSwitchView('login')}
                className="js-goto-login"
                style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}
              >
                Masuk di sini
              </button>
            ) : (
              <Link href="/login" className="js-goto-login">Masuk di sini</Link>
            )}
          </div>

        </form>

      </div>

      {/* Micro-credits for Register */}
      <footer className="bottom-microcredits" aria-label="Informasi Kepatuhan TwinBug">
        <span className="credit-item">© 2025 TwinBug Engine</span>
        <span className="credit-dot">•</span>
        <span className="credit-item">Enterprise Grade Security</span>
        <span className="credit-dot">•</span>
        <span className="credit-item">SOC2 Compliant</span>
      </footer>

    </section>
  );
}
