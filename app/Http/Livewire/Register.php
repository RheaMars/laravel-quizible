<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Invitation;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use JeffGreco13\FilamentBreezy\FilamentBreezy;
use Filament\Forms\Concerns\InteractsWithForms;
use JeffGreco13\FilamentBreezy\Http\Livewire\Auth\Register as FilamentBreezyRegister;

class Register extends FilamentBreezyRegister implements HasForms {
    use InteractsWithForms;

    public User $user;

    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation  = '';
    public $token;

    public function mount(): void {
        $this->token = request()->token;
        $this->form->fill();
    }

    protected function getFormSchema(): array {
        return [
            TextInput::make( 'name' )
            ->label( __( 'filament-breezy::default.fields.name' ) )
            ->required(),
            TextInput::make( 'email' )
            ->label( __( 'filament-breezy::default.fields.email' ) )
            ->email()
            ->disabled()
            ->unique( table: config( 'filament-breezy.user_model' ) ),
            TextInput::make( 'password' )
            ->label( __( 'filament-breezy::default.fields.password' ) )
            ->required()
            ->password()
            ->rules( app( FilamentBreezy::class )->getPasswordRules() )
            ->dehydrateStateUsing( fn ( $state ) => Hash::make( $state ) ),
            TextInput::make( 'password_confirm' )
            ->label( __( 'filament-breezy::default.fields.password_confirm' ) )
            ->required()
            ->password()
            ->same( 'password' )
            ->dehydrated( false ),
        ];
    }

    public function register() {

        // find Invitation
        $invitation = Invitation::where( 'email', $this->email )->first();
        $roles = $invitation->getRoleNames()->toArray();
        $user = User::create( $this->form->getState() );
        $user->assignRole( $roles );

        // delete Invitation
        $invitation->delete();

        Filament::auth()->login( user: $user, remember:true );
        return redirect()->intended( Filament::getUrl( 'filament.pages.dashboard' ) );
    }

    public function render(): View {
        $invite = Invitation::where( 'token', $this->token )->first();
        if ( $invite ) {
            $this->email = $invite->email;
            $view = view( 'filament-breezy::register' );
        } else {
            $view = view( 'filament-breezy::register-error' );

        }

        $view->layout( 'filament::components.layouts.base', [
            'title' => __( 'filament-breezy::default.registration.title' ),
        ] );
        return $view;

    }
}
