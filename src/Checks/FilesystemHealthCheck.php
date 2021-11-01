<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

            return ( $files !== false && !empty($files));
        } catch( Exception $e ) {
            Log::error('Exception getting files for '.$this->getInstanceName().': '.$e->getMessage());
            return false;
        }
    }

}
