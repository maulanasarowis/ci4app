<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Home | Belajar Codeigniter'
        ];
        return view('pages/home', $data);
    }
    public function about()
    {
        $data = [
            'title' => 'About'
        ];
        return view('pages/about', $data);
    }
    public function contact()
    {
        $data = [
            'title' => 'Contact',
            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'Jl. Kudu',
                    'kota' => 'Pekanbaru'
                ],
                [
                    'tipe' => 'Kantor',
                    'alamat' => 'Jl. Jejek',
                    'kota' => 'Selatpanjang'
                ],
            ]
        ];
        return view('pages/contact', $data);
    }
}
