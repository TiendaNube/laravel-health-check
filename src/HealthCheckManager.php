<?php namespace NpmWeb\LaravelHealthCheck;

use Illuminate\Support\Manager;

use NpmWeb\LaravelHealthCheck\Checks\DatabaseHealthCheck;
use NpmWeb\LaravelHealthCheck\Checks\FilesystemHealthCheck;
use NpmWeb\LaravelHealthCheck\Checks\FlysystemHealthCheck;
use NpmWeb\LaravelHealthCheck\Checks\FrameworkHealthCheck;
use NpmWeb\LaravelHealthCheck\Checks\MailHealthCheck;
use NpmWeb\LaravelHealthCheck\Checks\WebServiceHealthCheck;

class HealthCheckManager extends Manager {

    static $packageName = 'laravel-health-check';
    private $config;
    private $checks = null;

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->config = config( self::$packageName . '.checks');
    }

    /**
     * instantiates each check defined in the config file
     *
     * @return array of HealthCheckInterface instances
     */
    public function configuredChecks() {
        if ($this->checks === null) {
            $this->checks = [];
            foreach( $this->config as $driver => $checkConfig ) {
                // check if multiple or just one
                if (is_array($checkConfig)) {
                    foreach( $checkConfig as $key => $config ) {
                        $instance = $this->createInstance( $driver, $config );
                        $instance->setInstanceName(is_string($key)?$key:$config);
                        $this->checks[] = $instance;
                    }
                } else {
                    $instance = $this->createInstance( $driver, $checkConfig );
                    $this->checks[] = $instance;
                }
            }
        }
        return $this->checks;
    }

    /**
     * Create a new instance of the driver
     *
     * @param  string  $driver
     * @param  mixed   $config
     * @return mixed
     */
    public function createInstance($driver, $config = false)
    {
        // use createDriver() because driver() only creates on instance
        $reference = $this->createDriver($driver);

        // any other setup needed
        if ($config) {
            $reference->configure($config);
        }

        return $reference;
    }

    /**
     * Create an instance of the database driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createDatabaseDriver()
    {
        return new DatabaseHealthCheck;
    }

    /**
     * Create an instance of the filesystem driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createFilesystemDriver()
    {
        return new FilesystemHealthCheck;
    }

    /**
     * Create an instance of the flysystem driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createFlysystemDriver()
    {
        return new FlysystemHealthCheck;
    }

    /**
     * Create an instance of the framework driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createFrameworkDriver()
    {
        return new FrameworkHealthCheck;
    }

    /**
     * Create an instance of the mail queue driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createMailDriver()
    {
        return new MailHealthCheck;
    }

    /**
     * Create an instance of the web service driver.
     *
     * @return \NpmWeb\LaravelHealthCheck\Checks\HealthCheckInterface
     */
    public function createWebserviceDriver()
    {
        return new WebServiceHealthCheck;
    }


    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        $driver = $this->config[self::$packageName.'::driver'];
        return $driver;
    }

    /**
     * Set the default authentication driver name.
     *
     * @param  string  $name
     * @return void
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']->set(self::$packageName.'::driver', $name);
    }

}
