<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApplicationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->withoutMiddleware(
            \App\Http\Middleware\VerifyCsrfToken::class
        );
    }

    /** @test */
    public function can_submit_a_valid_application()
    {
        $this
            ->postJson('/api/application', [
                'name' => 'Kingsley',
                'age' => 21,
                'location' => 'United Kingdom',
                'email' => 'jameslkingsley@outlook.com',
                'steam' => 'https://steamcommunity.com/id/jlkingsley',
                'available' => true,
                'owns_apex' => true,
                'other_groups' => false,
                'experience' => 'Too much Arma.',
                'about' => 'I am alive.',
                'source' => 'Twitter',
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function cannot_submit_an_invalid_application()
    {
        $this->withExceptionHandling();

        $this
            ->postJson('/api/application', [
                'name' => 'Kingsley',
                'age' => 16,
                'location' => 'United Kingdom',
                'email' => 'jameslkingsley@outlook.com',
                'steam' => 'jlkingsley',
                'available' => true,
                'owns_apex' => true,
                'other_groups' => false,
                'experience' => 'Too much Arma.',
                'about' => 'I am alive.',
                'source' => 'Twitter',
            ])
            ->assertStatus(422);
    }
}
