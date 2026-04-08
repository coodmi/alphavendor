<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGlobalServiceOptionRequest;
use App\Http\Requests\StoreOptionValueRequest;
use App\Http\Requests\UpdateGlobalServiceOptionRequest;
use App\Http\Requests\UpdateOptionValueRequest;
use App\Models\GlobalServiceOption;
use App\Models\OptionValue;
use App\Models\ServiceCategory;
use App\Services\GlobalServiceOptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GlobalServiceOptionController extends Controller
{
    protected GlobalServiceOptionService $optionService;

    public function __construct(GlobalServiceOptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    /**
     * Display a listing of global service options.
     */
    public function index(): View
    {
        $options = GlobalServiceOption::withCount('values')
            ->with(['categoryAssignments.category'])
            ->orderBy('display_order')
            ->paginate(20);

        return view('admin.global-service-options.index', compact('options'));
    }

    /**
     * Show the form for creating a new option.
     */
    public function create(): View
    {
        $categories = ServiceCategory::where('is_active', true)->get();
        
        return view('admin.global-service-options.create', compact('categories'));
    }

    /**
     * Store a newly created option in storage.
     */
    public function store(StoreGlobalServiceOptionRequest $request): RedirectResponse
    {
        try {
            $this->optionService->createOption($request->validated());

            return redirect()
                ->route('admin.global-options.index')
                ->with('success', 'Global service option created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create option: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified option.
     */
    public function edit(GlobalServiceOption $option): View
    {
        $option->load(['values', 'categoryAssignments.category']);
        $categories = ServiceCategory::where('is_active', true)->get();
        
        // Check if option applies to all categories
        $appliesToAll = $option->categoryAssignments()->where('applies_to_all', true)->exists();
        
        // Get assigned category IDs
        $assignedCategories = $option->categoryAssignments()
            ->whereNotNull('category_id')
            ->pluck('category_id')
            ->toArray();

        return view('admin.global-service-options.edit', compact('option', 'categories', 'appliesToAll', 'assignedCategories'));
    }

    /**
     * Update the specified option in storage.
     */
    public function update(UpdateGlobalServiceOptionRequest $request, GlobalServiceOption $option): RedirectResponse
    {
        try {
            $this->optionService->updateOption($option, $request->validated());

            return redirect()
                ->route('admin.global-options.index')
                ->with('success', 'Global service option updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update option: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified option from storage.
     */
    public function destroy(GlobalServiceOption $option): RedirectResponse
    {
        try {
            $this->optionService->deleteOption($option);

            return redirect()
                ->route('admin.global-options.index')
                ->with('success', 'Global service option deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete option: ' . $e->getMessage());
        }
    }

    /**
     * Store a new value for the specified option.
     */
    public function storeValue(GlobalServiceOption $option, StoreOptionValueRequest $request): JsonResponse
    {
        try {
            $valueData = $request->validated();
            
            // If this value is marked as default, unmark any existing default
            if ($valueData['is_default'] ?? false) {
                $option->values()->update(['is_default' => false]);
            }

            $value = $option->values()->create($valueData);

            return response()->json([
                'success' => true,
                'message' => 'Value added successfully.',
                'value' => $value,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add value: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified value.
     */
    public function updateValue(OptionValue $value, UpdateOptionValueRequest $request): JsonResponse
    {
        try {
            $valueData = $request->validated();
            
            // If this value is marked as default, unmark others
            if ($valueData['is_default'] ?? false) {
                $value->option->values()
                    ->where('id', '!=', $value->id)
                    ->update(['is_default' => false]);
            }

            $value->update($valueData);

            return response()->json([
                'success' => true,
                'message' => 'Value updated successfully.',
                'value' => $value->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update value: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified value from storage.
     */
    public function destroyValue(OptionValue $value): JsonResponse
    {
        try {
            $value->delete();

            return response()->json([
                'success' => true,
                'message' => 'Value deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete value: ' . $e->getMessage(),
            ], 500);
        }
    }
}
