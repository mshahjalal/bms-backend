<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use App\Services\BuildingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BuildingController extends Controller
{
    public function __construct(protected BuildingService $buildingService)
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', Building::class);
        return response()->json(Building::all());
    }

    public function store(StoreBuildingRequest $request)
    {
        Gate::authorize('create', Building::class);

        $building = $this->buildingService->createBuilding($request->validated());

        return response()->json($building, 201);
    }

    public function show(Building $building)
    {
        Gate::authorize('view', $building);
        return response()->json($building);
    }

    public function update(UpdateBuildingRequest $request, Building $building)
    {
        Gate::authorize('update', $building);

        $building = $this->buildingService->updateBuilding($building, $request->validated());

        return response()->json($building);
    }

    public function destroy(Building $building)
    {
        Gate::authorize('delete', $building);
        $this->buildingService->deleteBuilding($building);
        return response()->json(null, 204);
    }
}
