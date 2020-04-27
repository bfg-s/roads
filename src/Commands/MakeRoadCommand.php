<?php

namespace Lar\Roads\Commands;

use App\Models\UserDeposit;
use Illuminate\Console\Command;
use Lar\Layout\CfgFile;
use Road;

class MakeRoadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:road {name} 
    {--dir= : Created directory (Default: /routes/)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make road file';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = \Str::snake($this->argument('name'));
        $name = str_replace(['-', '__'], '_', strtolower($name));

        $base_dir = $this->option('dir');
        $base_dir = $base_dir ? trim($base_dir, "/") : 'routes';
        $dir = base_path($base_dir);

        if (!is_dir($dir)) {

            mkdir($dir, 0777, true);
        }

        $file = "/" . trim($dir, "/") . "/{$name}.php";

        $file_data = <<<FILE
<?php

use Lar\Roads\Roads;

Road::asx('{$name}')->group(function (Roads \$roads) {

    \$roads->get('/', function () {
        return 'The same logic as with the usual routes in Laravel, only through the facade of the "Road" and "Roads".';
    })->name('home');
});
FILE;

        if (is_file($file)) {

            if (!$this->confirm("File [{$file}] exists, rewrite him?", false)) {

                $this->info('By!');

                return ;
            }
        }

        file_put_contents($file, $file_data);

        $this->info("File [{$file}] created!");

        CfgFile::open(config_path('roads.php'))->write("{$base_dir}/{$name}", 'web');

        $this->info("Config file [".config_path('roads.php')."] updated!");
    }
}
