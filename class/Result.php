<?php

namespace Tekfeed;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;

class Result extends Model {
    protected $table = 'result';

	protected $fillable = [
       'query', 'result'
   ];

   static function seed() {
        if (!Capsule::schema()->hasTable('result')) {
            Capsule::schema()->create('result', function ($table) {
                $table->increments('id');
                $table->longText('query');
                $table->longText('result');
                $table->timestamps();
            });
        }
   }
}

?>