<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = SaasPlan::orderBy('sort_order')->get();
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'slug'            => 'required|string|max:100|unique:saas_plans,slug',
            'description'     => 'nullable|string',
            'price_monthly'   => 'required|numeric|min:0',
            'price_yearly'    => 'required|numeric|min:0',
            'max_students'    => 'nullable|integer|min:1',
            'max_instructors' => 'nullable|integer|min:1',
            'max_courses'     => 'nullable|integer|min:1',
            'max_storage_gb'  => 'nullable|integer|min:1',
            'is_featured'     => 'boolean',
            'sort_order'      => 'integer',
        ]);

        $data['features'] = [
            'zoom'       => $request->boolean('feature_zoom'),
            'ai'         => $request->boolean('feature_ai'),
            'store'      => $request->boolean('feature_store'),
            'live_class' => $request->boolean('feature_live_class'),
        ];
        $data['is_active']  = true;
        $data['created_at'] = time();

        SaasPlan::create($data);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan created successfully.');
    }

    public function edit(int $id)
    {
        $plan = SaasPlan::findOrFail($id);
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, int $id)
    {
        $plan = SaasPlan::findOrFail($id);

        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'description'     => 'nullable|string',
            'price_monthly'   => 'required|numeric|min:0',
            'price_yearly'    => 'required|numeric|min:0',
            'max_students'    => 'nullable|integer|min:1',
            'max_instructors' => 'nullable|integer|min:1',
            'max_courses'     => 'nullable|integer|min:1',
            'max_storage_gb'  => 'nullable|integer|min:1',
            'is_featured'     => 'boolean',
            'sort_order'      => 'integer',
        ]);

        $data['features'] = [
            'zoom'       => $request->boolean('feature_zoom'),
            'ai'         => $request->boolean('feature_ai'),
            'store'      => $request->boolean('feature_store'),
            'live_class' => $request->boolean('feature_live_class'),
        ];
        $data['updated_at'] = time();

        $plan->update($data);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan updated.');
    }

    public function pricing()
    {
        $plans = SaasPlan::where('is_active', true)->orderBy('sort_order')->get();
        return view('superadmin.plans.pricing', compact('plans'));
    }

    public function toggleStatus(int $id)
    {
        $plan = SaasPlan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);

        return back()->with('success', 'Plan status toggled.');
    }
}
