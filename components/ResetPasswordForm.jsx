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
    <div className="auth-card auth-card--login">
      {/* Card Header & Branding */}
      <div className="card-header">
        <h2 className="brand-logo-text">TwinBug</h2>

        <div className="badge-pill">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
          </svg>
          <span>Konfigurasi Sandi Baru</span>
        </div>

        <h1 id="reset-heading" className="card-title">Atur Kata Sandi Baru</h1>
        <p className="card-subtitle">
          Buat kata sandi baru yang kuat untuk melindungi akun TwinBug Anda.
        </p>
      </div>

      <form className="auth-form" onSubmit={handleSubmit}>
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
              aria-label={showPassword ? 'Sembunyikan' : 'Tampilkan'}
            >
              {showPassword ? (
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="m9.88 9.88 4.24 4.24m-1.41-4.24 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                  <line x1="2" y1="2" x2="22" y2="22"></line>
                </svg>
              ) : (
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              )}
            </button>
          </div>
        </div>

        <div className="form-group">
          <label htmlFor="reset-confirm-password" className="form-label">Konfirmasi Kata Sandi Baru</label>
          <div className="input-wrapper">
            <span className="input-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
              </svg>
            </span>
            <input
              type={showConfirmPassword ? 'text' : 'password'}
              id="reset-confirm-password"
              className="form-input has-toggle"
              placeholder="Ulangi kata sandi baru"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
              autoComplete="new-password"
            />
            <button
              type="button"
              className="btn-toggle-password"
              onClick={() => setShowConfirmPassword(!showConfirmPassword)}
              aria-label={showConfirmPassword ? 'Sembunyikan' : 'Tampilkan'}
            >
              {showConfirmPassword ? (
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="m9.88 9.88 4.24 4.24m-1.41-4.24 4.24 4.24M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                  <line x1="2" y1="2" x2="22" y2="22"></line>
                </svg>
              ) : (
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              )}
            </button>
          </div>
        </div>

        {/* Strength bars */}
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

        <button type="submit" className="btn-primary-cta" disabled={loading}>
          <span>{loading ? 'Memperbarui...' : 'Simpan Kata Sandi Baru'}</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
          </svg>
        </button>

        <div className="switch-auth-link" style={{ marginTop: '16px' }}>
          {onSwitchView ? (
            <button
              type="button"
              onClick={() => onSwitchView('login')}
              className="link-accent"
              style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0, fontWeight: 700 }}
            >
              Kembali ke Halaman Masuk
            </button>
          ) : (
            <Link href="/login" className="link-accent">Kembali ke Halaman Masuk</Link>
          )}
        </div>
      </form>
    </div>
  );
}
