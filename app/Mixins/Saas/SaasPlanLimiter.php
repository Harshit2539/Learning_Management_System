<?php

namespace App\Mixins\Saas;

use App\Models\SaasOrganization;
use App\Models\Webinar;
use App\User;

class SaasPlanLimiter
{
    private SaasOrganization $org;

    public function __construct(SaasOrganization $org)
    {
        $this->org = $org;
    }

    /**
     * Build a limiter for the given user's organisation.
     * Returns null if the user has no SaaS org or no active plan.
     */
    public static function forUser(User $user): ?self
    {
        if (empty($user->organization_id)) {
            return null;
        }

        $org = SaasOrganization::find($user->organization_id);

        return $org ? new self($org) : null;
    }

    /**
     * Returns an error message if the student limit is reached, otherwise null.
     */
    public function checkStudentLimit(): ?string
    {
        $plan = $this->org->getActivePlan();

        if (!$plan || is_null($plan->max_students)) {
            return null;
        }

        // Count users whose organization_id matches this org
        $current = User::where('organization_id', $this->org->id)
            ->where('role_name', 'user')
            ->count();

        if ($current >= $plan->max_students) {
            return "Your plan allows a maximum of {$plan->max_students} students. Please upgrade your plan.";
        }

        return null;
    }

    /**
     * Returns an error message if the instructor limit is reached, otherwise null.
     */
    public function checkInstructorLimit(): ?string
    {
        $plan = $this->org->getActivePlan();

        if (!$plan || is_null($plan->max_instructors)) {
            return null;
        }

        // Count instructors (teachers) in this org, excluding the owner (admin)
        $current = User::where('organization_id', $this->org->id)
            ->where('role_name', 'teacher')
            ->count();

        if ($current >= $plan->max_instructors) {
            return "Your plan allows a maximum of {$plan->max_instructors} instructors. Please upgrade your plan.";
        }

        return null;
    }

    /**
     * Returns an error message if the course limit is reached, otherwise null.
     */
    public function checkCourseLimit(): ?string
    {
        $plan = $this->org->getActivePlan();

        if (!$plan || is_null($plan->max_courses)) {
            return null;
        }

        $current = Webinar::where('creator_id', $this->org->owner_id)->count();

        if ($current >= $plan->max_courses) {
            return "Your plan allows a maximum of {$plan->max_courses} courses. Please upgrade your plan.";
        }

        return null;
    }
}
