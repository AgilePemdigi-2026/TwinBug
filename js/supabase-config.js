/**
 * TwinBug - Supabase Backend Configuration & Client
 * Project: https://supabase.com/dashboard/project/ziezgigbbixahdcnhgnj
 */

const SUPABASE_CONFIG = {
  url: 'https://ziezgigbbixahdcnhgnj.supabase.co',
  anonKey: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InppZXpnaWdiYml4YWhkY25oZ25qIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIzNzgxNTUsImV4cCI6MjEwNzk1NDE1NX0.Ej4v73LnI8OdqHW0g64CzLhNBiy_pc6FeQiC-iObgfg'
};

// Initialize Supabase Client
let supabaseClient = null;

if (window.supabase && typeof window.supabase.createClient === 'function') {
  supabaseClient = window.supabase.createClient(SUPABASE_CONFIG.url, SUPABASE_CONFIG.anonKey);
} else {
  console.warn('Supabase SDK library not loaded via CDN, falling back to REST API.');
}

window.TwinBugSupabase = {
  client: supabaseClient,
  config: SUPABASE_CONFIG,

  /**
   * Register a new user with Supabase Auth
   */
  async register({ email, password, fullName, role }) {
    if (supabaseClient) {
      const { data, error } = await supabaseClient.auth.signUp({
        email: email,
        password: password,
        options: {
          data: {
            full_name: fullName,
            role: role || 'reporter'
          }
        }
      });
      if (error) throw error;
      return data;
    }

    // Direct REST API fallback
    const res = await fetch(`${SUPABASE_CONFIG.url}/auth/v1/signup`, {
      method: 'POST',
      headers: {
        'apikey': SUPABASE_CONFIG.anonKey,
        'Authorization': `Bearer ${SUPABASE_CONFIG.anonKey}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email,
        password,
        data: {
          full_name: fullName,
          role: role || 'reporter'
        }
      })
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.msg || data.message || data.error_description || 'Gagal mendaftar ke Supabase');
    }
    return data;
  },

  /**
   * Log in an existing user with Supabase Auth
   */
  async login({ email, password }) {
    if (supabaseClient) {
      const { data, error } = await supabaseClient.auth.signInWithPassword({
        email: email,
        password: password
      });
      if (error) throw error;
      return data;
    }

    // Direct REST API fallback
    const res = await fetch(`${SUPABASE_CONFIG.url}/auth/v1/token?grant_type=password`, {
      method: 'POST',
      headers: {
        'apikey': SUPABASE_CONFIG.anonKey,
        'Authorization': `Bearer ${SUPABASE_CONFIG.anonKey}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.msg || data.message || data.error_description || 'Gagal masuk ke Supabase');
    }
    return data;
  },

  /**
   * Fetch user profile from public.profiles
   */
  async getProfile(userId) {
    if (supabaseClient) {
      const { data, error } = await supabaseClient
        .from('profiles')
        .select('*')
        .eq('id', userId)
        .single();
      if (!error && data) return data;
    }

    // Direct REST API fallback
    const res = await fetch(`${SUPABASE_CONFIG.url}/rest/v1/profiles?id=eq.${userId}&select=*`, {
      headers: {
        'apikey': SUPABASE_CONFIG.anonKey,
        'Authorization': `Bearer ${SUPABASE_CONFIG.anonKey}`
      }
    });
    if (res.ok) {
      const rows = await res.json();
      if (rows && rows.length > 0) return rows[0];
    }
    return null;
  },

  /**
   * Send password recovery email
   */
  async requestPasswordReset(email) {
    const redirectTo = `${window.location.origin}${window.location.pathname}#reset-password`;
    if (supabaseClient) {
      const { data, error } = await supabaseClient.auth.resetPasswordForEmail(email, {
        redirectTo: redirectTo
      });
      if (error) throw error;
      return data;
    }

    // Direct REST API fallback
    const res = await fetch(`${SUPABASE_CONFIG.url}/auth/v1/recover`, {
      method: 'POST',
      headers: {
        'apikey': SUPABASE_CONFIG.anonKey,
        'Authorization': `Bearer ${SUPABASE_CONFIG.anonKey}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ email, redirect_to: redirectTo })
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.msg || data.message || data.error_description || 'Gagal mengirim email pemulihan');
    }
    return data;
  },

  /**
   * Update password for authenticated or recovery session
   */
  async updatePassword(newPassword) {
    if (supabaseClient) {
      const { data, error } = await supabaseClient.auth.updateUser({
        password: newPassword
      });
      if (error) throw error;
      return data;
    }

    const session = await this.getSession();
    const token = session ? session.access_token : SUPABASE_CONFIG.anonKey;

    const res = await fetch(`${SUPABASE_CONFIG.url}/auth/v1/user`, {
      method: 'PUT',
      headers: {
        'apikey': SUPABASE_CONFIG.anonKey,
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ password: newPassword })
    });
    const data = await res.json();
    if (!res.ok) {
      throw new Error(data.msg || data.message || data.error_description || 'Gagal memperbarui kata sandi');
    }
    return data;
  },

  /**
   * Sign out current user
   */
  async logout() {
    if (supabaseClient) {
      await supabaseClient.auth.signOut();
    }
    localStorage.removeItem('twinbug_session');
  },

  /**
   * Get active session
   */
  async getSession() {
    if (supabaseClient) {
      const { data } = await supabaseClient.auth.getSession();
      if (data && data.session) return data.session;
    }
    const cached = localStorage.getItem('twinbug_session');
    if (cached) {
      try { return JSON.parse(cached); } catch (e) {}
    }
    return null;
  }
};
