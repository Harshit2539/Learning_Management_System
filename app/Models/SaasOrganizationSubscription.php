<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasOrganizationSubscription extends Model
{
    protected $table = 'saas_organization_subscriptions';
    protected $guarded = ['id'];
    protected $dateFormat = 'U';
    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(SaasOrganization::class, 'organization_id');
    }

    public function plan()
    {
        return $this->belongsTo(SaasPlan::class, 'plan_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at > time();
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= time();
    }

    public function daysRemaining(): int
    {
        if ($this->isExpired()) return 0;
        return (int) ceil(($this->expires_at - time()) / 86400);
    }
}
