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
    public function getCrawlerContent(): void
    {
        try {

            $url = "https://public-mdc.trendyol.com/discovery-web-socialgw-service/api/review/284671018?merchantId=107671&storefrontId=1&culture=tr-TR&order=5&searchValue=&onlySellerReviews=false&page=4&tagValue=t%C3%BCm%C3%BC";
            $response = $this->client->get($url); // URL, where you want to fetch the content
            // process on json api
            $content = $response->getBody()->getContents();

            $decodeContent = json_decode($content);

            //polished Api
            $arrayReview =  $this->arrayReview($decodeContent);

            $api = [
                'raw_api'=> json_encode($decodeContent),
                'polished_api'=> $arrayReview
            ];
            dd($api);
//            ApiStore::create($api);

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
     * @param $decodeContent
     * @return array
     */
    private function arrayReview($decodeContent): array
    {
        $arrayReview = array();
        foreach ($decodeContent->result->productReviews->content as $index => $jsonContent)
            $arrayReview[$index] = [
                'review' => [
                    'comment' => $jsonContent->comment,
                    'productSize' => $jsonContent->productSize],
                'image' => ['آدرس عکس']
            ];
//        dd($arrayReview);
        return $arrayReview;
    }
}
