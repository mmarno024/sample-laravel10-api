<?php

namespace App\Models\Etc;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    public $incrementing = true;
    public $timestamps = true;
    protected $hidden = [
        'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $table = 'system_history';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'userid',
        'route',
        'item',
        'activity',
        'tag',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function rel_created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'userid');
    }
    public function rel_userid(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}
