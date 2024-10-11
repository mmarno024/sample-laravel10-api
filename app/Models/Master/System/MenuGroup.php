<?php

namespace App\Models\Master\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    public $incrementing = true;
    public $timestamps = true;
    protected $hidden = [
        'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $table = 'mst_system_menu_group';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'groupid',
        'groupname',
        'position',
        'created_by',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public function rel_created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'userid');
    }
}
