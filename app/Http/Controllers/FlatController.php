<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFlatRequest;
use App\Http\Requests\UpdateFlatRequest;
use App\Models\Building;
use App\Models\Flat;
use App\Services\FlatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FlatController extends Controller
{
    public function __construct(protected FlatService $flatService)
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', Flat::class);
        return response()->json(Flat::with('building')->get());
    }

    public function store(StoreFlatRequest $request)
    {
        Gate::authorize('create', Flat::class);

        $validated = $request->validated();

        Building::findOrFail($validated['building_id']); 

        $flat = $this->flatService->createFlat($validated);

        return response()->json($flat, 201);
    }

    public function show(Flat $flat)
    {
        Gate::authorize('view', $flat);
        return response()->json($flat->load('building'));
    }

    public function update(UpdateFlatRequest $request, Flat $flat)
    {
        Gate::authorize('update', $flat);

        $flat = $this->flatService->updateFlat($flat, $request->validated());

        return response()->json($flat);
    }

    public function destroy(Flat $flat)
    {
        Gate::authorize('delete', $flat);
        $this->flatService->deleteFlat($flat);
        return response()->json(null, 204);
    }
}
