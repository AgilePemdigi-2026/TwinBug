'use client';

import React, { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { authService } from '../lib/supabase';
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
    <section id="view-dashboard" className="auth-view active" aria-labelledby="dashboard-heading">
      <div className="auth-card auth-card--login" style={{ maxWidth: '520px', textAlign: 'center' }}>
        
        <div className="card-header">
          <h2 className="brand-logo-text">TwinBug</h2>
          <div className="badge-pill">
            <span className="badge-dot" aria-hidden="true"></span>
            <span>Sesi Supabase Aktif</span>
          </div>
          <h1 id="dashboard-heading" className="card-title">Selamat Datang!</h1>
          <p className="card-subtitle">
            Anda telah berhasil masuk ke sistem TwinBug.
          </p>
        </div>

        <div style={{
          background: 'var(--color-input-bg)',
          borderRadius: '16px',
          padding: '20px',
          marginBottom: '20px',
          textAlign: 'left',
          display: 'flex',
          flexDirection: 'column',
          gap: '10px',
        }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '8px' }}>
            <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase' }}>Pengguna</span>
            <span style={{ fontWeight: 600, color: 'var(--color-text-title)' }}>
              {loading ? 'Memuat...' : displayName}
            </span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '8px' }}>
            <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase' }}>Email</span>
            <span style={{ fontSize: '13px', color: 'var(--color-text-body)' }}>
              {loading ? 'Memuat...' : displayEmail}
            </span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid var(--color-divider)', paddingBottom: '8px' }}>
            <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase' }}>Role Akses</span>
            <span style={{
              fontSize: '12px',
              fontWeight: 700,
              padding: '3px 10px',
              borderRadius: '9999px',
              background: displayRole === 'developer' ? 'rgba(112, 92, 48, 0.15)' : 'rgba(74, 124, 89, 0.15)',
              color: displayRole === 'developer' ? 'var(--color-text-ochre)' : 'var(--color-primary)',
              textTransform: 'uppercase',
            }}>
              {displayRole}
            </span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
            <span style={{ fontSize: '12px', fontWeight: 700, color: 'var(--color-text-label)', textTransform: 'uppercase' }}>Status Backend</span>
            <span style={{ fontSize: '12px', fontWeight: 600, color: '#007C00', display: 'flex', alignItems: 'center', gap: '4px' }}>
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              Supabase public.profiles
            </span>
          </div>
        </div>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '10px', width: '100%' }}>
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

      </div>

      <footer className="bottom-microcredits">
        <span className="credit-item">© 2025 TwinBug Engine</span>
        <span className="credit-dot">•</span>
        <span className="credit-item">Supabase Auth Integrated</span>
      </footer>
    </section>
  );
}
