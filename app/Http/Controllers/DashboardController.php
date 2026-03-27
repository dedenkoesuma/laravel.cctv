<?php

namespace App\Http\Controllers;

use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Test data dulu
        $products = collect([
            [
                'id' => 1,
                'name' => 'Laptop ASUS ROG',
                'description' => 'Laptop gaming',
                'price' => 15000000,
                'stock' => 5,
                'category' => 'laptop',
                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800',
                'badge' => 'new'
            ],
            [
                'id' => 2,
                'name' => 'Mouse Gaming',
                'description' => 'Mouse RGB',
                'price' => 750000,
                'stock' => 15,
                'category' => 'accessories',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800',
                'badge' => 'sale'
            ]
        ]);
        
        return view('dashboard', compact('products'));
    }
}