<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FinalBackGroupSettingCommand extends Command
{
    protected $signature = 'command:FinalBackGroupSettingCommand {roll_no}';
    protected $description = 'Description of the command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $roll_no = $this->argument('roll_no');
        // Add your logic here
    }
}
