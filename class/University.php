<?php

namespace Tekfeed;

use Tekfeed\IcuRanking;
use Tekfeed\ShanghaiRanking;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use League\Csv\Reader;

class University extends Model {
    protected $table = 'university';

	protected $fillable = [
        'school',
        'country',
        'city',
        'tekfeedId',
        'dates',
        'language',
        'dualDegrees',
        'gpa',
        'languageRestrictions',
        'additionalFees',
        'places'
    ];

    public function icuRanking() {
        return $this->hasOne(IcuRanking::class);
    }

    public function shanghaiRanking() {
        return $this->hasOne(ShanghaiRanking::class);
    }

   static function seed() {
        Capsule::schema()->create('university', function ($table) {
            $table->increments('id');
            $table->longText('school'); // Col 3
            $table->longText('country'); // Col 1
            $table->longText('city'); // Col 2
            $table->text('dates'); // Col 4
            $table->text('language'); // Col 5
            $table->longText('dualDegrees'); // Col 6
            $table->text('gpa'); // Col 7
            $table->longText('languageRestrictions'); // Col 9
            $table->bigInteger('additionalFees'); // Col 10
            $table->integer('places'); // Col 11
            $table->integer('tekfeedId'); // Col 12
            $table->timestamps();
        });

        $csv = Reader::createFromPath(realpath(__DIR__.'/../data/university.csv'), 'r');

        $universities = $csv->getRecords();
        foreach ($universities as $university) {
            University::create([
                'country'       => $university[0],
                'city'          => $university[1],
                'school'        => $university[2],
                'dates'         => $university[3],
                'language'      => $university[4],
                'dualDegrees'   => $university[5],
                'gpa'           => $university[6],
                'languageRestrictions'  => $university[7],
                'additionalFees'        => $university[8],
                'places'        => $university[9],
                'tekfeedId'     => $university[10],
            ]);
        }
   }
}

?>