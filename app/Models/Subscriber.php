<?php

namespace App\Models;

use App\Helpers\Setting;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailingList\CampaignMail;

use Illuminate\Database\Eloquent\Model;
use App\Models\SentCampaign;


use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'email', 'code', 'is_active'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_has_subscribers')->withTimestamps();;
    }

    public function email_with_name()
    {
        if (empty($this->name)) {
            return "{$this->email}";
        } else {
            return "{$this->email} - {$this->name}";
        }
    }

    public function send_campaign(Campaign $campaign)
    {
        Mail::to($this->email)->send(new CampaignMail(Setting::info(), $campaign));
//        return !Mail::failures();
    }

    public static function generate_unique_code()
    {
        $randomString = self::generate_random_string();
        $subscriber = Subscriber::where('code', $randomString)->get();
        while ($subscriber->count()) {
            $randomString = self::generate_random_string();
            $subscriber = Subscriber::where('code', $randomString)->first();
        }

        return $randomString;
    }

    private static function generate_random_string($length = 128) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
