<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
// use Illuminate\Support\Facades\App;
use Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'password',
        'theme',
        'username',
        'group_id',
        'parent_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // public $granted;
    // public function __construct(){
    //     $this->granted = App::make('App\Services\GrantedService');
    // }

    // public function permissions(){
    //     $data = $this->hasOne(UsergroupModel::class, 'group_id', 'group_id')->pluck('group_menu_permission')->first();
    //     $array = $this->granted->get_accessible_menu($data);
    //     return $array;
    // }
}
