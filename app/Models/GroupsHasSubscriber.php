<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupsHasSubscriber extends Model
{
    public function details()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id');
    }
}
