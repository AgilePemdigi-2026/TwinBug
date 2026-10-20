<?php
// Re-insert test user
// Run this with: php artisan tinker < reinsert.php

// Check current state
$users = DB::table('users')->get();
echo "Users before: " . $users->count() . "\n";

if ($users->count() == 0) {
    // Insert the original user from the database backup
    DB::table('users')->insert([
        ['id' => 1, 'name' => 'Isa Jahwa', 'email' => 'blankon51@gmail.com', 'email_verified_at' => null, 'password' => '$2y$12$3A5WxNv5GyRDC14xIuy8JuT88z12/B8YEGNajc6PQJm8CCvcA7G4i', 'remember_token' => null, 'created_at' => now(), 'updated_at' => now(), 'role' => 'developer'],
    ]);
    echo "Inserted user\n";
}

$count = DB::table('users')->count();
echo "Users after: " . $count . "\n";
?>