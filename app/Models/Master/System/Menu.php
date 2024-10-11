<?php

namespace App\Models\Master\System;

use App\Models\Master\System\MenuGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    public $incrementing = true;
    public $timestamps = true;
    protected $hidden = [
        'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $table = 'mst_system_menu';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'menuid',
        'groupid',
        'menuname',
        'url',
        'parent',
        'icon',
        'order_no',
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

    public function rel_groupid(): BelongsTo
    {
        return $this->belongsTo(MenuGroup::class, 'groupid', 'groupid');
    }

    public function rel_parent()
    {
        return $this->belongsTo('App\Models\Master\System\Menu', 'parent', 'id');
    }

    public function rel_menu()
    {
        return $this->hasMany('App\Models\Master\System\Menu', 'parent', 'id');
    }
}
