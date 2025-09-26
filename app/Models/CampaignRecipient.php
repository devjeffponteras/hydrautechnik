<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignRecipient extends Model
{
    use HasFactory;
    
    protected $table = "campaign_recipients";
    protected $fillable = ['campaign_id', 'subscriber_id', 'group_id'];

}
