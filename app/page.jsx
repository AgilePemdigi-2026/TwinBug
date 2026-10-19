'use client';

import React, { useState, useEffect } from 'react';
import LoginForm from '../components/LoginForm';
import RegisterForm from '../components/RegisterForm';
import ForgotPasswordForm from '../components/ForgotPasswordForm';
import ResetPasswordForm from '../components/ResetPasswordForm';
import DashboardView from '../components/DashboardView';
import { authService, supabase } from '../lib/supabase';

export default function Home() {
  const [view, setView] = useState('login'); // 'login' | 'register' | 'forgot-password' | 'reset-password' | 'dashboard'

  // Sync with URL Hash (#login, #register, etc.) and check existing session
  useEffect(() => {
    const handleHashChange = () => {
      const hash = window.location.hash.replace('#', '');
      if (['login', 'register', 'forgot-password', 'reset-password', 'dashboard'].includes(hash)) {
        setView(hash);
      }
    };

    // Initial check
    if (window.location.hash) {
      handleHashChange();
    }

    // Check for auth recovery token in URL
    if (window.location.hash.includes('type=recovery') || window.location.hash.includes('access_token')) {
      setView('reset-password');
    }

    // Check if user already logged in
    authService.getSession().then((session) => {
      if (session && session.user && !window.location.hash.includes('reset-password')) {
        // If user is already authenticated and visits root without specific hash
        if (!window.location.hash || window.location.hash === '#dashboard') {
          setView('dashboard');
        }
      }
    }).catch(() => {});

    window.addEventListener('hashchange', handleHashChange);
    return () => window.removeEventListener('hashchange', handleHashChange);
  }, []);

  const switchView = (newView) => {
    setView(newView);
    window.location.hash = newView;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  return (
    <>
      {view === 'login' && <LoginForm onSwitchView={switchView} />}
      {view === 'register' && <RegisterForm onSwitchView={switchView} />}
      {view === 'forgot-password' && <ForgotPasswordForm onSwitchView={switchView} />}
      {view === 'reset-password' && <ResetPasswordForm onSwitchView={switchView} />}
      {view === 'dashboard' && <DashboardView onSwitchView={switchView} />}
    </>
  );
}
