<?php namespace NpmWeb\LaravelHealthCheck\Checks;

class FrameworkHealthCheck extends AbstractHealthCheck {

    public function getType() {
        return 'framework';
    }

    public function check() {
        return true; // if we get here, the framework is up
    }

}