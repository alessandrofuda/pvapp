<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // roles (no need roles table neither roles db seed ( --> better performances)
    const ROLE = [
        'admin' => 1,
        'operator' => 2
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected $appends = ['role'];


    /**
     * Send the password reset notification. --> override from Illuminate\Auth\Passwords\CanResetPassword
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getRoleAttribute() : string
    {
        return array_search($this->role_id, self::ROLE);
    }

    public function isAdmin() : bool
    {
        return $this->role_id === self::ROLE['admin'];
    }

    public function isOperator() : bool
    {
        return $this->role_id === self::ROLE['operator'];
    }

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function hasPurchasedLead(Lead $lead) : bool
    {
        // return true;
        dd('..wip...');  // return Transaction::where('lead_id',$lead->id)->where('user_id', $this->id)->get();
    }

}
