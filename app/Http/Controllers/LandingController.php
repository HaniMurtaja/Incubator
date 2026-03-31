<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        Log::info('Landing contact submission', $data);

        return redirect()->route('landing')->with('status', 'Thanks for contacting us. We will get back to you soon.');
    }
}

