<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginInfo extends Model
{
    protected $fillable = [
        'scene', 'site_id', 'openId_id', 'status'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 获取场景值关联的用户
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'openId_id', 'openId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 获取场景值关联的站点
     */
    public function site()
    {
        return $this->belongsTo('App\SiteInfo', 'site_id', 'site');
    }
}
