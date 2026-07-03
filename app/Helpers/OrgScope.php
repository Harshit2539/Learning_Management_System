<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class OrgScope
{
    /**
     * Apply organization scope to a User query via organization_id column.
     * For webinars/courses, scope by creator's organization_id via a subquery.
     */
    public static function users(Builder $query): Builder
    {
        if (app()->has('currentOrganization')) {
            $query->where('organization_id', app('currentOrganization')->id);
        }
        return $query;
    }

    public static function webinars(Builder $query): Builder
    {
        if (app()->has('currentOrganization')) {
            $orgId = app('currentOrganization')->id;
            $query->whereHas('creator', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId);
            });
        }
        return $query;
    }

    public static function sales(Builder $query): Builder
    {
        if (app()->has('currentOrganization')) {
            $orgId = app('currentOrganization')->id;
            $query->whereHas('seller', function ($q) use ($orgId) {
                $q->where('organization_id', $orgId);
            });
        }
        return $query;
    }

    public static function active(): bool
    {
        return app()->has('currentOrganization');
    }
}
