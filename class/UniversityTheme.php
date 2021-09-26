<?php

namespace Tekfeed;

use Tekfeed\University;

use League\Csv\Reader;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Panther\Client as CrawlerClient;
use Symfony\Component\Panther\DomCrawler\Crawler;

class UniversityTheme extends Model {
    protected $table = 'themes';

    protected $fillable = [
        'name'
   ];

    public function universities() {
        return $this->belongsToMany(University::class, 'theme_university');
    }


    static function createTable(bool $force = false) {
        if (Capsule::schema()->hasTable('themes') && $force) {
            Capsule::schema()->drop('themes');
        }
        if (!Capsule::schema()->hasTable('themes')) {
            Capsule::schema()->create('themes', function ($table) {
                $table->increments('id');
                $table->text('name');
                $table->timestamps();
            });
        }

        if (Capsule::schema()->hasTable('theme_university') && $force) {
            Capsule::schema()->drop('theme_university');
        }
        if (!Capsule::schema()->hasTable('theme_university')) {
            Capsule::schema()->create('theme_university', function ($table) {
                $table->increments('id');
                $table->foreignId('university_id');
                $table->foreignId('university_theme_id');
            });
        }
    }

    static function seed(bool $force = false) {
        if ($force) {
            UniversityTheme::truncate();
        }
        $universities = University::all();
        foreach ($universities as $university) {
            $client = CrawlerClient::createChromeClient();
            $client->request('GET', 'https://tekfeed.epitech.eu/#/universite/'.$university->tekfeedId.'/specifications');
            $crawler = $client->waitForVisibility('table > tbody > tr > td.col-md-7.ng-binding');
            try {
                if (!$crawler->filter('table > tbody > tr > td.col-md-7.ng-binding')->getElement(1)) {
                    echo "Can't find for university : ".$university->tekfeedId."\n";
                    continue;
                }
                $themes = $crawler->filter('table > tbody > tr > td.col-md-7.ng-binding')->eq(1)->text();
                $themes = explode(', ', $themes);
                foreach ($themes as $themeName) {
                    $ut = UniversityTheme::where('name', $themeName);
                    if (!$ut->exists()) {
                        UniversityTheme::create(['name' => $themeName]);       
                    }
                    $ut = UniversityTheme::where('name', $themeName)->get();
                    $university->themes()->attach($ut);
                }
            } catch (Exception $e) {
                echo "Can't find for university : ".$university->tekfeedId;
            }
        }
   }
}

?>