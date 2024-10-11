<?php

namespace App\Models\Master\Region;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql';
	public $incrementing = false;
	public $timestamps = true;
	protected $hidden = [
		'deleted_at',
	];
    protected $dates = ['deleted_at'];
	protected $table = 'mst_region_city';
	protected $primaryKey = "citid";
    protected $fillable = [
        'citid',
        'provid',
        'citname',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function rel_created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'userid');
    }
}
