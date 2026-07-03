<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SaasOrganization extends Model
{
    protected $table = 'saas_organizations';
    protected $guarded = ['id'];
    protected $dateFormat = 'U';
    public $timestamps = false;

    const STATUS_ACTIVE    = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_TRIAL     = 'trial';
    const STATUS_PENDING   = 'pending';

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(SaasOrganizationSubscription::class, 'organization_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(SaasOrganizationSubscription::class, 'organization_id')
            ->where('status', 'active')
            ->where('expires_at', '>', time())
            ->latest('created_at');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    public function isOnTrial(): bool
    {
        return $this->status === self::STATUS_TRIAL
            && $this->trial_ends_at
            && $this->trial_ends_at > time();
    }

    public function hasActiveAccess(): bool
    {
        if ($this->isOnTrial()) {
            return true;
        }

        return $this->isActive() && $this->activeSubscription !== null;
    }

    public function getActivePlan(): ?SaasPlan
    {
        $sub = $this->activeSubscription;
        return $sub ? $sub->plan : null;
    }

    public static function findBySubdomain(string $subdomain): ?self
    {
        return self::where('subdomain', $subdomain)->first();
    }

    public static function findByDomain(string $domain): ?self
    {
        return self::where('custom_domain', $domain)
            ->orWhere('subdomain', explode('.', $domain)[0] ?? '')
            ->first();
    }
}
