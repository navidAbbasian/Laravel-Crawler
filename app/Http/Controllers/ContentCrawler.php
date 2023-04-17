<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Exception;

class ContentCrawler extends Controller
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
            $temp = Template::with('sites')->first();
            $url = Site::where('id', $temp->sites->id)->first();

            $response = $this->client->get($url->site_url); // URL, where you want to fetch the content

            // get content and pass to the crawler
            $content = $response->getBody()->getContents();

            $crawler = new Crawler($content);

            $_this = $this;
            $data = $crawler->filter($temp['card'])
                ->each(function (Crawler $node, $i) use ($_this , $temp) {
                    return $_this->getNodeContent($node , $temp);
                });
            Content::insert($data);
            print_r($data);

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
            'title' => $this->hasContent($node->filter($temp['title'])) != false ? $node->filter($temp['title'])->text() : '',
            'score' => $this->hasContent($node->filter($temp['score'])) != false ? $node->filter($temp['score'])->attr('aria-label') : '',
            'price' => $this->hasContent($node->filter($temp['price'])) != false ? $node->filter($temp['price'])->text() : '',
            'discount-price' => $this->hasContent($node->filter($temp['discount-price'])) != false ? $node->filter($temp['discount-price'])->text() : '',
            'discount-percent' => $this->hasContent($node->filter($temp['discount-percent'])) != false ? $node->filter($temp['discount-percent'])->text() : '',
            'stock' => $this->hasContent($node->filter($temp['stock'])) != false ? 'Out of Stock' : 'In Stock',
            'url' => $this->hasContent($node->filter($temp['url'])) != false ? $node->filter($temp['url'])->attr('href') : '',
            'featured_image' => $this->hasContent($node->filter($temp['featured_image'])) != false ? $node->filter($temp['featured_image'])->eq(0)->attr('src') : '',
            'site_id' => $temp->sites->id
        ];
//        Template::insert($array);
        return $array;
    }
}
