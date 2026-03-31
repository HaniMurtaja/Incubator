<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickAccessController extends Controller
{
    public function loginAsRole(Request $request, $role)
    {
        $roleMap = [
            'admin' => 'Admin',
            'mentor' => 'Mentor',
            'entrepreneur' => 'Entrepreneur',
        ];

        if (! isset($roleMap[$role])) {
            abort(404);
        }

        $user = User::role($roleMap[$role])->first();

        if (! $user) {
            return redirect()->back()->withErrors([
                'role' => __('ui.no_role_user'),
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }
}

