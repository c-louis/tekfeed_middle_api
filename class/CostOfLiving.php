<?php

namespace Tekfeed;

use Tekfeed\University;

use League\Csv\Reader;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Panther\Client as CrawlerClient;
use Symfony\Component\Panther\DomCrawler\Crawler;

class CostOfLiving extends Model {
    protected $table = 'cost_of_living';

    protected $fillable = [
        'country',
        'costOfLivingIndex',
        'rentIndex',
        'costOfLivingPlusRentIndex',
        'groceriesIndex',
        'restaurantPriceIndex',
        'localPurchasingPowerIndex'
   ];

    static function seed() {
        if (!Capsule::schema()->hasTable('cost_of_living')) {
            Capsule::schema()->create('cost_of_living', function ($table) {
                $table->increments('id');
                $table->text('country')->unique();
                $table->decimal('costOfLivingIndex');
                $table->decimal('rentIndex');
                $table->decimal('costOfLivingPlusRentIndex');
                $table->decimal('groceriesIndex');
                $table->decimal('restaurantPriceIndex');
                $table->decimal('localPurchasingPowerIndex');
                $table->timestamps();
            });
        } else {
            CostOfLiving::truncate();
        }

        // URL : https://www.numbeo.com/cost-of-living/rankings_by_country.jsp

        $client = CrawlerClient::createChromeClient();
        $client->request('GET', 'https://www.numbeo.com/cost-of-living/rankings_by_country.jsp?title=2020');
        $crawler = $client->waitForVisibility('table#t2 > tbody > tr');
        $records = $crawler->filter('table#t2 > tbody > tr')->each(function (Crawler $node, $i) {
            if (CostOfLiving::where('country', $node->children('td')->eq(1)->text())->first() == null) {
                CostOfLiving::create([
                    'country' =>            $node->children('td')->eq(1)->text(),
                    'costOfLivingIndex' =>  floatval($node->children('td')->eq(2)->text()),
                    'rentIndex' =>          floatval($node->children('td')->eq(3)->text()),
                    'costOfLivingPlusRentIndex' =>  floatval($node->children('td')->eq(3)->text()),
                    'groceriesIndex' =>             floatval($node->children('td')->eq(3)->text()),
                    'restaurantPriceIndex' =>       floatval($node->children('td')->eq(4)->text()),
                    'localPurchasingPowerIndex' =>  floatval($node->children('td')->eq(5)->text()),                
                ]);
            }
        });
   }
}

?>