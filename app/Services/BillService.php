<?php

namespace App\Services;

use App\Jobs\BillCreatedJob;
use App\Jobs\BillPaidJob;
use App\Models\Bill;

class BillService
{
    public function createBill(array $data): Bill
    {
        $bill = Bill::create($data);
        
        BillCreatedJob::dispatch($bill);
        
        return $bill;
    }

    public function updateBill(Bill $bill, array $data): Bill
    {
        $oldStatus = $bill->status;
        $bill->update($data);

        if ($oldStatus !== 'paid' && $bill->status === 'paid') {
             BillPaidJob::dispatch($bill);
        }
        
        return $bill;
    }

    public function deleteBill(Bill $bill): void
    {
        $bill->delete();
    }
}
