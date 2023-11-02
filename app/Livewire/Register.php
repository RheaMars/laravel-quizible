<?php

namespace App\Livewire;

use Carbon\Carbon;
use Filament\Forms\Form;
use App\Models\Invitation;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\SimplePage;
use Filament\Actions\ActionGroup;
use Illuminate\Auth\SessionGuard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Support\Htmlable;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

/**
* @property Form $form
*/

class Register extends SimplePage {
    use InteractsWithFormActions;
    use WithRateLimiting;

    /**
    * @var view-string
    */
    protected static string $view = 'filament-panels::pages.auth.register';

    /**
    * @var array<string, mixed> | null
    */
    public ?array $data = [];
    public $token;
    public $invitation;

    protected string $userModel;

    public function mount(): void {
        if ( Filament::auth()->check() ) {
            redirect()->intended( Filament::getUrl() );
        }
        $this->token = request()->token;
        $this->invitation = Invitation::where( 'token', '=', $this->token )->first();
        if ( !$this->invitation ) {
            dd( 'kein token' );
        } else {
            $this->form->fill();
        }

    }

    public function register(): ?RegistrationResponse {
        try {
            $this->rateLimit( 2 );
        } catch ( TooManyRequestsException $exception ) {
            Notification::make()
            ->title( __( 'filament-panels::pages/auth/register.notifications.throttled.title', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil( $exception->secondsUntilAvailable / 60 ),
            ] ) )
            ->body( array_key_exists( 'body', __( 'filament-panels::pages/auth/register.notifications.throttled' ) ?: [] ) ? __( 'filament-panels::pages/auth/register.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => ceil( $exception->secondsUntilAvailable / 60 ),
            ] ) : null )
            ->danger()
            ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->getUserModel()::create( $data );

        $user->email_verified_at = Carbon::now();
        $user->save();

        // assign roles to new user
        $roles = $this->invitation->getRoleNames()->toArray();
        $user->assignRole( $roles );

        // delete Invitation
        $this->invitation->delete();

        app()->bind(
            \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
            \Filament\Listeners\Auth\SendEmailVerificationNotification::class,
        );
        event( new Registered( $user ) );

        Filament::auth()->login( $user );

        session()->regenerate();

        return app( RegistrationResponse::class );
    }

    public function form( Form $form ): Form {
        return $form;
    }

    /**
    * @return array<int | string, string | Form>
    */
    protected function getForms(): array {
        return [
            'form' => $this->form(
                $this->makeForm()
                ->schema( [
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ] )
                ->statePath( 'data' ),
            ),
        ];
    }

    protected function getNameFormComponent(): Component {
        return TextInput::make( 'name' )
        ->label( __( 'filament-panels::pages/auth/register.form.name.label' ) )
        ->required()
        ->maxLength( 255 )
        ->autofocus();
    }

    protected function getEmailFormComponent(): Component {
        return TextInput::make( 'email' )
        ->label( __( 'filament-panels::pages/auth/register.form.email.label' ) )
        ->default( $this->invitation->email )
        ->readOnly();
    }

    protected function getPasswordFormComponent(): Component {
        return TextInput::make( 'password' )
        ->label( __( 'filament-panels::pages/auth/register.form.password.label' ) )
        ->password()
        ->required()
        ->rule( Password::default() )
        ->dehydrateStateUsing( fn ( $state ) => Hash::make( $state ) )
        ->same( 'passwordConfirmation' )
        ->validationAttribute( __( 'filament-panels::pages/auth/register.form.password.validation_attribute' ) );
    }

    protected function getPasswordConfirmationFormComponent(): Component {
        return TextInput::make( 'passwordConfirmation' )
        ->label( __( 'filament-panels::pages/auth/register.form.password_confirmation.label' ) )
        ->password()
        ->required()
        ->dehydrated( false );
    }

    public function loginAction(): Action {
        return Action::make( 'login' )
        ->link()
        ->label( __( 'filament-panels::pages/auth/register.actions.login.label' ) )
        ->url( filament()->getLoginUrl() );
    }

    protected function getUserModel(): string {
        if ( isset( $this->userModel ) ) {
            return $this->userModel;
        }

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        /** @var EloquentUserProvider $provider */
        $provider = $authGuard->getProvider();

        return $this->userModel = $provider->getModel();
    }

    public function getTitle(): string | Htmlable {
        return __( 'filament-panels::pages/auth/register.title' );
    }

    public function getHeading(): string | Htmlable {
        return __( 'filament-panels::pages/auth/register.heading' );
    }

    /**
    * @return array<Action | ActionGroup>
    */
    protected function getFormActions(): array {
        return [
            $this->getRegisterFormAction(),
        ];
    }

    public function getRegisterFormAction(): Action {
        return Action::make( 'register' )
        ->label( __( 'filament-panels::pages/auth/register.form.actions.register.label' ) )
        ->submit( 'register' );
    }

    protected function hasFullWidthFormActions(): bool {
        return true;
    }
}
