<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Company;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $items = Item::paginate(10);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('items.create', compact('companies'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Item creation started', ['request_data' => $request->all()]);
            
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

            $item = Item::create($validated);
            \Log::info('Item created successfully', ['item' => $item]);

            return redirect()->route('items.index')
                ->with('success', __('Item created successfully'));
        } catch (\Exception $e) {
            \Log::error('Error creating item', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors(['error' => 'Failed to create item: ' . $e->getMessage()]);
        }
    }

    public function edit(Item $item)
    {
        $companies = Company::all();
        return view('items.edit', compact('item', 'companies'));
    }

    public function update(Request $request, Item $item)
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

        $item->update($validated);

        return redirect()->route('items.index')
            ->with('success', __('Item updated successfully'));
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', __('Item deleted successfully'));
    }
}
