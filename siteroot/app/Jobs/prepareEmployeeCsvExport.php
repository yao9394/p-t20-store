<?php

namespace App\Jobs;

use App\Services\EmployeeService;
use App\Models\User;
use Carbon\Carbon;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class prepareEmployeeCsvExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $employeeService;
    private $user;
    private $orderAt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $request, EmployeeService $employeeService, User $user, Carbon $orderAt)
    {
        $this->request = $request;
        $this->employeeService = $employeeService;
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

        $columns = ['#ranking', 'name', 'sales count'];

        $employeeData = $this->employeeService->employeeSalesQuery($start, $end);
        $fName = 'employeeLadder-' . time() . '-' . $this->user->id . '.csv';
        $filename = storage_path('app/' . $fName);
        $handle = fopen($filename, "w");
        fputcsv($handle, $columns);

        $rank = 1;
        $employeeData->chunk(100, function($employees) use ($handle, $rank) {
            foreach ($employees as $employee) {
                fputcsv($handle, [
                    $rank,
                    $employee->name,
                    $employee->sales_count
                  ]);
                $rank++;
            }
        });
        fclose($handle);

        $file = new File();
        $file->file_name = $fName;
        $file->order_at = $this->orderAt->toDateTimeString();
        $this->user->files()->save($file);
    }
}
