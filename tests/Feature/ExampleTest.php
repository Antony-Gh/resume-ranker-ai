<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // For unauthenticated users (redirect to login)
        $response = $this->get('/');
        $response->assertRedirect('/login');

        // OR for authenticated users
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertOk();
    }
}
