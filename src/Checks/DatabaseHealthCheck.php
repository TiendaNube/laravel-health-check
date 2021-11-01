<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseHealthCheck extends AbstractHealthCheck {

    public function getType() {
        return 'database';
    }

    public function check() {
        try {
            if ( $this->instanceName == 'default' ) {
                return false != DB::select('SELECT 1');
            } else {
                return false != DB::connection( $this->instanceName )->select('SELECT 1');
            }
        } catch( \Exception $e ) {
            Log::error('Exception doing db check: '.$e->getMessage());
            return false;
        }
    }

}
