<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\VarExporter\VarExporter;
use Uasoft\Badaso\Helpers\Firebase\FirebasePublishFile;

class BadasoSetup extends Command
{
    protected $file;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'badaso:setup';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badaso:setup {--force=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Badaso';

    private $force = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->file = app('files');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->force = $this->options()['force'] == 'true' || $this->options()['force'] == null;

        $this->addingBadasoEnv();
        $this->updatePackageJson();
        // $this->updateWebpackMix();
        $this->publishBadasoProvider();
        $this->publishLaravelBackupProvider();
        $this->publishLaravelActivityLogProvider();
        $this->publishLaravelFileManager();
        $this->publishLaravelAnalytics();
        $this->publicFileFirebaseServiceWorker();
        $this->addingBadasoAuthConfig();
        $this->generateSwagger();
    }

    protected function generateSwagger()
    {
        try {
            $this->call('l5-swagger:generate');
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    protected function updatePackageJson()
    {
        $package_json = file_get_contents(base_path('package.json'));
        $decoded_json = json_decode($package_json, true);

        $decoded_json['devDependencies']['axios'] = '^0.27';
        // $decoded_json['devDependencies']['laravel-mix'] = '^6.0.19';
        $decoded_json['devDependencies']['lodash'] = '^4.17.4';
        $decoded_json['devDependencies']['postcss'] = '^8.4.16';

        $decoded_json['dependencies']['copy-files-from-to'] = '^3.2.2';
        $decoded_json['dependencies']['@popperjs/core'] = '^2.11.6';
        $decoded_json['dependencies']['cross-env'] = '^7.0.3';
        $decoded_json['dependencies']['vue'] = '^2.7.10';
        $decoded_json['dependencies']['vue-loader'] = '^17.0.0';
        $decoded_json['dependencies']['vue-template-compiler'] = '^2.7.10';
        $decoded_json['dependencies']['sass'] = '^1.54.5';
        $decoded_json['dependencies']['sass-loader'] = '^13.0.2';
        $decoded_json['dependencies']['resolve-url-loader'] = '^5.0.0';

        $decoded_json['dependencies']['@johmun/vue-tags-input'] = '^2.1.0';
        $decoded_json['dependencies']['@tinymce/tinymce-vue'] = '^3.2.8';
        $decoded_json['dependencies']['body-scroll-lock'] = '^4.0.0-beta.0';
        $decoded_json['dependencies']['chart.js'] = '^3.9.1';
        $decoded_json['dependencies']['firebase'] = '^9.9.3';
        $decoded_json['dependencies']['jspdf'] = '^2.5.1';
        $decoded_json['dependencies']['jspdf-autotable'] = '^3.5.25';
        $decoded_json['dependencies']['luxon'] = '^1.28.0';
        $decoded_json['dependencies']['material-icons'] = '^1.11.10';
        $decoded_json['dependencies']['moment'] = '^2.29.4';
        $decoded_json['dependencies']['prismjs'] = '^1.29.0';
        $decoded_json['dependencies']['tinymce'] = '^6.1.2';
        $decoded_json['dependencies']['uuid'] = '^8.3.2';
        $decoded_json['dependencies']['vue-chartjs'] = '^4.1.1';
        $decoded_json['dependencies']['vue-color'] = '^2.8.1';
        $decoded_json['dependencies']['vue-datetime'] = '^1.0.0-beta.14';
        $decoded_json['dependencies']['vue-draggable-nested-tree'] = '^3.0.0-beta3';
        $decoded_json['dependencies']['vue-gtag'] = '^1.16.1';
        $decoded_json['dependencies']['vue-i18n'] = '^8.27.2';
        $decoded_json['dependencies']['vue-json-excel'] = '^0.3.0';
        $decoded_json['dependencies']['vue-prism-editor'] = '^1.3.0';
        $decoded_json['dependencies']['vue-router'] = '^3.6.4';
        $decoded_json['dependencies']['vue2-editor'] = '^2.10.3';
        $decoded_json['dependencies']['vuedraggable'] = '^2.24.3';
        $decoded_json['dependencies']['vuelidate'] = '^0.7.7';
        $decoded_json['dependencies']['vuesax'] = '^3.12.2';
        $decoded_json['dependencies']['vuex'] = '^3.6.2';
        $decoded_json['dependencies']['vuex-persistedstate'] = '^4.1.0';
        $decoded_json['dependencies']['weekstart'] = '^1.1.0';

        $encoded_json = json_encode($decoded_json, JSON_PRETTY_PRINT);
        file_put_contents(base_path('package.json'), $encoded_json);

        $this->info('package.json updated');
    }

    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && !Str::contains($this->file->get($file), $search);
    }

    // protected function updateWebpackMix()
    // {
    //     // mix
    //     $mix_file = base_path('webpack.mix.js');
    //     $search = 'Badaso';

    //     if ($this->checkExist($mix_file, $search)) {
    //         $data =
    //             <<<'EOT'

    //     // Badaso
    //     mix.js("vendor/badaso/core/src/resources/badaso/app.js", "public/js/badaso.js")
    //         .sass("vendor/badaso/core/src/resources/badaso/assets/scss/style.scss", "public/css/badaso.css")
    //         .vue()
    //     EOT;

    //         $this->file->append($mix_file, $data);
    //     }

    //     $this->info('webpack.mix.js updated');
    // }

    protected function publishBadasoProvider()
    {
        $command_params = ['--tag' => 'Badaso'];
        if ($this->force) {
            $command_params['--force'] = true;
        }

        Artisan::call('vendor:publish', $command_params);

        $this->info('Badaso provider published');
    }

    protected function publishLaravelBackupProvider()
    {
        $command_params = [
            '--provider' => "Spatie\Backup\BackupServiceProvider",
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }

        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel backup provider published');
    }

    protected function publishLaravelActivityLogProvider()
    {
        $command_params = [
            '--provider' => "Spatie\Activitylog\ActivitylogServiceProvider",
            '--tag' => 'config',
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel activity log provider published');
    }

    protected function publishLaravelFileManager()
    {
        $command_params = ['--tag' => 'lfm_public'];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('File Manager provider published');
    }

    protected function publicFileFirebaseServiceWorker()
    {
        FirebasePublishFile::publishNow();
    }

    protected function addingBadasoAuthConfig()
    {
        try {
            $path_config_auth = config_path('auth.php');
            $config_auth = require $path_config_auth;

            $config_auth['providers']['users'] = [
                'driver' => 'eloquent',
                'model' => \Uasoft\Badaso\Models\User::class,
            ];

            $exported_config_auth = VarExporter::export($config_auth);
            $exported_config_auth = <<<PHP
                <?php
                return {$exported_config_auth} ;
                PHP;
            file_put_contents($path_config_auth, $exported_config_auth);
            $this->info('Adding badaso auth config');
        } catch (\Exception $e) {
            $this->error('Failed adding badaso auth config ', $e->getMessage());
        }
    }

    protected function envListUpload()
    {
        return [
            'BADASO_AUTH_TOKEN_LIFETIME' => '',
            'ARCANEDEV_LOGVIEWER_MIDDLEWARE' => '',
            'VITE_BADASO_PLUGINS' => '',
            'VITE_DEFAULT_MENU' => 'general',
            'VITE_BADASO_MENU' => '${VITE_DEFAULT_MENU}',
            'VITE_ADMIN_PANEL_ROUTE_PREFIX' => 'admin',
            'VITE_API_ROUTE_PREFIX' => 'api',
            'VITE_LOG_VIEWER_ROUTE' => '"log-viewer"',
            'VITE_FIREBASE_API_KEY' => '',
            'VITE_FIREBASE_AUTH_DOMAIN' => '',
            'VITE_FIREBASE_PROJECT_ID' => '',
            'VITE_FIREBASE_STORAGE_BUCKET' => '',
            'VITE_FIREBASE_MESSAGE_SEENDER' => '',
            'VITE_FIREBASE_APP_ID' => '',
            'VITE_FIREBASE_MEASUREMENT_ID' => '',
            'VITE_FIREBASE_WEB_PUSH_CERTIFICATES' => '',
            'VITE_FIREBASE_SERVER_KEY' => '',
            'FILESYSTEM_DRIVER' => 'public',
            'AWS_ACCESS_KEY_ID' => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_DEFAULT_REGION' => '',
            'AWS_BUCKET' => '',
            'AWS_URL' => '',
            'GOOGLE_DRIVE_CLIENT_ID' => '',
            'GOOGLE_DRIVE_CLIENT_SECRET' => '',
            'GOOGLE_DRIVE_REFRESH_TOKEN' => '',
            'GOOGLE_DRIVE_FOLDER_ID' => '',
            'DROPBOX_AUTH_TOKEN' => '',
            'BACKUP_TARGET' => '',
            'BACKUP_DISK' => '',
            'VITE_DATE_FORMAT' => '',
            'VITE_DATETIME_FORMAT' => '',
            'VITE_TIME_FORMAT' => '',
            'ANALYTICS_VIEW_ID' => '',
            'VITE_ANALYTICS_TRACKING_ID' => '',
            'VITE_API_DOCUMENTATION_ANNOTATION_ROUTE' => 'api-annotation',
            'VITE_API_DOCUMENTATION_ROUTE' => 'api-docs',
            'BADASO_TABLE_PREFIX' => 'bds_',
            'OCTANE_SERVER' => 'swoole',
            'REDIS_CLIENT' => 'predis',
            'WORKSPACE_PUID' => '1000',
            'WORKSPACE_PGID' => '1000',
            'WWWGROUP' => '1000',
            'WWWUSER' => '1000',
        ];
    }

    protected function addingBadasoEnv()
    {
        try {
            $env_path = base_path('.env');

            $env_file = file_get_contents($env_path);
            $arr_env_file = explode("\n", $env_file);

            $env_will_adding = $this->envListUpload();

            $new_env_adding = [];
            foreach ($env_will_adding as $key_add_env => $val_add_env) {
                $status_adding = true;
                foreach ($arr_env_file as $key_env_file => $val_env_file) {
                    $val_env_file = trim($val_env_file);
                    if (substr($val_env_file, 0, 1) != '#' && $val_env_file != '' && strstr($val_env_file, $key_add_env)) {
                        $status_adding = false;
                        break;
                    }
                }
                if ($status_adding) {
                    $new_env_adding[] = "{$key_add_env}={$val_add_env}";
                }
            }

            foreach ($new_env_adding as $index_env_add => $val_env_add) {
                $arr_env_file[] = $val_env_add;
            }

            $env_file = join("\n", $arr_env_file);
            file_put_contents($env_path, $env_file);

            $this->info('Adding badaso env');
        } catch (\Exception $e) {
            $this->error('Failed adding badaso env ' . $e->getMessage());
        }
    }

    protected function publishLaravelAnalytics()
    {
        $command_params = [
            '--provider' => "Spatie\Analytics\AnalyticsServiceProvider",
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel analytics provider published');
    }
}