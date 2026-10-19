'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { authService, supabase } from '../lib/supabase';
import { useToast } from './Toast';

export default function DashboardView({ onSwitchView }) {
  const router = useRouter();
  const { showToast } = useToast();

  const [user, setUser] = useState(null);
  const [profile, setProfile] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function loadSession() {
      try {
        const session = await authService.getSession();
        if (session && session.user) {
          setUser(session.user);
          const prof = await authService.getProfile(session.user.id);
          setProfile(prof);
        }
      } catch (err) {
        console.error('Session load error:', err);
      } finally {
        setLoading(false);
      }
    }
    loadSession();
  }, []);

  const handleLogout = async () => {
    try {
      await authService.logout();
      showToast('Sesi Berakhir', 'Anda telah berhasil keluar dari akun.', 'info');
      if (onSwitchView) {
        onSwitchView('login');
      } else {
        router.push('/login');
      }
    } catch (err) {
      console.error('Logout error:', err);
      showToast('Gagal Keluar', err.message, 'error');
    }
  };

  const displayName = profile?.full_name || user?.user_metadata?.full_name || 'Pengguna TwinBug';
  const displayEmail = user?.email || profile?.email || 'email@twinbug.com';
  const displayRole = profile?.role || user?.user_metadata?.role || 'reporter';

  return (
    <div className="auth-card auth-card--login" style={{ maxWidth: '540px', textAlign: 'center' }}>
      <div className="card-header">
        <h2 className="brand-logo-text">TwinBug</h2>
        <div className="badge-pill">
          <span className="badge-dot" aria-hidden="true"></span>
          <span>Sesi Supabase Aktif</span>
        </div>
        <h1 className="card-title">Selamat Datang!</h1>
        <p className="card-subtitle">
          Anda telah berhasil masuk ke sistem intelijen deteksi bug multimodal.
        </p>
      </div>

      <div style={{
        background: 'var(--color-input-bg)',
        borderRadius: '16px',
        padding: '20px',
        marginBottom: '24px',
        textAlign: 'left',
        display: 'flex',
        flexDirection: 'column',
        gap: '12px',
        border: '1px solid var(--color-divider)',
      }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '10px' }}>
          <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            Nama Pengguna
          </span>
          <span style={{ fontWeight: 600, color: 'var(--color-text-title)' }}>
            {loading ? 'Memuat...' : displayName}
          </span>
        </div>

        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '10px' }}>
          <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            Email
          </span>
          <span style={{ fontSize: '13px', color: 'var(--color-text-body)' }}>
            {loading ? 'Memuat...' : displayEmail}
          </span>
        </div>

        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '10px' }}>
          <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            Role Akses
          </span>
          <span style={{
            fontSize: '12px',
            fontWeight: 700,
            padding: '4px 12px',
            borderRadius: '9999px',
            background: displayRole === 'developer' ? 'rgba(112, 92, 48, 0.15)' : 'rgba(74, 124, 89, 0.15)',
            color: displayRole === 'developer' ? 'var(--color-text-ochre)' : 'var(--color-primary)',
            textTransform: 'uppercase',
            letterSpacing: '0.05em',
          }}>
            {displayRole}
          </span>
        </div>

        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
            Status Backend
          </span>
          <span style={{ fontSize: '12px', fontWeight: 600, color: '#007C00', display: 'flex', alignItems: 'center', gap: '6px' }}>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            Supabase PostgreSQL Terhubung
          </span>
        </div>
      </div>

      <div style={{ display: 'flex', flexDirection: 'column', gap: '12px', width: '100%' }}>
        <button
          type="button"
          onClick={handleLogout}
          className="btn-primary-cta"
          style={{
            background: '#FAF6F0',
            color: '#2E3230',
            border: '1.5px solid var(--color-divider)',
            boxShadow: 'none',
          }}
        >
          <span>Keluar dari Akun (Logout)</span>
        </button>
      </div>

      <footer className="card-footer" style={{ marginTop: '24px' }}>
        <div className="footer-meta-item">
          <span>© 2025 TwinBug Engine</span>
        </div>
        <div className="footer-dot-separator" aria-hidden="true">•</div>
        <div className="footer-meta-item">
          <span>Supabase Auth Integrated</span>
        </div>
      </footer>
    </div>
  );
}
