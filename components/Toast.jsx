'use client';

import React, { createContext, useContext, useState, useCallback } from 'react';

const ToastContext = createContext({
  showToast: (title, desc, type) => {},
});

export function ToastProvider({ children }) {
  const [toast, setToast] = useState({
    visible: false,
    title: '',
    desc: '',
    type: 'success', // 'success' | 'error' | 'info'
  });

  const showToast = useCallback((title, desc = '', type = 'success') => {
    setToast({ visible: true, title, desc, type });
    setTimeout(() => {
      setToast((prev) => ({ ...prev, visible: false }));
    }, 4000);
  }, []);

  return (
    <ToastContext.Provider value={{ showToast }}>
      {children}
      <div
        className={`toast-notification ${toast.visible ? 'show' : ''}`}
        role="alert"
        aria-live="polite"
      >
        <span className="toast-icon">
          {toast.type === 'error' ? (
            <svg viewBox="0 0 24 24" fill="none" stroke="#D9534F" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="15" y1="9" x2="9" y2="15"></line>
              <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
          ) : toast.type === 'info' ? (
            <svg viewBox="0 0 24 24" fill="none" stroke="#31708F" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
          ) : (
            <svg viewBox="0 0 24 24" fill="none" stroke="#4A7C59" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
          )}
        </span>
        <div>
          <div className="toast-title">{toast.title}</div>
          {toast.desc && <div className="toast-desc">{toast.desc}</div>}
        </div>
      </div>
    </ToastContext.Provider>
  );
}

export const useToast = () => useContext(ToastContext);
