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
            return view('welcome');
        }
    }


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole(Role::findByName('user'));

        // delete Invitation from new user
        Invitation::where('email', $user->email)->first()->delete();

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function invite_view()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(404);
        } else {
            return view('auth.invite');
        }
    }


    public function invite(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) abort(404);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
            ]);
        $validator->after(function ($validator) use ($request) {
            if (Invitation::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'Es existiert bereits eine Einladung für diese E-Mail-Adresse!');
            }
        });
        if ($validator->fails()) {
            return redirect(route('invite_view'))
                ->withErrors($validator)
                ->withInput();
        }
        do {
            $token = Str::random(60);
        } while (Invitation::where('token', $token)->first());
        Invitation::create([
            'token' => $token,
            'email' => $request->input('email')
        ]);
        $url = URL::temporarySignedRoute(
            'register', now()->addMinutes(300), ['token' => $token]
        );

        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($url));

        return redirect('/admin/invitations');
    }
}
