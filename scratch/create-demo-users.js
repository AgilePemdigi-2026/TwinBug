// create-demo-users.js
const SUPABASE_URL = 'https://ziezgigbbixahdcnhgnj.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InppZXpnaWdiYml4YWhkY25oZ25qIiwicm9sZSI6ImFub24iLCJpYXQiOjE3OTIzNzgxNTUsImV4cCI6MjEwNzk1NDE1NX0.Ej4v73LnI8OdqHW0g64CzLhNBiy_pc6FeQiC-iObgfg';

const demoUsers = [
  {
    email: 'reporter.demo@gmail.com',
    password: 'TwinBug@Demo2025!',
    full_name: 'Alex Rivera (Demo QA)',
    role: 'reporter'
  },
  {
    email: 'developer.demo@gmail.com',
    password: 'TwinBug@Demo2025!',
    full_name: 'Marcus Vance (Demo Dev)',
    role: 'developer'
  }
];

async function createDemoUsers() {
  for (const u of demoUsers) {
    try {
      const res = await fetch(`${SUPABASE_URL}/auth/v1/signup`, {
        method: 'POST',
        headers: {
          'apikey': SUPABASE_ANON_KEY,
          'Authorization': `Bearer ${SUPABASE_ANON_KEY}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: u.email,
          password: u.password,
          data: {
            full_name: u.full_name,
            role: u.role
          }
        })
      });
      const data = await res.json();
      console.log(`User ${u.email}: status ${res.status}`, data.id ? 'Created successfully' : data);
    } catch (err) {
      console.error(err);
    }
  }
}

createDemoUsers();
