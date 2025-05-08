<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Company;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $resources = Resource::paginate(10);

        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('resources.create', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Resource creation started', ['request_data' => $request->all()]);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|string|max:255',
                'company_id' => 'required|exists:companies,id',
                'status' => 'boolean',
                'icon' => 'required|string|max:255',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            // Convert status checkbox value
            $validated['status'] = isset($validated['status']) && $validated['status'] == '1';

            $resource = Resource::create($validated);
            \Log::info('Resource created successfully', ['resource' => $resource]);

            return redirect()->route('resources.index')
                ->with('success', __('Resource created successfully'));
        } catch (\Exception $e) {
            \Log::error('Error creating resource', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to create resource: ' . $e->getMessage()]);
        }
    }

    public function edit(Resource $resource)
    {
        $companies = Company::all();
        return view('resources.edit', compact('resource', 'companies'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'status' => 'boolean',
        ]);

        if (auth()->user()->isAdmin()) {
            $validated['company_id'] = auth()->user()->company_id;
        }

        $resource->update($validated);

        return redirect()->route('resources.index')
            ->with('success', __('Resource updated successfully'));
    }

    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()->route('resources.index')
            ->with('success', __('Resource deleted successfully'));
    }
}
