'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { authService } from '../lib/supabase';
import { useToast } from './Toast';

export default function ForgotPasswordForm({ onSwitchView }) {
  const { showToast } = useToast();
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [sent, setSent] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!email) {
      showToast('Form Belum Lengkap', 'Silakan masukkan alamat email akun Anda.', 'error');
      return;
    }

    setLoading(true);
    try {
      await authService.requestPasswordReset(email);
      setSent(true);
      showToast(
        'Tautan Terkirim!',
        `Instruksi pemulihan kata sandi telah dikirimkan ke ${email}. Silakan cek kotak masuk Anda.`,
        'success'
      );
    } catch (err) {
      console.error('Password reset request error:', err);
      showToast('Gagal Mengirim Tautan', err.message || 'Terjadi kesalahan sistem.', 'error');
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
          {/* Key / Recovery Icon */}
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <circle cx="7.5" cy="15.5" r="5.5"></circle>
            <path d="m21 2-9.6 9.6"></path>
            <path d="m15.5 7.5 3 3L22 7l-3-3"></path>
          </svg>
          <span>Pemulihan Keamanan Akun</span>
        </div>

        <h1 id="forgot-heading" className="card-title">Lupa Kata Sandi?</h1>
        <p className="card-subtitle">
          Masukkan email terdaftar Anda. Kami akan mengirimkan tautan pemulihan kata sandi aman.
        </p>
      </div>

      {sent ? (
        <div style={{ textAlign: 'center', padding: '20px 0' }}>
          <div style={{
            width: '60px',
            height: '60px',
            borderRadius: '50%',
            background: 'rgba(74, 124, 89, 0.12)',
            color: 'var(--color-primary)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            margin: '0 auto 16px',
          }}>
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          </div>
          <h3 style={{ fontSize: '18px', fontWeight: 700, marginBottom: '8px', color: 'var(--color-text-title)' }}>
            Email Pemulihan Terkirim
          </h3>
          <p style={{ fontSize: '14px', color: 'var(--color-text-body)', lineHeight: 1.5, marginBottom: '24px' }}>
            Tautan reset kata sandi telah dikirim ke <strong>{email}</strong>. Periksa juga folder Spam/Junk jika belum muncul.
          </p>

          <button
            type="button"
            className="btn-primary-cta"
            onClick={() => onSwitchView ? onSwitchView('login') : null}
            style={{ width: '100%' }}
          >
            <span>Kembali ke Halaman Masuk</span>
          </button>
        </div>
      ) : (
        <form className="auth-form" onSubmit={handleSubmit}>
          <div className="form-group">
            <label htmlFor="forgot-email" className="form-label">Email Akun Terdaftar</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                  <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                </svg>
              </span>
              <input
                type="email"
                id="forgot-email"
                className="form-input"
                placeholder="nama@email.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                autoComplete="email"
              />
            </div>
          </div>

          <button type="submit" className="btn-primary-cta" disabled={loading}>
            <span>{loading ? 'Mengirim Tautan...' : 'Kirim Tautan Pemulihan'}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </button>

          <div className="switch-auth-link" style={{ marginTop: '16px' }}>
            <span>Ingat kata sandi Anda? </span>
            {onSwitchView ? (
              <button
                type="button"
                onClick={() => onSwitchView('login')}
                className="link-accent"
                style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0, fontWeight: 700 }}
              >
                Kembali ke Masuk
              </button>
            ) : (
              <Link href="/login" className="link-accent">Kembali ke Masuk</Link>
            )}
          </div>
        </form>
      )}

      {/* Card Footer */}
      <footer className="card-footer">
        <div className="footer-meta-item">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
          <span>Token Pemulihan Terenkripsi</span>
        </div>
      </footer>
    </div>
  );
}
