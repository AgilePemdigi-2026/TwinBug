<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Tampilkan halaman Tentang Kami (About Us).
     */
    public function index(Request $request): View
    {
        $teamMembers = [
            [
                'name' => 'Abdul Hakim Fatur Rochman',
                'nim' => '187241111',
                'role' => 'Scrum Master / Project Lead',
                'github' => 'https://github.com/AbdulHakimFaturR',
                'username' => 'AbdulHakimFaturR',
                'initials' => 'AH',
            ],
            [
                'name' => 'Ivan Raditya Prisetyo',
                'nim' => '187241072',
                'role' => 'Fullstack Developer & CI/CD',
                'github' => 'https://github.com/Ivan-Raditya',
                'username' => 'Ivan-Raditya',
                'initials' => 'IR',
            ],
            [
                'name' => 'Isa Jahwa Fatimiyah',
                'nim' => '187241029',
                'role' => 'Frontend & Auth Developer',
                'github' => 'https://github.com/isajahwa',
                'username' => 'isajahwa',
                'initials' => 'IJ',
            ],
            [
                'name' => 'Muhammad Fikhlas Hakim Arya Maulana',
                'nim' => '187241034',
                'role' => 'Backend & Security Developer',
                'github' => 'https://github.com/fikhlassm',
                'username' => 'fikhlassm',
                'initials' => 'MF',
            ],
        ];

        return view('about', compact('teamMembers'));
    }
}
