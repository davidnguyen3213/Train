<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\Eloquent\MonthlySalaryRepository;


class InsertMonthSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insertMonthSalary:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add records every month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $monthlySalaryRepository;
    public function __construct(MonthlySalaryRepository $monthlySalaryRepository)
    {
        parent::__construct();
        $this->monthlySalaryRepository = $monthlySalaryRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->monthlySalaryRepository->insertMonthSalary();
        $this->info('insertMonthSalary:cron Command Run successfully!');
    }
}
