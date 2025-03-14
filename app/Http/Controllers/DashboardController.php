<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function home()
    {
        return view('pages.guest-home');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function resumeRankings()
    {
        return view('pages.resume-rankings');
    }

    public function subscriptionManagement()
    {
        return view('pages.subscription-management');
    }
}
