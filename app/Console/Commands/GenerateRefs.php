<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Missions\Mission;
use Kingsley\References\Models\Reference;

class GenerateRefs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:refs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the mission model refs.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Mission::all() as $mission) {
            if (is_null($mission->ref)) {
                $mission->reference()->save(
                    new Reference([
                        'hash' => $mission->makeReferenceHash()
                    ])
                );
            }
        }
    }
}
