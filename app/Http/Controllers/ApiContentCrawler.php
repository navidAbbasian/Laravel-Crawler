<?php

namespace App\Http\Controllers;


use App\Models\ApiStore;
use App\Models\Content;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Exception;
class ApiContentCrawler extends Controller
{
    private $client;
    /**
     * Class __contruct
     */
    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false
        ]);
    }
    /**
     * Content Crawler
     */
    public function getCrawlerContent()
    {
        try {

            $url = "https://public-mdc.trendyol.com/discovery-web-socialgw-service/api/review/284671018?merchantId=107671&storefrontId=1&culture=tr-TR&order=5&searchValue=&onlySellerReviews=false&page=4&tagValue=t%C3%BCm%C3%BC";
            $response = $this->client->get($url); // URL, where you want to fetch the content ,($url->site_url)
            // process on json api
            $content = $response->getBody()->getContents();

            $decodeContent = json_decode($content);

            $test = [
                'raw_api'=> json_encode($decodeContent),
                'polished_api'=> json_encode(['1' ,'2'])
            ];

            ApiStore::create($test);
            foreach ($decodeContent->result->productReviews->content as $jasonContent)
                $array = [
                    'comment' => $jasonContent->comment,
                    'productSize' => $jasonContent->productSize

                ];
            dd($array);


//            $arrayContent = array_column($decodeContent->result, 'comment');
//            dd($arrayContent);
//            if ($decodeContent->isSuccess){
//                dd('moz');
//            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    /**
     * Check is content available
     */
    private function hasContent($node)
    {
        return $node->count() > 0 ? true : false;
    }
    /**
     * Get node values
     * @filter function required the identifires, which we want to filter from the content.
     */
    private function getNodeContent($node , $temp)
    {
        $array = [


        ];
        return $array;
    }
}
