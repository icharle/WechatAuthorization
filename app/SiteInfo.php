<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $fillable = [
        'site', 'sitename', 'sitelogo', 'sitedesc', 'openId_id'
    ];
}
