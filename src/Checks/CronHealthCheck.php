<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use File;

/**
 * Checks that an appropriate cron is set up in a given file. Note: this doesn't
 * confirm that crond is running, or that it's looking at this file, or that
 * it's valid cron format; it just checks to see that the given file has the
 * given content.
 *
 * Config format:
 *
 * 'checks' => [
 *   'cron' => [
 *     '/path/to/cronfile' => [
 *       'regexp to match',
 *       ...
 *     ]
 *   ],
 *   ...
 * ]
 */
class CronHealthCheck implements HealthCheckInterface {

    protected $cronFiles;

    public function __construct( $cronFiles ) {
        $this->cronFiles = $cronFiles;
    }

    public function getName() {
        return 'cron';
    }

    public function check() {
        $success = true;
        foreach( $this->cronFiles as $filename => $patterns ) {
            if( !$this->checkCron($filename, $patterns) ) {
                $success = false;
            }
        }
        return $success;
    }

    protected function checkCron($filename, $patterns) {
        try {
            $contents = File::get($filename);
            if( is_array($patterns) ) {
                foreach( $patterns as $pattern ) {
                    if( !$this->checkCronPattern($contents,$pattern) ) {
                        return false;
                    }
                }
                return true;
            } else {
                return $this->checkCronPattern($contents,$patterns);
            }
        } catch( Exception $e ) {
            return false;
        }
    }

    protected function checkCronPattern($contents, $pattern) {
        return false !== strpos($contents, $pattern);
    }
}