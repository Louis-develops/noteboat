<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'priority', 'position', 'is_completed'])]
class Task extends Model
{
    public function taskList(): BelongsTo
    {
        return $this->belongsTo(TaskList::class);
    }

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'position' => 'integer',
        ];
    }
}