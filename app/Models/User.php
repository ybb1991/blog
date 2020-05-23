<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authuser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class User extends Authuser implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name', 'email','phone', 'department_id', 'role_id', 'password','supplier_id','storage_id','avatar', 'ip', 'login_count', 'is_modify_pwd', 'is_admin','status','is_salesman', 'is_own'
    ];

    /**
     * 获取会储存到 jwt 声明中的标识
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     *返回包含要添加到 jwt 声明中的自定义键值对数组
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
