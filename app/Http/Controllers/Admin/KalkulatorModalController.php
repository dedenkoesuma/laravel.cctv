<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class KalkulatorModalController extends Controller
{
    public function index()
    {
        return view('admin.modal.kalkulator_modal');
    }
}