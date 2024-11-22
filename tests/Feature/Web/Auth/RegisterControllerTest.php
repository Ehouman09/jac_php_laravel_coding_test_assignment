<?php

namespace Tests\Feature\Web\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class RegisterControllerTest extends TestCase
{
    
    
    /**
     * Set up the test case.
     *
     * Ensure the database is migrated before running the tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    /**
     * Test to ensure the registration form is displayed to users who are not logged in.
     *
     * This test sends a GET request to the registration route and checks that:
     * - The HTTP response status is 200 (OK)
     * - The 'auth.register' view is returned
     */
     #[Test]
    public function it_shows_the_registration_form_when_not_logged_in()
    {
        // Register the user
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test to ensure that a logged in user is redirected to the books index route if they attempt to access the registration route.
     *
     * This test sends a GET request to the registration route while logged in and checks that:
     * - The HTTP response status is a redirect (302)
     * - The user is redirected to the books index route
     * - The user is flashed a warning message
     */
     #[Test]
    public function it_redirects_to_books_index_if_user_is_logged_in()
    {
        // Create a logged in user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Attempt to access the registration route
        $response = $this->get(route('register'));
        $response->assertRedirect(route('books.index'));
        $response->assertSessionHas('warning', __('auth.yr_ar_already_logged'));
    }

    /**
     * Test to ensure that a user can register and is logged in.
     *
     * This test sends a POST request to the registration route with valid data and checks that:
     * - The user is logged in
     * - The user is redirected to the books index route
     * - The user is flashed a success message
     * - The user is created in the database
     * - The user's password is not equal to the one passed in the request
     */
     #[Test]
    public function it_registers_a_user_and_logs_in()
    {
        // Register the user
        $data = [
            'name' => 'Jean Yves',
            'email' => 'test@example.com',
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

    /**
     * Test to ensure that a successful registration logs a message to the log.
     *
     * This test sends a POST request to the registration route with valid data and checks that:
     * - The log contains the message 'Registration successful' with the user's email address
     */
    #[Test]
    public function it_logs_registration_success_to_log()
    {
        // Completely mock the Log facade
        Log::shouldReceive('info')
            ->once()
            ->with('Registration successful', Mockery::on(function ($logData) {
                return isset($logData['user']);
            }));

        // Optionally, set up to ignore any error logs
        Log::shouldReceive('error')
            ->zeroOrMoreTimes();

        $data = [
            'name' => 'Jean Yves',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertStatus(302); // Assuming successful registration redirects
    }

    /**
     * Clean up Mockery and call the parent tearDown method.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
