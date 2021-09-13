<?php

namespace Tekfeed\Services;

use Tekfeed\Result;

use GuzzleHttp\Client;

class CustomSearchIcu {
	static public function search(string $university)
	{
		$base_uri = 'https://www.googleapis.com';
		$path = '/customsearch/v1/siterestrict';
		
		$client = new Client([
	        'base_uri' => $base_uri
	    ]);
	    $response = $client->request('GET', $path, [
	    	'query' => [
		    	'key'   => getenv('GOOGLE_API_KEY'),
	        	'cx'    => getenv('GOOGLE_SEARCH_ENGINE'),
	        	'q'     => $university,
	        ]
	    ]);
	    if ($response->getStatusCode() == 200) {
	    	$body = $response->getBody();
	    	$result = Result::Create([
	            'query' => $university,
	            'result' => $body,
	        ]);
	        $result->refresh();
	        return $result;
	    }
	    return null;
	}

	static public function findOrSearch(string $university)
	{
		$res = Result::where('query', $university)->first();
		if ($res !== null) {
			return $res;
		} else {
			return CustomSearchIcu::search($university);
		}
	}
}

?>