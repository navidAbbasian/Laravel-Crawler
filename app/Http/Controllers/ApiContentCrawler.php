<?php

namespace App\Http\Controllers;


use App\Models\Template;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Exception;
class ApiContentCrawler extends Controller
{
    /**
     * @var Client
     */
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
            $url = "https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/687004388?storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false";
            $response = $this->client->get($url); // URL, where you want to fetch the content
            // process on json api
            $content = $response->getBody()->getContents();

            $decodeContent = json_decode($content);
            dd($decodeContent);
            //final data
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
