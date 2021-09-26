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

    static function createTable(bool $force = false) {
        if (Capsule::schema()->hasTable('icu_ranking') && $force) {
            Capsule::schema()->drop('icu_ranking');
        }
        if (!Capsule::schema()->hasTable('icu_ranking')) {
            Capsule::schema()->create('icu_ranking', function ($table) {
                $table->increments('id');
                $table->foreignId('university_id')->unique();
                $table->integer('national_rank');
                $table->integer('world_rank');
                $table->timestamps();
            });
        }
    }

    static function seed(bool $force = false) {
        if ($force) {
            IcuRanking::truncate();
        }
        $universities = University::all();
        foreach ($universities as $university) {
            if ($university->icuRanking != null)
                continue;
            $res = CustomSearchIcu::findOrSearch($university->school);
            $json = json_decode($res->result);
            if (!isset($json->items) || !isset($json->items[0]) || !isset($json->items[0]->link)) {
                continue;
            }
            $icu_link = $json->items[0]->link;

            $client = CrawlerClient::createChromeClient();
            $crawler = $client->request('GET', $icu_link);

            try {
                if (!$crawler->filter('table.text-right > tbody > tr')->getElement(0)) {
                    continue;
                }
                $national_rank = explode(' ', $crawler->filter('table.text-right > tbody > tr')->eq(0)->text())[3];
                $world_rank = explode(' ', $crawler->filter('table.text-right > tbody > tr')->eq(1)->text())[3];
                IcuRanking::create([
                    'university_id' => $university->id,
                    'national_rank' => intval($national_rank),
                    'world_rank' => intval($world_rank),
                ]);
            } catch (mixed $e) {
            
            }
        }
   }
}

?>