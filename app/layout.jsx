import './globals.css';
import { ToastProvider } from '../components/Toast';

export const metadata = {
  title: 'TwinBug - Multimodal Bug Intelligence System',
  description: 'Sistem deteksi otomatis duplikasi laporan bug berbasis multimodal teks dan visual. Solusi cerdas untuk tim QA dan Developer.',
  keywords: 'TwinBug, duplicate bug detection, multimodal bug detection, bug intelligence, QA, developer',
  icons: {
    icon: "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%234A7C59' stroke-width='2'%3E%3Cpath d='M12 20c-3.3 0-6-2.7-6-6v-3a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v3c0 3.3-2.7 6-6 6'/%3E%3Cpath d='M12 20v-9'/%3E%3C/svg%3E",
  },
};

export default function RootLayout({ children }) {
  return (
    <html lang="id">
      <body>
        {/* Ambient Blurred Orbs Background */}
        <div className="bg-ambient-wrapper" aria-hidden="true">
          <div className="ambient-orb orb-top-left"></div>
          <div className="ambient-orb orb-bottom-right"></div>
          <div className="ambient-orb orb-center-glow"></div>
        </div>

        <ToastProvider>
          <main className="app-container">
            <div className="auth-views-container">
              {children}
            </div>
          </main>
        </ToastProvider>
      </body>
    </html>
  );
}
