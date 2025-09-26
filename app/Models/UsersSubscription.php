<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class UsersSubscription extends Model
{
    use HasFactory;

    protected $table = 'users_subscriptions';
    protected $fillable = ['user_id', 'plan_id', 'no_days', 'mode_payment', 'amount_paid', 'start_date', 'end_date', 'is_subscribe', 'is_expired', 'is_extended', 'is_cancelled', 'is_cancelled', 'remarks'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Change 'user_id' if the column is different
    }
    

    public static function getSubscriptionsList($id){

        $subs = UsersSubscription::where('user_id', $id)
            ->where(function ($query) {
                $query->where('is_subscribe', 1)
                    ->orWhere('is_extended', 1);
            })
            ->where('is_cancelled', '<>', 1)
            ->where('end_date', '>', Carbon::now())
            ->orderBy('start_date', 'desc') // Order by start date in descending order
            ->distinct()
            ->get();

        return $subs;
    }

    public static function getSubscriptions($id){

        $subs = UsersSubscription::where('user_id', $id)
            ->where(function ($query) {
                $query->where('is_subscribe', 1)
                    ->orWhere('is_extended', 1);
            })
            ->where('is_cancelled', '<>', 1)
            ->where('end_date', '>', Carbon::now())
            ->orderBy('start_date', 'desc') // Order by start date in descending order
            ->distinct()
            ->first(); // Get only the latest record

            
        // $subs = UsersSubscription::where('user_id', $id)
        //     ->where(function ($query) {
        //         $query->where('is_subscribe', 1)
        //             ->orWhere('is_extended', 1);
        //     })
        //     ->where('is_cancelled', '<>', 1)
        //     ->where('end_date', '>', Carbon::now())
        //     ->distinct()
        //     ->get();

        return $subs;
    }
    
}
