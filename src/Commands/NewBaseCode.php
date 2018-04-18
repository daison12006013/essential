<?php

namespace Daison\Essential\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class NewBaseCode extends Command
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new {project_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new base code.';

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

        $this->filesystem = new Filesystem();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->currentWorkingPath = getcwd();
        $projectName = $this->argument('project_name');

        // check if folder exists by using the inputted project name
        if (file_exists($projectName)) {
            $this->error(sprintf('Folder [%s] exists.', $projectName));

            return;
        }

        // build the config
        $configContent = strtr(file_get_contents($this->currentWorkingPath.'/essential.json'), [
            '{PROJECT_NAME}' => $projectName,
        ]);
        $this->configValues = json_decode($configContent, true);

        // we need to post-inject the 'replace' keys inside the config itself using strtr
        $this->configValues = json_decode(
            strtr(
                json_encode($this->configValues),
                $this->parseVariables($this->configValues['replace'])
            ),
            true
        );

        $this->runScripts('before');
        $this->replacer();
        $this->runScripts('after');
    }

    /**
     * Run scripts based on the key.
     *
     * @return void
     */
    protected function runScripts($key)
    {
        if (! isset($this->configValues['scripts'][$key])) {
            return;
        }

        foreach ($this->configValues['scripts'][$key] as $cmd) {
            $process = new Process($cmd);
            $process->setTimeout(
                isset($this->configValues['per_script_timeout'])
                    ? $this->configValues['per_script_timeout']
                    : 3600
            );
            $process->start();

            foreach ($process as $type => $data) {
                $this->output->write($data);
                // if ($process::OUT === $type) {
                //     $this->output->write($data);
                // } else { // $process::ERR === $type
                //     $this->output->write($data);
                // }
            }
        }
    }

    /**
     * Replace values of config.
     *
     * @return void
     */
    protected function replacer()
    {
        $sourcePath = $this->currentWorkingPath.'/'.$this->configValues['template_path'];
        $targetPath = $this->currentWorkingPath.'/'.$this->configValues['build_path'];

        // 1.) copy the whole TEMPLATE folder inside .tmp folder
        $this->filesystem->copyDirectory($sourcePath, $targetPath);

        // 2.) iterate all files and find the keys to replace
        $replaces = $this->parseVariables($this->configValues['replace']);

        foreach ($files = $this->filesystem->allFiles($targetPath, $hidden = true) as $file) {
            $path = $file->getRealPath();

            if (! $file->isWritable()) {
                $this->error(sprintf('File [%s] is not writable', $path));

                continue;
            }

            file_put_contents(
                $path,
                strtr($contents = $file->getContents(), $replaces)
            );

            $this->comment(sprintf('Replacing values to [%s]', $path));
        }
    }

    /**
     * [parseVariables description]
     *
     * @param  array $vars
     * @return array
     */
    public function parseVariables(array $vars)
    {
        $ret = [];

        foreach ($vars as $key => $val) {
            $ret[sprintf('{%s}', $key)] = $val;
        }

        return $ret;
    }
}
