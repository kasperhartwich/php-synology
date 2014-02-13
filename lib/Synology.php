<?php
include_once(__DIR__ . '/SynologySurveillanceStation.php');

class Synology {
    private $username;
    private $password;
    private $protocol;
    private $hostname;
    private $port;
    private $https;
    private $session;
    private $sid = null;
    private $debug = true;
    private $curl_handler;
    private $common_error_codes = array(
        100 => 'Unknown error', 
        101 => 'Invalid parameters',
        102 => 'API does not exist',
        103 => 'Method does not exist',
        104 => 'This API version is not supported',
        105 => 'Insufficient user privilege',
        106 => 'Connection time out',
        107 => 'Multiple login detected',
    );

    function __construct($username, $password, $hostname, $port = 5000, $https = false, $session = 'DSM') {
        $this->username = $username;
        $this->password = $password;
        $this->protocol = $protocol;
        $this->hostname = $hostname;
        $this->port = $port;
        $this->https = $https;
        $this->session = $session;

        if (!function_exists('curl_init')) {
            throw new Exception('php cURL extension must be installed and enabled');
        }

        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

        //login
        $this->Login();
    }

    function __destruct() {
        $this->Logout();
    }

    public function _request($path, $api, $params = array(), $method = 'Query') {
        $params = array_merge(array(
            'api' => $api,
            'version' => 1,
            'session' => $this->session,
            'sid' => $this->sid,
        ), $params);
        $url = ($this->https ? 'https' : 'http') . '://' . $this->hostname . ':' . $this->port . '/webapi/' . $path . '?' . http_build_query($params);
        curl_setopt($this->curl, CURLOPT_URL, $url);
        if ($this->debug) echo 'http-request: ' . $url . "\n";
        $response = json_decode(curl_exec($this->curl));
        //Common error
        if (!$response->success && array_key_exists($response->error->code, $this->common_error_codes)) {
            throw new Exception($this->common_error_codes[$response->error->code]);
        }
        return $response;
    }

    public function Login() {
        $response = $this->_request('auth.cgi', 'SYNO.API.Auth', array('account' => $this->username, 'passwd' => $this->password, 'method' => 'Login', 'version' => 2));
        if ($response->success) {
            $this->sid = $response->data->sid;
        } else {
            throw new Exception('Could not login (Error code: ' . $response->error->code. ')');
        }
    }

    public function Logout() {
        $this->_request('auth.cgi', 'SYNO.API.Auth', array('method' => 'Logout'));
    }

    public function Info($query = 'SYNO.') {
        $response = $this->_request('query.cgi', 'SYNO.API.Info', array('query' => $query, 'method' => 'Query'));
        return $response->data;
    }
}
