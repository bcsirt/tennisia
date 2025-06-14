<?php

namespace Tests\Feature;

use App\Models\Joueur;
use App\Models\MatchTennis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchTennisTest extends TestCase
{
    use RefreshDatabase;

    public function test_perdant_relationship_returns_losing_player(): void
    {
        $joueur1 = Joueur::factory()->create();
        $joueur2 = Joueur::factory()->create();

        $match = MatchTennis::factory()->create([
            'joueur1_id' => $joueur1->id,
            'joueur2_id' => $joueur2->id,
            'gagnant_id' => $joueur1->id,
        ]);

        $this->assertEquals($joueur2->id, $match->perdant->id);
    }
}
