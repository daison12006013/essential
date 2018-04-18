<?php

namespace Daison\Essential\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Initialize extends Command
{
    /**
     * The template sample.
     *
     * @var array
     */
    protected $template = __DIR__.'/config.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init {--template=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize essential\'s config.';

    /**
     * Essential's config.
     *
     * @var array
     */
    protected $configValues = [];

    /**
     * The current working path.
     *
     * @var string
     */
    protected $currentWorkingPath;

    /**
     * Create a new patcher instance.
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
        $this->currentWorkingPath = getcwd();

        if (file_exists($this->currentWorkingPath.'/essential.json')) {
            $this->error('essential.json already exists!');

            return;
        }

        if ($this->option('template')) {
            $this->template = $this->option('template');
        }

        file_put_contents($this->currentWorkingPath.'/essential.json', file_get_contents($this->template));

        $this->info('[essential.json] successfully generated.');
    }
}
