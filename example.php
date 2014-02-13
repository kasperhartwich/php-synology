<?php
include('lib/Synology.php');

$synology = new SynologySurveillanceStation('admin', 'password', '192.168.1.10');

$response = $synology->Camera();

var_dump($response);
