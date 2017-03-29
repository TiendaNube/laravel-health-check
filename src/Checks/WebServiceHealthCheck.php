<?php namespace NpmWeb\LaravelHealthCheck\Checks;

use GuzzleHttp\Client as HttpClient;

class WebServiceHealthCheck extends AbstractHealthCheck {

    public function getType() {
        return 'webservice';
    }

    public function check() {
        try {
            $httpClient = new HttpClient();
            \Log::debug(__METHOD__.':: checking URL '.$this->config['url']);
            $response = $httpClient->get($this->config['url']);
            if (array_key_exists('check', $this->config)) {
                return $this->config['check']->__invoke($response);
            } else {
                // by default just check that the response is not empty
                return !empty($response->getBody());
            }
        } catch( \Exception $e ) {
            \Log::error('Exception doing web service check: '.$e->getMessage());
            return false;
        }
    }

}