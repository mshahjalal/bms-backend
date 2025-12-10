<?php

namespace App\Services;

use App\Models\Flat;

class FlatService
{
    public function createFlat(array $data): Flat
    {
        return Flat::create($data);
    }

    public function updateFlat(Flat $flat, array $data): Flat
    {
        $flat->update($data);
        return $flat;
    }

    public function deleteFlat(Flat $flat): void
    {
        $flat->delete();
    }
    
    public function assignRenter(Flat $flat, string $renterId): Flat
    {
        $flat->renter_id = $renterId;
        $flat->save();
        return $flat;
    }
}
