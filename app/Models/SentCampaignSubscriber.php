<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentCampaignSubscriber extends Model
{
    protected $fillable = ['sent_campaign_id', 'group_id', 'subscriber_id', 'mailing_type', 'is_sent'];
}
