<?php
namespace App\Http\Controllers\Regent;
use App\Http\Controllers\Controller;
class ProfileController extends Controller
{
    public function index() { return view('regent.profile'); }
}
