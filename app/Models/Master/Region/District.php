<?php

namespace App\Models\Master\Region;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
    public $incrementing = false;
    public $timestamps = true;
    protected $hidden = [
        'deleted_at',
    ];
    protected $dates = ['deleted_at'];
    protected $table = 'mst_region_district';
    protected $primaryKey = "disid";
    protected $fillable = [
        'disid',
        'citid',
        'disname',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function rel_created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'userid');
    }
}
