'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { authService } from '../lib/supabase';
import { useToast } from './Toast';

export default function LoginForm({ onSwitchView }) {
  const router = useRouter();
  const { showToast } = useToast();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [rememberMe, setRememberMe] = useState(true);
  const [loading, setLoading] = useState(false);

  // Quick Demo Autofill
  const handleQuickDemo = (role) => {
    if (role === 'reporter') {
      setEmail('reporter.demo@gmail.com');
      setPassword('TwinBug@Demo2025!');
      showToast('Autofill Berhasil', 'Akun Reporter Demo (QA) telah dimuat', 'info');
    } else {
      setEmail('developer.demo@gmail.com');
      setPassword('TwinBug@Demo2025!');
      showToast('Autofill Berhasil', 'Akun Developer Demo (Dev) telah dimuat', 'info');
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!email || !password) {
      showToast('Form Belum Lengkap', 'Silakan masukkan email dan kata sandi Anda.', 'error');
      return;
    }

    setLoading(true);
    try {
      const data = await authService.login({ email, password });
      showToast('Login Berhasil!', `Selamat datang kembali, ${data.user?.user_metadata?.full_name || email}!`, 'success');
      
      if (onSwitchView) {
        onSwitchView('dashboard');
      } else {
        router.push('/dashboard');
      }
    } catch (err) {
      console.error('Login error:', err);
      let errorMsg = err.message || 'Email atau kata sandi tidak sesuai.';
      if (errorMsg.includes('Invalid login credentials')) {
        errorMsg = 'Email atau kata sandi salah. Silakan periksa kembali atau gunakan tombol akun demo.';
      }
      showToast('Gagal Masuk', errorMsg, 'error');
    } finally {
      setLoading(false);
    }
  };

  return (
    <section id="view-login" className="auth-view active" aria-labelledby="login-heading">
      <div className="auth-card auth-card--login">
        
        {/* Card Header & Branding */}
        <div className="card-header">
          <h2 className="brand-logo-text">TwinBug</h2>
          
          <div className="badge-pill">
            {/* Multimodal Neural Node Icon */}
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="M12 3v3"></path>
              <path d="M12 18v3"></path>
              <path d="m4.93 4.93 2.12 2.12"></path>
              <path d="m16.95 16.95 2.12 2.12"></path>
              <path d="M3 12h3"></path>
              <path d="M18 12h3"></path>
              <path d="m4.93 19.07 2.12-2.12"></path>
              <path d="m16.95 7.05 2.12-2.12"></path>
            </svg>
            <span>Multimodal Bug Intelligence System</span>
          </div>

          <h1 id="login-heading" className="card-title">Masuk ke Akun Anda</h1>
          <p className="card-subtitle">
            Akses sistem deteksi otomatis duplikasi laporan bug berbasis teks &amp; visual
          </p>
        </div>

        {/* Login Form */}
        <form id="login-form" className="auth-form" onSubmit={handleSubmit} autoComplete="on">
          
          {/* Email / Username */}
          <div className="form-group">
            <label htmlFor="login-email" className="form-label">Email atau Username</label>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
              <input 
                type="text" 
                id="login-email" 
                name="email" 
                className="form-input" 
                placeholder="nama@email.com" 
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                autoComplete="username"
              />
            </div>
          </div>

          {/* Password */}
          <div className="form-group">
            <div className="form-label-row">
              <label htmlFor="login-password" className="form-label">Kata Sandi</label>
              {onSwitchView ? (
                <button
                  type="button"
                  onClick={() => onSwitchView('forgot-password')}
                  className="form-link js-goto-forgot"
                  style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}
                >
                  Lupa kata sandi?
                </button>
              ) : (
                <Link href="/forgot-password" className="form-link js-goto-forgot">Lupa kata sandi?</Link>
              )}
            </div>
            <div className="input-wrapper">
              <span className="input-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <input 
                type={showPassword ? 'text' : 'password'} 
                id="login-password" 
                name="password" 
                className="form-input has-toggle" 
                placeholder="••••••••••••" 
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                autoComplete="current-password"
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

          {/* Remember Me Checkbox */}
          <label className="checkbox-row">
            <input 
              type="checkbox" 
              id="login-remember" 
              className="checkbox-input" 
              checked={rememberMe}
              onChange={(e) => setRememberMe(e.target.checked)}
            />
            <span className="checkbox-custom">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
                <path d="M20 6 9 17l-5-5"></path>
              </svg>
            </span>
            <span className="checkbox-label-text">Ingat saya di perangkat ini</span>
          </label>

          {/* Primary Submit Button */}
          <button type="submit" id="btn-login-submit" className="btn-primary-cta" disabled={loading}>
            <span>{loading ? 'Memverifikasi...' : 'Masuk ke Sistem'}</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </button>

          {/* Divider */}
          <div className="divider-container" aria-hidden="true">
            <div className="divider-line"></div>
            <span className="divider-badge">Atau</span>
          </div>

          {/* Demo Quick Access Roles */}
          <div className="demo-access-section">
            <div className="demo-access-title">Demo Role Schnellzugriff</div>
            <div className="demo-buttons-row">
              <button 
                type="button" 
                id="btn-demo-reporter" 
                className="btn-demo-role"
                onClick={() => handleQuickDemo('reporter')}
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                  <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                  <path d="m9 14 2 2 4-4"></path>
                </svg>
                <span>Reporter</span>
              </button>
              <button 
                type="button" 
                id="btn-demo-developer" 
                className="btn-demo-role"
                onClick={() => handleQuickDemo('developer')}
              >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
                  <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                  <path d="m7 10 2 2-2 2"></path>
                  <path d="M13 14h4"></path>
                  <path d="M8 21h8"></path>
                  <path d="M12 17v4"></path>
                </svg>
                <span>Developer</span>
              </button>
            </div>
          </div>

          {/* Footer Switch Link */}
          <div className="card-footer-switch">
            <span>Belum memiliki akun?</span>
            {onSwitchView ? (
              <button
                type="button"
                onClick={() => onSwitchView('register')}
                className="js-goto-register"
                style={{ background: 'none', border: 'none', cursor: 'pointer', padding: 0 }}
              >
                Daftar Sekarang
              </button>
            ) : (
              <Link href="/register" className="js-goto-register">Daftar Sekarang</Link>
            )}
          </div>

        </form>

      </div>

      {/* Micro-credits for Login */}
      <footer className="bottom-microcredits" aria-label="Informasi Keamanan">
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
