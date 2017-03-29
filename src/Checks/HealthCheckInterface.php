<?php namespace NpmWeb\LaravelHealthCheck\Checks;

interface HealthCheckInterface {

    public function getName();

    public function getType();

    public function check();

}