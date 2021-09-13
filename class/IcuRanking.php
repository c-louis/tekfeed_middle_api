<?php

namespace Tekfeed;

use Tekfeed\University;
use Tekfeed\Services\CustomSearchIcu;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Panther\Client as CrawlerClient;

class IcuRanking extends Model {
    protected $table = 'icu_ranking';

	protected $fillable = [
       'university_id',
       'national_rank',
       'world_rank',
   ];

    public function university() {
        return $this->hasOne(University::class);
    }    

    static function seed() {
        Capsule::schema()->create('icu_ranking', function ($table) {
            $table->increments('id');
            $table->foreignId('university_id')->unique();
            $table->integer('national_rank');
            $table->integer('world_rank');
            $table->timestamps();
        });

        $universities = University::all();
        foreach ($universities as $university) {
            if ($university->icuRanking != null)
                continue;
            try {
                $res = CustomSearchIcu::findOrSearch($university->school);
                $json = json_decode($res->result);
                if (!isset($json->items) || !isset($json->items[0]) || !isset($json->items[0]->link)) {
                    continue;
                }
                $icu_link = $json->items[0]->link;

                $client = CrawlerClient::createChromeClient();
                $client->request('GET', $icu_link);

                $crawler = $client->waitForVisibility('table.text-right > tbody > tr');
                $national_rank = explode(' ', $crawler->filter('table.text-right > tbody > tr')->eq(0)->text())[3];
                $world_rank = explode(' ', $crawler->filter('table.text-right > tbody > tr')->eq(1)->text())[3];
                IcuRanking::create([
                    'university_id' => $university->id,
                    'national_rank' => intval($national_rank),
                    'world_rank' => intval($world_rank),
                ]);
            } catch (Exception $e) {
                var_dump($e);
            }
        }
   }
}

?>