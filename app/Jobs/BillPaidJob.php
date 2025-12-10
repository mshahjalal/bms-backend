<?php

namespace App\Jobs;

use App\Mail\BillPaidMail;
use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BillPaidJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Bill $bill)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->bill->load(['flat.renter']);

        if ($this->bill->flat && $this->bill->flat->renter) {
            Mail::to($this->bill->flat->renter->email)->send(new BillPaidMail($this->bill));
        }
    }
}
