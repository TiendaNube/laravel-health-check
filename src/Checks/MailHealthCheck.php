<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use Exception;
use Mail;

class MailHealthCheck extends AbstractHealthCheck {

    protected $emailAddr;
    protected $method;

    public function configure( $config = null ) {
        \Log::debug(__METHOD__.'() '.print_r($config,true));
        parent::configure($config);
        $this->emailAddr = $config['email'];
        if( isset($config['method']) ) {
            $this->method = $config['method'];
        } else {
            $this->method = 'send';
        }
    }

    public function getType() {
        return 'mail';
    }

    public function check() {
        try {
            $method = $this->method;
            $email = $this->emailAddr;
            Mail::$method('laravel-health-check::emails.test', array(), function($message) use($email) {
                $message
                    ->from($email)
                    ->to($email)
                    ->subject('Health Check');
            });
            return true;
        } catch( Exception $e ) {
            return false;
        }
    }

}