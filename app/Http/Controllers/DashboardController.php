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
        return view('pages.main-home');
    }

    public function myHome()
    {
        return redirect()->route('realHome');
    }

    public function realHome()
    {
        return view('pages.guest-home', [
            'showResetModal' => session('show_password_reset', false),
            'resetToken' => session('reset_token', ''), // Provide default empty string
            'resetEmail' => session('reset_email', ''), // Provide default empty string
            'action' => session('action', 'regular'),
        ]);
    }


    public function mainHome()
    {
        return view('pages.main-home');
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
