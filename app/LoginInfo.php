<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginInfo extends Model
{
    protected $fillable = [
        'scene', 'site_id', 'openId', 'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 获取场景值关联的用户
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'openId_id', 'openId');
    }
}
