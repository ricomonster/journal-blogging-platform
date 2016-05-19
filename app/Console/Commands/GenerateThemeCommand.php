<?php //-->
namespace Journal\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use File;

class GenerateThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'journal:generate-theme {name : The name of the theme.}
        {--no-files : Basic files will be not generated.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates basic theme file.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    protected $themeFiles = [
        'layout', 'index', 'post', 'tag', 'author'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = trim($this->input->getArgument('name'));

        $noFiles = $this->input->getOption('no-files');

        // set the path of the theme
        $themePath = base_path('themes/' . $name);

        $this->info('Creating the theme in the themes folder...');

        // create the folder
        $this->filesystem->makeDirectory($themePath, 0775, true, true);

        $this->info('Generating theme.json...');

        // get the stub for the theme.json
        $themeStub = $this->getStub('theme');

        // add theme.json
        $this->filesystem->put(
            $themePath . '/theme.json',
            $this->populateStub($name, $themeStub));

        // check if we need to create some basic files
        if ($noFiles) {
            // exit
            // show success message
            $this->info(ucfirst($name) . ' Theme was successfully generated!');
            exit;
        }

        // get the stub
        $stub = $this->populateStub($name, $this->getStub('template'));

        // generate some files
        foreach ($this->themeFiles as $themeFile) {
            // show some message
            $this->info('Generating file '. $name . '/'. $themeFile.'.blade.php...');

            // check if the file is a layout
            if ($themeFile === 'layout') {
                // layout stub
                $layoutStub = $this->populateStub($name, $this->getStub('layout'));


                $this->filesystem->put(
                    $themePath . '/' . $themeFile . '.blade.php',
                    $layoutStub);
                continue;
            }

            $this->filesystem->put(
                $themePath . '/' . $themeFile . '.blade.php',
                $stub);
        }

        // show success message
        $this->info(ucfirst($name) . ' Theme was successfully generated!');
        exit;
    }

    /**
     * Get the stub file based on the desired file.
     *
     * @param  string $file
     * @return string
     */
    protected function getStub($file)
    {
        return $this->filesystem->get($this->getStubPath() . "/{$file}.stub");
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__.'/stubs';
    }

    /**
     * Populates the stub based on the given name of the theme.
     *
     * @param  string $name
     * @param  string $stub
     * @return string
     */
    protected function populateStub($name, $stub)
    {
        // replace the placeholders in the file
        $stub = str_replace('THEME_NAME', ucfirst($name), $stub);
        $stub = str_replace('theme_template', str_slug($name, '_'), $stub);

        return $stub;
    }
}
