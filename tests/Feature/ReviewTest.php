<?php

use App\Models\User;
use App\Models\Review;

test('user can submit a review', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/reviews', [
        'rating' => 5,
        'comment' => 'This app is outstanding!',
    ]);

    $response->assertRedirect('/reviews');
    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'rating' => 5,
        'comment' => 'This app is outstanding!',
    ]);
});
