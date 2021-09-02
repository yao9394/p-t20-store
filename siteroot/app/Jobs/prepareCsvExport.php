<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\User;
use App\Services\SalesService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class prepareCsvExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $request;
    private $salesService;
    private $user;
    private $orderAt;

    public function __construct(array $request, SalesService $salesService, User $user, Carbon $orderAt)
    {
        $this->request = $request;
        $this->salesService = $salesService;
        $this->user = $user;
        $this->orderAt = $orderAt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $start = $this->request['start'];
        $end = $this->request['end'];
        $customer = $this->request['customer'];
        $employee = $this->request['employee'];

        $columns = ['Invoice ID', 'date', 'product', 'customer', 'sales person'];

        $salesData = $this->salesService->salesDataQuery($start, $end, $customer, $employee);
        $fName = 'salesData-' . time() . '-' . $this->user->id . '.csv';
        $filename = storage_path('app/' . $fName);
        $handle = fopen($filename, "w");
        fputcsv($handle, $columns);
        $salesData->chunk(100, function($sales) use ($handle) {
            foreach ($sales as $sale) {
                fputcsv($handle, [
                    $sale->invoiceId,
                    $sale->date,
                    $sale->product->name,
                    $sale->customer->full_name,
                    $sale->employee->name
                  ]);
            }
        });
        fclose($handle);
        Storage::disk('s3')->put('csv/'.$fName, file_get_contents($filename));

        $file = new File();
        $file->file_name = $fName;
        $file->order_at = $this->orderAt->toDateTimeString();
        $this->user->files()->save($file);
    }
}
