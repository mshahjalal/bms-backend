<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use App\Models\Flat;
use App\Models\BillCategory;
use App\Services\BillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BillController extends Controller
{
    public function __construct(protected BillService $billService)
    {
    }

    public function index()
    {
        Gate::authorize('viewAny', Bill::class);
        return response()->json(Bill::with(['flat', 'category'])->get());
    }

    public function store(StoreBillRequest $request)
    {
        Gate::authorize('create', Bill::class);

        $validated = $request->validated();

        Flat::findOrFail($validated['flat_id']);
        BillCategory::findOrFail($validated['category_id']);

        $bill = $this->billService->createBill($validated);

        return response()->json($bill, 201);
    }

    public function show(Bill $bill)
    {
        Gate::authorize('view', $bill);
        return response()->json($bill->load(['flat', 'category']));
    }

    public function update(UpdateBillRequest $request, Bill $bill)
    {
        Gate::authorize('update', $bill);

        $bill = $this->billService->updateBill($bill, $request->validated());

        return response()->json($bill);
    }

    public function destroy(Bill $bill)
    {
        Gate::authorize('delete', $bill);
        $this->billService->deleteBill($bill);
        return response()->json(null, 204);
    }
}
