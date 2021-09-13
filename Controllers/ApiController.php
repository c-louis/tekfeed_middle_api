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

class ApiController extends Controller {
	
	public function seed(Request $request)
	{
		$params = $request->query->all();
		if (!isset($params['key']) || $params['key'] != getenv('MIDDLE_API_KEY') || getenv('LOCK') == 'TRUE') {
			return ['code' => '404', 'msg' => 'Invalid request'];
		}
		$hasError = false;
		try {
			Result::seed();
		} catch (Exception $e) {
			$hasError = true;
			print_r($e);
		}
		try {
			University::seed();
		} catch (Exception $e) {
			$hasError = true;
			print_r($e);
		}
		try {
			IcuRanking::seed();
		} catch (Exception $e) {
			$hasError = true;
			echo 'FOUND ERROR IN ICU RANKING';
			print_r($e);
		}
		try {
			ShanghaiRanking::seed();
		} catch (Exception $e) {
			$hasError = true;
			print_r($e);
		}
		try {
			CostOfLiving::seed();
		} catch (Exception $e) {
			$hasError = true;
			print_r($e);
		}

		if ($hasError) {
	    	return ['code' => '500', 'msg' => 'Something must have failed'];
		} else {
	    	return ['code' => '200', 'msg' => 'Seeded successfully'];
		}
	}

	public function clear(Request $request)
	{
		$params = $request->query->all();

		if (!isset($params['key']) || $params['key'] != getenv('MIDDLE_API_KEY')) {
			return ['code' => '404', 'msg' => 'Invalid request'];
		} else {
			Capsule::schema()->dropIfExists('result');
			Capsule::schema()->dropIfExists('university');
			Capsule::schema()->dropIfExists('icu_ranking');
			Capsule::schema()->dropIfExists('shanghai_ranking');
			Capsule::schema()->dropIfExists('cost_of_living');
		}
	}

	public function search(Request $request) {

		$params = $request->query->all();
	    if (!isset($params['key']) || $params['key'] != getenv('MIDDLE_API_KEY')) {
	        return [
	            'code' => '300',
	            'msg' => 'Missing api key',
	        ];
	    }
	    else if (!isset($params['q'])) {
	        return ['code' => '300', 'msg' => 'Missing query parameter'];
	    } else {
	        $oldResult = Result::where('query', $params['q'])->first();

	        if ($oldResult != null) {
	            return $oldResult->result;
	        } else if (getenv('LOCK') == "TRUE") {
	            return ['code' => '404', 'msg' => 'No previous result and api has been locked'];
	        } else {
	            return CustomSearchIcu::findOrSearch($params['q']);
	        }
	    }
	}
}