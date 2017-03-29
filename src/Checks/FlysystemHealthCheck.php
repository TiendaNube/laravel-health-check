<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use Closure;
use Exception;
use League\Flysystem\FileSystem;

// supported adapters/drivers
use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Sftp\SftpAdapter;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\Rackspace\RackspaceAdapter;

/*
 * When using Flysystem directly, this check makes sure
 * each configured connection is working.
 */
class FlysystemHealthCheck extends AbstractHealthCheck {

    protected $flysystem;

    public function configure($config ) {
        $this->flysystem = $this->createFlysystem( $config );
    }

    protected function createFlysystem( $config ) {
        if (is_array($config)) {
            if (array_key_exists('driver',$config)) {
                $driver = array_pull($config,'driver');
            } else if (array_key_exists('adapter',$config)) {
                $driver = array_pull($config,'adapter');
            } else {
                // misconfigured
                throw new Exception('Must specify the Flystem adapter in the configuration, but configured keys are ['.implode(",", array_keys($config)). ']');
            }
        } else if (is_string($config)) {
            // using a config from Laravel's filesystem
            $fsConfig = \Config::get('filesystems.disks.' . $config);
            $driver = array_pull($fsConfig,'driver');
            $config = $fsConfig;
        }

        \Log::debug(__METHOD__.':: instantiating a Flysystem for '.$driver);
        $adapter = $this->getAdapterForDriver($driver, $config);

        return new Filesystem ( $adapter );
    }

    public function getType() {
        return 'flysystem';
    }

    public function check() {
        \Log::debug(__METHOD__.'()');
        try {
            $files = $this->flysystem->listContents();
            return ( $files !== false && !empty($files));
        } catch( Exception $e ) {
            \Log::error(__METHOD__.':: Exception getting files: '.$e->getMessage()) ;
            return false;
        }
    }

    /*
     * maps the short driver name to the full class for Flysystem Adapter,
     * and does adapter-specific things to instantiate the filesystem
     */
    public function getAdapterForDriver($driver, $config) {
        $adapterClass = '';
        switch($driver) {
            case 'ftp':
                return new FtpAdapter($config);
            case 'sftp':
                return new SftpAdapter($config);
            case 'local':
                return new LocalAdapter($config);
            case 'rackspace':
                $client = new \OpenCloud\Rackspace($config['endpoint'],
                    [ 'username' => $config['username'],
                      'apiKey' => $config['key'] ]
                );
                $store = $client->objectStoreService('cloudFiles',$config['region']);
                $container = $store->getContainer($config['container']);
                return new RackspaceAdapter($container);
            default:
                throw new Exception('Driver not supported: '.$driver);
        }

        $reflection = new \ReflectionClass($adapterClass);
        $adapter = $reflection->newInstance( $config );
    }

}