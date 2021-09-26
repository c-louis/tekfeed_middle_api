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

class DataController extends Controller {

	public function ranking(Request $request) {
		$params = $request->query->all();
		if (!isset($params['type'])) {
			return ['code' => '300', 'Missing type parameter'];
		}
		if ($params['type'] == 'ICU') {
			return json_encode(IcuRanking::all());
		} else if ($params['type'] == 'SHANGHAI') {
			return json_encode(ShanghaiRanking::all());
		} else {
			return ['code' => '300', 'Given type does not exist !'];
		}
	}

	public function universities(Request $request) {
		$params = $request->query->all();
		if (isset($params['all']) && $params['all'] == '1') {
			$universities = University::with(['icuRanking', 'shanghaiRanking', 'themes'])->get();
			return json_encode($universities);
		}
		return json_encode(University::all());
	}

	public function costOfLiving(Request $request) {
		return json_encode(CostOfLiving::all());
	}
}

?>