<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Activity\Company;
use App\Models\Master\System\RoleAccess;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// class User extends Authenticatable
// class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'userid',
        'email',
        // 'email_verified_at',
        'password',
        'gender',
        'address',
        'compid',
        'roleid',
        'phone',
        'photo',
        'photo_path',
        'created_by',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function username()
    {
        return 'userid';
    }

    // public function getAuthIdentifierName()
    // {
    //     return 'userid';
    // }

    public function rel_compid(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'compid', 'compid');
    }
    public function rel_roleid(): BelongsTo
    {
        return $this->belongsTo(RoleAccess::class, 'roleid', 'roleid');
    }
}
