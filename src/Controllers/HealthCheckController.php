<?php namespace NpmWeb\LaravelHealthCheck\Controllers;

use App;
use Illuminate\Routing\Controller;
use Response;

class HealthCheckController extends Controller {

    protected $healthChecks;

    public function __construct() {
        $this->healthChecks = App::make('health-checks');
    }

    public function index()
    {
        $checkNames = array_map( function($check) {
             \Log::debug(__METHOD__.':: got another health-check! ['. $check->getName().']');
            return $check->getName();
        }, $this->healthChecks );

        return Response::json([
            'status' => 'success',
            'checks' => $checkNames,
        ]);
    }

    public function show($checkName)
    {
        $check = $this->getHealthCheckByName($checkName);
        $result = $check->check();
        return Response::json([
            'status' => $result,
        ]);
    }

    protected function getHealthCheckByName($name) {
        foreach( $this->healthChecks as $check ) {
            if( $name == $check->getName() ) {
                return $check;
            }
        }
        return null;
    }
}