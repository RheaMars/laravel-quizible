<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Notifications\InviteNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class RegisteredUserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['create', 'store']);
    }


    public function create($token): View
    {
        $invite = Invitation::where('token', $token)->first();
        if ($invite) {
            return view('auth.register',['invite' => $invite]);
        } else {
            session()->flash('error', 'Diese Einladung existiert nicht mehr.');
            return view('auth.register-error');
        }
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // find Invitation from new user
        $invitation = Invitation::where('email', $request->email)->first();
        $roles = $invitation->getRoleNames()->toArray();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole($roles);

        // delete Invitation and Invitation-Roles
        $invitation->delete();

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }


}
