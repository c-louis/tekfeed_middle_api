<?php

namespace Tekfeed;

use Tekfeed\University;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use League\Csv\Reader;

class ShanghaiRanking extends Model {
    protected $table = 'shanghai_ranking';

    protected $fillable = [
       'university_id',
       'national_rank',
       'world_rank',
   ];

    public function university() {
        return $this->hasOne(University::class);
    }    

    static function seed() {
        Capsule::schema()->create('shanghai_ranking', function ($table) {
            $table->increments('id');
            $table->foreignId('university_id')->unique();
            $table->integer('world_rank');
            $table->timestamps();
        });

        $shanghaiRank = [];
        $csv = Reader::createFromPath(realpath(__DIR__.'/../data/shanghai.csv'), 'r');
        $records = $csv->getRecords();
        foreach ($records as $record) {
            $shanghaiRank[$record[1]] = $record[0];
        }
        $universities = University::all();
        $keys = array_keys($shanghaiRank);
        $notFound = 0;
        foreach ($universities as $university) {
            if ($university->shanghaiRanking != null)
                continue;
            if ($university->school != 'National Tsinghua University') {
                foreach ($keys as $key) {
                    if (str_contains($university->school, $key)) {
                        ShanghaiRanking::create([
                            'university_id'   => $university->id,
                            'world_rank'      => intval($shanghaiRank[$key])
                        ]);
                        continue 2;
                    }
                }
            }
            if (isset($shanghaiRank[$university->school])) {
                ShanghaiRanking::create([
                    'university_id'   => $university->id,
                    'world_rank'      => intval($shanghaiRank[$university->school])
                ]);
            }
        }
   }
}

?>