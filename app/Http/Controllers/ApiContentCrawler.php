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

            //endpoint table
            $url = "https://public-mdc.trendyol.com/discovery-web-socialgw-service/api/review/284671018?merchantId=107671&storefrontId=1&culture=tr-TR&order=5&searchValue=&onlySellerReviews=false&page=4&tagValue=t%C3%BCm%C3%BC";
            $response = $this->client->get($url); // URL, where you want to fetch the content
            // process on json api
            $content = $response->getBody()->getContents();

            $decodeContent = json_decode($content);

            $data = array();
            foreach ($decodeContent->result->productReviews->content as $index => $test){
                $data[$index] = $this->getTemplateData($test);
            }
            dd($data);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Check is content available
     */
    private function hasContent($node): bool
    {
        return $node->count() > 0;
    }
    /**
     * @param $decodeContent
     * @return array
     */

    private function getTemplateData($decodeContent): array
    {
        $image = false;

        if (property_exists($decodeContent, "mediaFiles"))$image = true;

        //key is dest value is source
        $template_data = [
            'comment' => [
                'title' => $decodeContent->commentTitle,
                'description' => $decodeContent->comment,
                'productSize' => $decodeContent->productSize,
                'username' => $decodeContent->userFullName,
                'id' => $decodeContent->id,
                'rate' => $decodeContent->rate
            ]
        ];
        if ($image){
            foreach ($decodeContent->mediaFiles as $i => $img)
                $template_data['image'][$i] =
                    [
                        'url' => $img->url
                    ];
        }
        return $template_data;
    }
}
