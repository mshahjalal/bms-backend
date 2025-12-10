<?php

namespace App\Services;

use App\Models\Building;

class BuildingService
{
    public function createBuilding(array $data): Building
    {
        return Building::create($data);
    }

    public function updateBuilding(Building $building, array $data): Building
    {
        $building->update($data);
        return $building;
    }

    public function deleteBuilding(Building $building): void
    {
        $building->delete();
    }
}
