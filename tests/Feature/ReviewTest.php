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

test('review submission fails with invalid data', function () {
    $user = User::factory()->create();

    // Rating out of bounds
    $response = $this->actingAs($user)->post('/reviews', [
        'rating' => 6,
        'comment' => 'Great app!',
    ]);
    $response->assertSessionHasErrors(['rating']);

    // Empty comment
    $response = $this->actingAs($user)->post('/reviews', [
        'rating' => 3,
        'comment' => '',
    ]);
    $response->assertSessionHasErrors(['comment']);
});

test('user can delete their own review', function () {
    $user = User::factory()->create();
    $review = Review::create([
        'user_id' => $user->id,
        'rating' => 4,
        'comment' => 'Really solid app',
    ]);

    $this->assertDatabaseHas('reviews', ['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete('/reviews/' . $review->id);

    $response->assertRedirect('/reviews');
    $response->assertSessionHas('success', 'Your review has been removed.');
    $this->assertDatabaseMissing('reviews', ['user_id' => $user->id]);
});
