<?php

use Tekfeed\Result;
use Tekfeed\University;
use Tekfeed\IcuRanking;
use Tekfeed\ShanghaiRanking;
use Tekfeed\CostOfLiving;
use Tekfeed\Services\CustomSearchIcu;

use Illuminate\Database\Capsule\Manager as Capsule;
use Buki\Router\Http\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use GuzzleHttp\Client;

class EpitechController extends Controller {
	
	public function login(Request $request) {
		$params = $request->query->all();
		if (!isset($params['login'])) {
			return ['code' => '404', 'msg' => 'Invalid request'];
		}
		$auth = str_replace('https://intra.epitech.eu/', '', $params['login']);
		$base_uri = 'https://intra.epitech.eu/';
		$path = $auth.'/user/';
		
		$client = new Client([
	        'base_uri' => $base_uri
	    ]);
	    $response = $client->request('GET', $path, [
	    	'query' => [
		    	'format'   => 'json',
	        ]
	    ]);
	    if ($response->getStatusCode() == 200) {
	    	$body = $response->getBody();
	    	return $body;
	    }
	}
}