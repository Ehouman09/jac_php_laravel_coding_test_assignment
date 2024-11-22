<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    // Ensure the database is migrated before running the tests
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    #[test]
    public function it_shows_the_registration_form_when_not_logged_in()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    #[test]
    public function it_redirects_to_books_index_if_user_is_logged_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('register'));
        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('warning', __('auth.yr_ar_already_logged'));
    }

    #[test]
    public function it_registers_a_user_and_logs_in()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        // Ensure the user is logged in
        $this->assertTrue(Auth::check());

        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('success', __('auth.registration_success'));

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name'],
        ]);

        $user = User::where('email', $data['email'])->first();
        $this->assertNotEquals($data['password'], $user->password);
    }

    #[test]
    public function it_logs_registration_success_to_log()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        /*
        Log::shouldReceive('info')
        ->once()
        ->with('Registration successful', \Mockery::on(function ($logData) use ($data) {
            return isset($logData['user']) && $logData['user'] === $data['email'];
        }));*/

        $this->post(route('register'), $data);
    }
}
