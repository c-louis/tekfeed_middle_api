<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Tekfeed\Result;

$router = new Router();
$router->get('/', function() {
    return ['code' => '400', 'msg' => 'Route not served'];
});

$router->get('/seed', function() {
    Capsule::schema()->create('result', function ($table) {
        $table->increments('id');
        $table->longText('query');
        $table->longText('result');
        $table->timestamps();
    });

    return ['code' => '200', 'msg' => 'Seeded successfully'];
});


$router->get('/search', function(Request $request) {
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
            $headers = array('Accept' => 'application/json');
            $client = new Client([
                'base_uri' => 'https://www.googleapis.com'
            ]);
            $response = $client->request('GET', '/customsearch/v1/siterestrict', [
                'query' => [
                    'key'   => getenv('GOOGLE_API_KEY'),
                    'cx'    => getenv('GOOGLE_SEARCH_ENGINE'),
                    'q'     => $params['q'],
                ]
            ]);
            if ($response->getStatusCode() == 200) {
                $body = $response->getBody();
                Result::Create([
                    'query' => $params['q'],
                    'result' => $body,
                ]);
                return $body;
            } else {
                return ['code' => '500', 'msg' => 'Request to googleapis failed !'];
            }
            return ['code' => 'not found'];
        }
    }
});

$router->run();

?>