<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

class TaskUser extends Pivot
{
    public $incrementing = true;

    protected $keyType = 'int';

    protected $primaryKey = 'id';

    protected $fillable = [
        'task_id',
        'user_id',
        'status',
        'started_at',
        'rejected_at',
        'number_of_rejections',
        'reject_reason',
        'completed_at',
        'report_text',
        'report_file_id',
        'reported_at'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'started_at' => 'date',
        'rejected_at' => 'date',
        'completed_at' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'report_file_id');
    }
}
