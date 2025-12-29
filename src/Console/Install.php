<?php
namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Install extends Command
{
    protected $signature   = 'prolancee:dynamic-crud-ajax:install {--force}';
    protected $description = 'Install the PROLANCEE DYNAMIC CRUD Ajax package';

    public function handle(): void
    {
        // COLORS
        $yellow = "\e[33m";
        $blue   = "\e[34m";
        $green  = "\e[32m";
        $cyan   = "\e[36m";
        $reset  = "\e[0m";

        $this->info("Installing PROLANCEE DYNAMIC CRUD Ajax...\n");

        // HEADER
        $this->info("{$cyan}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━{$reset}");
        $this->info("           {$yellow}PROLANCEE — INSTALLATION{$reset}");
        $this->info("{$cyan}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━{$reset}\n");

        // -------------------------------------------------------------
        // STEP 1: Publish Config
        // -------------------------------------------------------------
        $this->info('Checking configuration file...');

        $configPath1 = config_path('prolancee/dynamic.crud.ajax.php');
        $configPath2 = config_path('prolancee/support.php');

        $missingConfig1 = ! File::exists($configPath1);
        $missingConfig2 = ! File::exists($configPath2);

        if ($this->option('force')) {

            $this->warn("Force mode enabled — overwriting existing config files...");

            $status1 = $this->callSilent('vendor:publish', [
                '--tag'   => 'prolancee:dynamic-crud-ajax:config',
                '--force' => true,
            ]);

            $status2 = $this->callSilent('vendor:publish', [
                '--tag'   => 'prolancee:support:config',
                '--force' => true,
            ]);

            if ($status1 === 0 && $status2 === 0) {
                $this->info("{$green}✓{$reset} Config files overwritten.\n");
            } else {
                $this->error("✗ Failed to overwrite config files.\n");
            }

        } elseif ($missingConfig1 || $missingConfig2) {

            $this->info("Publishing missing configuration files...");

            $status1 = $this->callSilent('vendor:publish', [
                '--tag'   => 'prolancee:dynamic-crud-ajax:config',
                '--force' => false,
            ]);

            $status2 = $this->callSilent('vendor:publish', [
                '--tag'   => 'prolancee:support:config',
                '--force' => false,
            ]);

            if ($status1 === 0 && $status2 === 0) {
                $this->info("{$green}✓{$reset} Missing config files installed.\n");
            } else {
                $this->error("✗ Failed to install missing config files.\n");
            }

        } else {
            $this->warn("⚠ All config files already exist — skipping publish.\n");
        }

        // -------------------------------------------------------------
        // STEP 2: Publish Application Files (via Support Commands)
        // -------------------------------------------------------------
        $this->info("Checking application files...");

        $modelsFiles = [
            app_path('Models/Prolancee/Modeler.php'),
            app_path('Models/Prolancee/Classes/User.php'),
        ];

        $missingModels = array_filter($modelsFiles, fn($file) => ! File::exists($file));

        $notifierFile    = app_path('Notifier/Prolancee.php');
        $missingNotifier = ! File::exists($notifierFile);

        if ($this->option('force')) {

            $this->warn("Force mode enabled — overwriting application files...");

            $this->callSilent('prolancee:support:models', [
                '--force' => true,
            ]);

            $this->callSilent('prolancee:support:notifier', [
                '--force' => true,
            ]);

            $this->info("{$green}✓{$reset} Application files overwritten.\n");

        } else {
            if (! empty($missingModels)) {
                $this->info("Installing missing model files...");
                $this->callSilent('prolancee:support:models');
                $this->info("{$green}✓{$reset} Models installed.");
            }

            if ($missingNotifier) {
                $this->info("Installing missing notifier...");
                $this->callSilent('prolancee:support:notifier');
                $this->info("{$green}✓{$reset} Notifier installed.");
            }

            if (empty($missingModels) && ! $missingNotifier) {
                $this->warn("⚠ All application files already exist — skipping publish.\n");
            } else {
                $this->info("\n{$green}✓{$reset} Missing application files installed successfully.\n");
            }
        }

        // -------------------------------------------------------------
        // STEP 4: Database & Redis Encryption
        // -------------------------------------------------------------
        $this->info('Checking Database & Redis encryption files...');

        $dbEncryptFiles = [
            storage_path('app/prolancee/rdbms.json'),
            storage_path('app/prolancee/redis.json'),
        ];

        $missingDBEncrypt = array_filter(
            $dbEncryptFiles,
            fn($file) => ! File::exists($file)
        );

        if ($this->option('force')) {

            $this->warn('Force mode enabled — regenerating Database & Redis encryption files...');

            $this->callSilent('prolancee:db:encryption', [
                '--force' => true,
            ]);

            $this->info("✓ Database & Redis encryption files regenerated.\n");

        } else {

            if (! empty($missingDBEncrypt)) {

                $this->info('Missing encryption files detected. Generating...');
                $this->callSilent('prolancee:db:encryption');
                $this->info("✓ Encryption files generated.\n");

            } else {
                $this->warn("⚠ Encryption files already exist — skipping.\n");
            }
        }

        // -------------------------------------------------------------
        // STEP 5: Clear & Rebuild Cache
        // -------------------------------------------------------------
        $this->info("Clearing caches...");
        $this->callSilent('optimize:clear');
        $this->info("✓ All caches cleared.");

        $this->info("Rebuilding cache...");

        $this->callSilent('config:cache');
        $this->callSilent('view:cache');

        try {
            $this->callSilent('route:cache');
            $this->info("✓ Route cache built.");
        } catch (Throwable $e) {
            $this->warn("⚠ Route cache skipped (closures detected).");
        }

        $this->info("✓ Cache rebuilt successfully.\n");

        // -------------------------------------------------------------
        // SUCCESS MESSAGE
        // -------------------------------------------------------------
        $this->info("{$cyan}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━{$reset}");
        $this->info(" {$green}PROLANCEE DYNAMIC CRUD Ajax Installed Successfully!{$reset}");
        $this->info("{$cyan}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━{$reset}\n");

        // COMMUNITY MESSAGE
        $this->info("This package is part of the {$yellow}PROLANCEE Ecosystem{$reset}.");
        $this->info("Prolancee.com is a modern freelancing marketplace.\n");

        $this->info("{$yellow}Join our community or create your free account:{$reset}");
        $this->info("➡  {$blue}https://panel.prolancee.com/signup{$reset}\n");

        $this->info("Documentation:");
        $this->info("➡  {$blue}https://flux.prolancee.com/docs/laravel/dynamic-crud-ajax/2.0.0{$reset}\n");
    }
}
