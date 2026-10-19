// test-supabase.js
const SUPABASE_URL = 'https://ziezgigbbixahdcnhgnj.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InppZXpnaWdiYml4YWhkY25oZ25qIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIzNzgxNTUsImV4cCI6MjEwNzk1NDE1NX0.Ej4v73LnI8OdqHW0g64CzLhNBiy_pc6FeQiC-iObgfg';

async function testConnection() {
  try {
    const res = await fetch(`${SUPABASE_URL}/auth/v1/settings`, {
      headers: {
        'apikey': SUPABASE_ANON_KEY,
        'Authorization': `Bearer ${SUPABASE_ANON_KEY}`
      }
    });
    const data = await res.json();
    console.log('Auth settings response status:', res.status);
    console.log('Data:', JSON.stringify(data, null, 2));
  } catch (err) {
    console.error('Fetch error:', err);
  }
}

testConnection();
