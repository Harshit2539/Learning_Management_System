<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $table = 'saas_plans';
    protected $guarded = ['id'];
    protected $dateFormat = 'U';

    protected $casts = [
        'features'       => 'array',
        'price_monthly'  => 'float',
        'price_yearly'   => 'float',
        'is_active'      => 'boolean',
        'is_featured'    => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(SaasOrganizationSubscription::class, 'plan_id');
    }

    public function hasFeature(string $key): bool
    {
        return !empty($this->features[$key]);
    }

    public function getPrice(string $cycle = 'monthly'): float
    {
        return $cycle === 'yearly' ? $this->price_yearly : $this->price_monthly;
    }

    public static function getActivePlans()
    {
        return self::where('is_active', true)->orderBy('sort_order')->get();
    }
}
