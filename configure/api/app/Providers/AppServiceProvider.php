<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validation\CustomValidator;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{

    /**
     * 設定ファイルがある配列
     *
     * @var array
     */
    protected $fileConfigs = [
        'app' => 'app.php',
        'database' => 'database.php',
        'cache' => 'cache.php',
        'session' => 'session.php',
        'view' => 'view.php'
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::resolver(function ($translator, $data, $rules, $messages) {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->fileConfigs as $key => $file) {
            $pathConfig = config_path() . '/' . env('GNAVIENV') . '/' . $file;
            if (File::isFile($pathConfig)) {
                $this->app['config']->set($key, require $pathConfig);
            }
        }
    }
}
