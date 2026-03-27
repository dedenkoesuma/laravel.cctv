<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman home/welcome
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Data brands untuk ditampilkan di homepage
        $brands = [
            [
                'name' => 'Dahua',
                'slug' => 'dahua',
                'image' => '/images/brands/dahua.png',
                'description' => 'Leading video surveillance solutions'
            ],
            [
                'name' => 'HIKVISION',
                'slug' => 'hikvision',
                'image' => '/images/brands/hikvision.png',
                'description' => 'World\'s leading surveillance products'
            ],
            [
                'name' => 'HiLook',
                'slug' => 'hilook',
                'image' => '/images/brands/hilook.png',
                'description' => 'Affordable security solutions'
            ],
            [
                'name' => 'UNV',
                'slug' => 'unv',
                'image' => '/images/brands/unv.png',
                'description' => 'IP video surveillance solutions'
            ],
            [
                'name' => 'Hiview',
                'slug' => 'hiview',
                'image' => '/images/brands/hiview.png',
                'description' => 'Professional CCTV systems'
            ],
            [
                'name' => 'Ruijie',
                'slug' => 'ruijie',
                'image' => '/images/brands/ruijie.png',
                'description' => 'Network & surveillance solutions'
            ],
            [
                'name' => 'Foreages',
                'slug' => 'foreages',
                'image' => '/images/brands/foreages.png',
                'description' => 'Advanced security solutions'
            ]
        ];

        return view('welcome', compact('brands'));
    }
}