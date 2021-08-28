<?php

namespace Tekfeed;

use Illuminate\Database\Eloquent\Model as Model;

class Result extends Model {
    protected $table = 'result';

	protected $fillable = [
       'query', 'result'
   ];
}

?>