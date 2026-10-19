'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { authService } from '../lib/supabase';
import { useToast } from './Toast';

export default function ResetPasswordForm({ onSwitchView }) {
  const router = useRouter();
  const { showToast } = useToast();

  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState(false);
  const [loading, setLoading] = useState(false);

  // Strength evaluation
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
    if (password.length < 8) {
      showToast('Kata Sandi Terlalu Pendek', 'Kata sandi baru minimal 8 karakter.', 'error');
      return;
    }
    if (password !== confirmPassword) {
      showToast('Kata Sandi Tidak Cocok', 'Konfirmasi kata sandi baru tidak sesuai.', 'error');
      return;
    }

    setLoading(true);
    try {
      await authService.updatePassword(password);
      showToast(
        'Kata Sandi Diperbarui!',
        'Kata sandi akun Anda berhasil diganti. Silakan masuk kembali.',
        'success'
      );
      if (onSwitchView) {
        onSwitchView('login');
      } else {
        router.push('/login');
      }
    } catch (err) {
      console.error('Update password error:', err);
      showToast('Gagal Memperbarui', err.message || 'Terjadi kesalahan sistem.', 'error');
    } finally {
      setLoading(false);
    }
  };

  return (
    <section id="view-reset-password" className="auth-view active" aria-labelledby="reset-heading">
      <div className="auth-card auth-card--login">
        
        {/* Card Header & Branding */}
        <div className="card-header">
          <h2 className="brand-logo-text">TwinBug</h2>
          <div className="badge-pill">
            <span className="badge-dot" aria-hidden="true"></span>
            <span>Atur Ulang Kata Sandi</span>
          </div>
          <h1 id="reset-heading" className="card-title">Buat Kata Sandi Baru</h1>
          <p className="card-subtitle">
            Masukkan kata sandi baru yang kuat untuk mengamankan akun TwinBug Anda.
          </p>
        </div>

        <form id="reset-password-form" className="auth-form" onSubmit={handleSubmit} autoComplete="off">
          
          {/* Kata Sandi Baru */}
          <div className="form-group">
            <label htmlFor="reset-new-password" className="form-label">Kata Sandi Baru</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type={showPassword ? 'text' : 'password'} 
                id="reset-new-password" 
                name="new-password" 
                className="form-input has-toggle" 
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

          {/* Konfirmasi Kata Sandi Baru */}
          <div className="form-group">
            <label htmlFor="reset-confirm-password" className="form-label">Konfirmasi Kata Sandi Baru</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
                </svg>
              </span>
              <input 
                type={showConfirmPassword ? 'text' : 'password'} 
                id="reset-confirm-password" 
                name="confirm-password" 
                className="form-input has-toggle" 
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

          <button type="submit" id="btn-reset-submit" className="btn-primary-cta" disabled={loading}>
            <span>{loading ? 'Memperbarui...' : 'Simpan Kata Sandi Baru'}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </button>

          <div className="card-footer-switch">
            <span>Ingat kata sandi Anda?</span>
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

      <footer className="bottom-microcredits">
        <span className="credit-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
          </svg>
          <span>Enkripsi AES-256</span>
        </span>
        <span className="credit-dot">•</span>
        <span className="credit-item">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"></path>
          </svg>
          <span>SSO Enterprise Siap</span>
        </span>
      </footer>
    </section>
  );
}
