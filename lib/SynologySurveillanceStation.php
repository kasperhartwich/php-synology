<?php
class SynologySurveillanceStation extends Synology {
    private $url_path = 'SurveillanceStation/camera.cgi';

    function __construct($username, $password, $hostname, $port = 5000, $https = false) {
        parent::__construct($username, $password, $hostname, $port, $https, 'SurveillanceStation');
    }

    public function AudioStream() {
        throw new Exception('Not implemented yet.');
    }

    public function Camera($method = 'List') {
        return $this->_request('SurveillanceStation/camera.cgi', 'SYNO.SurveillanceStation.Camera', array('method' => $method, 'offset' => 0, 'limit' => 10, 'additional' => 'device,video,record,schedule,advanced'));
    }

    public function Device() {
        throw new Exception('Not implemented yet.');
    }

    public function Emap() {
        throw new Exception('Not implemented yet.');
    }

    public function Event() {
        throw new Exception('Not implemented yet.');
    }

    public function ExternalRecording() {
        throw new Exception('Not implemented yet.');
    }

    public function Info() {
        return parent::Info('SYNO.SurveillanceStation.');
    }

    public function Notification() {
        throw new Exception('Not implemented yet.');
    }

    public function PTZ() {
        throw new Exception('Not implemented yet.');
    }

    public function Streaming() {
        throw new Exception('Not implemented yet.');
    }

    public function VideoStream() {
        throw new Exception('Not implemented yet.');
    }

}