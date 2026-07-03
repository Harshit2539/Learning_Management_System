<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToOrganization
{
    protected static function bootBelongsToOrganization(): void
    {
        // Auto-scope all queries to the current organization
        static::addGlobalScope('organization', function (Builder $query) {
            if (app()->has('currentOrganization')) {
                $query->where(
                    (new static)->getTable() . '.organization_id',
                    app('currentOrganization')->id
                );
            }
        });

        // Auto-fill organization_id on create
        static::creating(function ($model) {
            if (app()->has('currentOrganization') && empty($model->organization_id)) {
                $model->organization_id = app('currentOrganization')->id;
            }
        });
    }

    public function scopeForOrganization(Builder $query, int $organizationId): Builder
    {
        return $query->withoutGlobalScope('organization')
            ->where($this->getTable() . '.organization_id', $organizationId);
    }
}
