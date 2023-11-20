<?php
namespace Tests\Feature;

use App\Entities\User;
use App\Enums\UserStatusEnum;
use App\Livewire\Login\Login;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses()->group('livewire', 'manual-login', 'auth')->beforeEach(fn () =>
    $this->user = User::factory()->createOne(['user_status_id' => UserStatusEnum::Complete->value, 'password' => Hash::make($this->password = '1234567890123'), 'has_password' => true])
);

test('Login manual successful', function (): void
{
    /** TODO: Try to understand why auth don't work in tests */
    Livewire::test(Login::class)
        ->set('form.email', $this->user->email)
        ->set('form.password', $this->password)
        ->call('submitFormLogin')
        ->assertHasNoErrors();
})->skip('Auth don\'t work in tests (???)');

test('Login manual failed', function (string $email, string $password): void
{
    Livewire::test(Login::class)
        ->set('form.email', $email)
        ->set('form.password', $password)
        ->call('submitFormLogin')
        ->assertSee('Usuário ou senha inválido');
})->with([
    'Invalid password' => [
        fn () => User::factory()->createOne(['password' => Hash::make('1234567890123'), 'has_password' => true])->email,
        '0000000000'
    ],
    'Deleted user and correct password' => [
        fn () => User::factory()->createOne(['deleted' => true, 'password' => Hash::make('1234567890123'), 'has_password' => true])->email,
        '1234567890123'
    ],
    'No has password user' => [
        fn () => User::factory()->createOne(['password' => Hash::make(''), 'has_password' => false])->email,
        '1234567890123'
    ],
    'No has password and deleted user' => [
        fn () => User::factory()->createOne(['password' => Hash::make(''), 'has_password' => false, 'deleted' => true])->email,
        '1234567890123'
    ]
]);
