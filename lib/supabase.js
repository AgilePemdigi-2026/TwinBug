import { createClient } from '@supabase/supabase-js';

const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL || 'https://ziezgigbbixahdcnhgnj.supabase.co';
const supabaseAnonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY || 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InppZXpnaWdiYml4YWhkY25oZ25qIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIzNzgxNTUsImV4cCI6MjEwNzk1NDE1NX0.Ej4v73LnI8OdqHW0g64CzLhNBiy_pc6FeQiC-iObgfg';

export const supabase = createClient(supabaseUrl, supabaseAnonKey);

/**
 * Authentication helper functions
 */
export const authService = {
  // Sign up with email, password, full name, and role
  async register({ email, password, fullName, role }) {
    const { data, error } = await supabase.auth.signUp({
      email,
      password,
      options: {
        data: {
          full_name: fullName,
          role: role || 'reporter',
        },
      },
    });
    if (error) throw error;
    return data;
  },

  // Sign in with email and password
  async login({ email, password }) {
    const { data, error } = await supabase.auth.signInWithPassword({
      email,
      password,
    });
    if (error) throw error;
    return data;
  },

  // Sign out
  async logout() {
    const { error } = await supabase.auth.signOut();
    if (error) throw error;
  },

  // Get current active session
  async getSession() {
    const { data, error } = await supabase.auth.getSession();
    if (error) throw error;
    return data.session;
  },

  // Get current user profile from public.profiles table
  async getProfile(userId) {
    const { data, error } = await supabase
      .from('profiles')
      .select('*')
      .eq('id', userId)
      .single();
    if (error) {
      console.warn('Could not fetch profile from public.profiles:', error.message);
      return null;
    }
    return data;
  },

  // Request password reset email
  async requestPasswordReset(email) {
    const origin = typeof window !== 'undefined' ? window.location.origin : '';
    const { data, error } = await supabase.auth.resetPasswordForEmail(email, {
      redirectTo: `${origin}/reset-password`,
    });
    if (error) throw error;
    return data;
  },

  // Update password
  async updatePassword(newPassword) {
    const { data, error } = await supabase.auth.updateUser({
      password: newPassword,
    });
    if (error) throw error;
    return data;
  },
};
