<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Livewire\Admin\CashClosing\GenerateDailyClosingsService;
class GenerateDailyClosings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashclosing:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app(GenerateDailyClosingsService::class)->generate();
    }
}
