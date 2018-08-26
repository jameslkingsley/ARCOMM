<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Absence;

class AbsenceTest extends TestCase
{
    /** @test */
    public function can_create_an_absence()
    {
        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/absence', [
                'date' => '2018-09-08',
                'reason' => 'Open days suck'
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function can_update_absence()
    {
        $absence = Absence::create([
            'user_id' => $this->user->id,
            'date' => '2018-09-08',
            'reason' => 'Open days suck'
        ]);

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/absence/' . $absence->id, [
                'date' => '2018-09-15',
                'reason' => 'I hate Arma'
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function can_delete_absence()
    {
        $absence = Absence::create([
            'user_id' => $this->user->id,
            'date' => '2018-09-08',
            'reason' => 'Open days suck'
        ]);

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson('/api/absence/' . $absence->id)
            ->assertSuccessful();
    }

    /** @test */
    public function can_get_authenticated_users_absences()
    {
        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/absence')
            ->assertSuccessful();
    }
}
