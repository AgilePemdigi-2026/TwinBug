// test-auth.js
const SUPABASE_URL = 'https://ziezgigbbixahdcnhgnj.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InppZXpnaWdiYml4YWhkY25oZ25qIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIzNzgxNTUsImV4cCI6MjEwNzk1NDE1NX0.Ej4v73LnI8OdqHW0g64CzLhNBiy_pc6FeQiC-iObgfg';

async function testSignupAndLogin() {
  const testEmail = `twinbug.tester.${Date.now()}@gmail.com`;
  const testPassword = 'TwinBug@Pass2025!';

  console.log('Testing SignUp for:', testEmail);
  const signUpRes = await fetch(`${SUPABASE_URL}/auth/v1/signup`, {
    method: 'POST',
    headers: {
      'apikey': SUPABASE_ANON_KEY,
      'Authorization': `Bearer ${SUPABASE_ANON_KEY}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email: testEmail,
      password: testPassword,
      data: {
        full_name: 'Test Tester',
        role: 'reporter'
      }
    })
  });

  const signUpData = await signUpRes.json();
  console.log('SignUp status:', signUpRes.status);
  console.log('SignUp result:', JSON.stringify(signUpData, null, 2));

  console.log('\nTesting SignIn for:', testEmail);
  const signInRes = await fetch(`${SUPABASE_URL}/auth/v1/token?grant_type=password`, {
    method: 'POST',
    headers: {
      'apikey': SUPABASE_ANON_KEY,
      'Authorization': `Bearer ${SUPABASE_ANON_KEY}`,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      email: testEmail,
      password: testPassword
    })
  });

  const signInData = await signInRes.json();
  console.log('SignIn status:', signInRes.status);
  console.log('SignIn result:', JSON.stringify(signInData, null, 2));
}

testSignupAndLogin();
