<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use Exception;
use Storage;

/**
 * configuration is the disk name(s) configured in filesystems config file
 */
class FilesystemHealthCheck extends AbstractHealthCheck {

    public function getType() {
        return 'filesystem';
    }

    public function check() {
        try {
            $files = Storage::disk( $this->getInstanceName() )->files() + Storage::disk( $this->getInstanceName() )->directories();
            //\Log::debug(__METHOD__.':: Got these files for disk ('.$this->getInstanceName() . '): '.print_r($files,true));
            return ( $files !== false && !empty($files));
        } catch( Exception $e ) {
            \Log::error('Exception getting files for '.$this->getInstanceName().': '.$e->getMessage());
            return false;
        }
    }

}