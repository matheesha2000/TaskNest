<?php

use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

test('scopes filter tasks correctly by status', function () {
    $user = User::factory()->create();

    $pendingTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);
    $inProgressTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'in_progress',
    ]);
    $completedTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed',
    ]);

    expect(Task::pending()->count())->toBe(1)
        ->and(Task::pending()->first()->id)->toBe($pendingTask->id);

    expect(Task::inProgress()->count())->toBe(1)
        ->and(Task::inProgress()->first()->id)->toBe($inProgressTask->id);

    expect(Task::completed()->count())->toBe(1)
        ->and(Task::completed()->first()->id)->toBe($completedTask->id);
});

test('scopeOverdue filters tasks correctly', function () {
    $user = User::factory()->create();

    // Overdue task (past due date, not completed)
    $overdueTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->subDay(),
    ]);

    // Not overdue (future due date)
    $futureTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDay(),
    ]);

    // Not overdue (completed, even if past due date)
    $completedOverdueTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed',
        'due_date' => Carbon::now()->subDay(),
    ]);

    // Not overdue (no due date)
    $noDueDateTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => null,
    ]);

    $overdueTasks = Task::overdue()->get();

    expect($overdueTasks->count())->toBe(1);
    expect($overdueTasks->first()->id)->toBe($overdueTask->id);
});

test('status checker helpers return correct boolean values', function () {
    $user = User::factory()->create();

    $pending = Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
    $inProgress = Task::factory()->create(['user_id' => $user->id, 'status' => 'in_progress']);
    $completed = Task::factory()->create(['user_id' => $user->id, 'status' => 'completed']);

    expect($pending->isPending())->toBeTrue()
        ->and($pending->isInProgress())->toBeFalse()
        ->and($pending->isCompleted())->toBeFalse();

    expect($inProgress->isPending())->toBeFalse()
        ->and($inProgress->isInProgress())->toBeTrue()
        ->and($inProgress->isCompleted())->toBeFalse();

    expect($completed->isPending())->toBeFalse()
        ->and($completed->isInProgress())->toBeFalse()
        ->and($completed->isCompleted())->toBeTrue();
});

test('isOverdue returns true for uncompleted tasks with past due dates', function () {
    $user = User::factory()->create();

    $task1 = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->subHour(),
    ]);

    $task2 = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'in_progress',
        'due_date' => Carbon::now()->subDay(),
    ]);

    expect($task1->isOverdue())->toBeTrue();
    expect($task2->isOverdue())->toBeTrue();
});

test('isOverdue returns false for completed or future/null due date tasks', function () {
    $user = User::factory()->create();

    $completedTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'completed',
        'due_date' => Carbon::now()->subDay(),
    ]);

    $futureTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => Carbon::now()->addDay(),
    ]);

    $noDueDateTask = Task::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
        'due_date' => null,
    ]);

    expect($completedTask->isOverdue())->toBeFalse();
    expect($futureTask->isOverdue())->toBeFalse();
    expect($noDueDateTask->isOverdue())->toBeFalse();
});

test('statusBadgeClass returns appropriate CSS classes', function () {
    $user = User::factory()->create();

    $pending = Task::factory()->make(['status' => 'pending']);
    $inProgress = Task::factory()->make(['status' => 'in_progress']);
    $completed = Task::factory()->make(['status' => 'completed']);
    $unknown = Task::factory()->make(['status' => 'unknown']);

    expect($pending->statusBadgeClass())->toContain('bg-amber-50');
    expect($inProgress->statusBadgeClass())->toContain('bg-blue-50');
    expect($completed->statusBadgeClass())->toContain('bg-emerald-50');
    expect($unknown->statusBadgeClass())->toContain('bg-slate-50');
});

test('priorityBadgeClass returns appropriate CSS classes', function () {
    $high = Task::factory()->make(['priority' => 'high']);
    $medium = Task::factory()->make(['priority' => 'medium']);
    $low = Task::factory()->make(['priority' => 'low']);
    $unknown = Task::factory()->make(['priority' => 'unknown']);

    expect($high->priorityBadgeClass())->toContain('bg-rose-50');
    expect($medium->priorityBadgeClass())->toContain('bg-orange-50');
    expect($low->priorityBadgeClass())->toContain('bg-slate-50');
    expect($unknown->priorityBadgeClass())->toContain('bg-slate-50');
});
