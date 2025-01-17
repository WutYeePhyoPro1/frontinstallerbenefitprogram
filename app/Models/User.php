<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'branch_id',
        'phone_no',
        'department_id',
        'employee_id',
        'status'
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

    public function branches(){
        return $this->belongsToMany(Branch::class,"branch_users",'user_uuid','branch_id',"uuid","branch_id");
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id',"branch_id");
    }

    // public function hasRole($rolename){
    //     dd($this->getRoleNames);
    //     return $this->roles()->where('name',$rolename)->exists();
    // }

}
