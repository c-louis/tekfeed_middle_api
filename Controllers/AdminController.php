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

class AdminController extends Controller {

	public function upload(Request $request) {
		// TODO
	}

	public function diff(Request $request) {
		// TODO
	}

	public function table(Request $request) {
		$unis = University::all();
		return $blade->run('table', array('universities' => $unis));
	}
}

?>