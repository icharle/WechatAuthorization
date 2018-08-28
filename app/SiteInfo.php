<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $fillable = [
        'sitename', 'sitelogo', 'sitedesc', 'openId_id'
    ];
}
