<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authuser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class Merchant extends Authuser implements JWTSubject
{
    use Notifiable;

    protected $table = 'merchants';

    protected $fillable = [
        'id','name','password','consignor','photo', 'is_limit_brand', 'brand_ids', 'point',
        'last_login', 'system', 'version','last_ip','alias','qq','phone','sex','is_insider',
        'disabled','rank', 'type', 'admin_id', 'open_shop', 'open_id', 'qrcode'
    ];

    protected $casts = [
        'is_insider'        => 'boolean',
        'is_limit_brand'    => 'boolean',
        'open_shop'         => 'boolean',
        'consignor'         => 'json',
        'brand_ids'         => 'json',
        'qrcode'         => 'json',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
