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
  const [agreeTerms, setAgreeTerms] = useState(false);
  const [loading, setLoading] = useState(false);

  // Calculate Password Strength
  const getPasswordStrength = (pwd) => {
    if (!pwd) return { score: 0, label: 'Belum terisi', color: 'var(--color-strength-empty)' };
    let score = 0;
    if (pwd.length >= 8) score++;
    if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
    if (/[0-9]/.test(pwd)) score++;
    if (/[^a-zA-Z0-9]/.test(pwd)) score++;

    if (score <= 1) return { score: 1, label: 'Lemah', color: 'var(--color-strength-weak)' };
    if (score === 2) return { score: 2, label: 'Sedang', color: 'var(--color-strength-medium)' };
    if (score === 3) return { score: 3, label: 'Kuat', color: 'var(--color-strength-active)' };
    return { score: 4, label: 'Sangat Kuat', color: 'var(--color-strength-active)' };
  };

  const strength = getPasswordStrength(password);

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
      const data = await authService.register({
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
    <div className="auth-card auth-card--register">
      {/* Card Header & Branding */}
      <div className="card-header">
        <h2 className="brand-logo-text">TwinBug</h2>

        <div className="badge-pill">
          <span className="badge-dot" aria-hidden="true"></span>
          <span>PENDAFTARAN AKUN BARU</span>
        </div>

        <h1 id="register-heading" className="card-title">Buat Akun TwinBug</h1>
        <p className="card-subtitle">
          Bergabunglah dengan ekosistem deteksi bug multimodal cerdas
        </p>
      </div>

      {/* Register Form */}
      <form className="auth-form" onSubmit={handleSubmit} autoComplete="on">
        {/* Row 1: Full Name & Email */}
        <div className="form-row">
          <div className="form-group">
            <label htmlFor="reg-fullname" className="form-label">Nama Lengkap</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
              <input
                type="text"
                id="reg-fullname"
                className="form-input"
                placeholder="Contoh: Alex Rivera"
                value={fullName}
                onChange={(e) => setFullName(e.target.value)}
                required
                autoComplete="name"
              />
            </div>
          </div>

          <div className="form-group">
            <label htmlFor="reg-email" className="form-label">Email Kerja</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                </svg>
              </span>
              <input
                type="email"
                id="reg-email"
                className="form-input"
                placeholder="nama@perusahaan.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                autoComplete="email"
              />
            </div>
          </div>
        </div>

        {/* Role Selector */}
        <div className="form-group">
          <label className="form-label">Pilih Peran Utama Anda</label>
          <div className="role-selector-grid">
            <label className={`role-card ${role === 'reporter' ? 'active' : ''}`}>
              <input
                type="radio"
                name="role"
                value="reporter"
                checked={role === 'reporter'}
                onChange={() => setRole('reporter')}
                className="sr-only"
              />
              <div className="role-radio-custom">
                <div className="role-radio-inner"></div>
              </div>
              <div className="role-info">
                <div className="role-title-row">
                  <span className="role-name">Reporter (QA / Tester)</span>
                  <span className="role-badge">Utama</span>
                </div>
                <p className="role-desc">Laporkan bug baru, unggah tangkapan layar, dan pantau status duplikasi.</p>
              </div>
            </label>

            <label className={`role-card ${role === 'developer' ? 'active' : ''}`}>
              <input
                type="radio"
                name="role"
                value="developer"
                checked={role === 'developer'}
                onChange={() => setRole('developer')}
                className="sr-only"
              />
              <div className="role-radio-custom">
                <div className="role-radio-inner"></div>
              </div>
              <div className="role-info">
                <div className="role-title-row">
                  <span className="role-name">Developer / Triage</span>
                  <span className="role-badge">Triage</span>
                </div>
                <p className="role-desc">Tinjau klaster bug duplikat, verifikasi skor kemiripan multimodal, dan debug.</p>
              </div>
            </label>
          </div>
        </div>

        {/* Row 2: Password & Confirm Password */}
        <div className="form-row">
          <div className="form-group">
            <label htmlFor="reg-password" className="form-label">Kata Sandi</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input
                type={showPassword ? 'text' : 'password'}
                id="reg-password"
                className="form-input has-toggle"
                placeholder="Minimal 8 karakter"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                autoComplete="new-password"
              />
              <button
                type="button"
                className="btn-toggle-password"
                onClick={() => setShowPassword(!showPassword)}
                aria-label={showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'}
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

          <div className="form-group">
            <label htmlFor="reg-confirm-password" className="form-label">Konfirmasi Kata Sandi</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
              </span>
              <input
                type={showConfirmPassword ? 'text' : 'password'}
                id="reg-confirm-password"
                className="form-input has-toggle"
                placeholder="Ulangi kata sandi"
                value={confirmPassword}
                onChange={(e) => setConfirmPassword(e.target.value)}
                required
                autoComplete="new-password"
              />
              <button
                type="button"
                className="btn-toggle-password"
                onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                aria-label={showConfirmPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'}
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

        {/* Password Strength Indicator */}
        <div className="strength-tracker">
          <div className="strength-bars">
            {[1, 2, 3, 4].map((i) => (
              <div
                key={i}
                className="strength-bar-segment"
                style={{
                  background: i <= strength.score ? strength.color : 'var(--color-strength-empty)',
                  height: '4px',
                  borderRadius: '2px',
                  transition: 'background 0.3s ease',
                }}
              ></div>
            ))}
          </div>
          <div className="strength-text-row">
            <span className="strength-label-prefix">Tingkat Kekuatan:</span>
            <span className="strength-value" style={{ color: strength.color, fontWeight: 700 }}>
              {strength.label}
            </span>
          </div>
        </div>

        {/* Terms & Conditions Checkbox */}
        <label className="checkbox-row terms-checkbox-row">
          <input
            type="checkbox"
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
            Saya menyetujui <a href="#terms" className="link-inline">Ketentuan Layanan</a> dan <a href="#privacy" className="link-inline">Kebijakan Privasi</a> TwinBug terkait analisis dataset laporan bug.
          </span>
        </label>

        {/* Submit CTA */}
        <button type="submit" className="btn-primary-cta" disabled={loading}>
          <span>{loading ? 'Mendaftarkan Akun...' : 'Daftar Akun Baru'}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
          </svg>
        </button>

        {/* Switch to Login */}
        <div className="switch-auth-link">
          <span>Sudah memiliki akun? </span>
          {onSwitchView ? (
            <button
              type="button"
              onClick={() => onSwitchView('login')}
              className="link-accent"
              style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0, fontWeight: 700 }}
            >
              Masuk di sini
            </button>
          ) : (
            <Link href="/login" className="link-accent">Masuk di sini</Link>
          )}
        </div>
      </form>

      {/* Card Footer */}
      <footer className="card-footer">
        <div className="footer-meta-item">
          <span>Enterprise Grade Security</span>
        </div>
        <div className="footer-dot-separator" aria-hidden="true">•</div>
        <div className="footer-meta-item">
          <span>SOC2 Compliant Ready</span>
        </div>
      </footer>
    </div>
  );
}
