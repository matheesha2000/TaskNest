<?php

use App\Models\Subscription;

test('isFree returns true for subscription with price zero', function () {
    $free = Subscription::factory()->make(['price' => 0]);
    $pro = Subscription::factory()->make(['price' => 9.99]);

    expect($free->isFree())->toBeTrue();
    expect($pro->isFree())->toBeFalse();
});
