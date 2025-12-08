<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirewallIpList extends Model
{
    protected $table = 'firewall_ip_lists';

    protected $fillable = [
        'ip_address',
        'type',     // whitelist | blacklist
        'note',
        'created_by',
    ];
}
