<?php

namespace App\Http\Controllers;

use App\Models\Content;
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
//            $url = "https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/687004388?storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false";
            $url = "https://iranmojo.com/product-category/buy-cp-call-of-duty/";
            $response = $this->client->get($url); // URL, where you want to fetch the content

            // get content and pass to the crawler
            $content = $response->getBody()->getContents();

            $crawler = new Crawler($content);
//            foreach ($crawler as $domElement) {
//                dd($domElement->nodeValue);
//            }
            $_this = $this;
            $data = $crawler->filter('div.product-grid-item.product')
                ->each(function (Crawler $node, $i) use ($_this) {
                    return $_this->getNodeContent($node);
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
    private function getNodeContent($node)
    {
        $temp = Template::all();
        dd($temp[0]['score']);
        $array = [
            'title' => $this->hasContent($node->filter('h3.wd-entities-title a')) != false ? $node->filter('.wd-entities-title a')->text() : '',
            'score' => $this->hasContent($node->filter('div.star-rating')) != false ? $node->filter('div.star-rating')->attr('aria-label') : '',
            'price' => $this->hasContent($node->filter('del span.woocommerce-Price-amount')) != false ? $node->filter('del span.woocommerce-Price-amount')->text() : '',
            'discount-price' => $this->hasContent($node->filter('ins span.woocommerce-Price-amount')) != false ? $node->filter('ins span.woocommerce-Price-amount')->text() : '',
            'discount-percent' => $this->hasContent($node->filter('span.onsale.product-label')) != false ? $node->filter('span.onsale.product-label')->text() : '',
            'stock' => $this->hasContent($node->filter('span.out-of-stock')) != false ? 'Out of Stock' : 'In Stock',
            'url' => $this->hasContent($node->filter('div.product-element-top a')) != false ? $node->filter('div.product-element-top a')->attr('href') : '',
            'featured_image' => $this->hasContent($node->filter('img.attachment-woocommerce_thumbnail')) != false ? $node->filter('img.attachment-woocommerce_thumbnail')->eq(0)->attr('src') : ''
        ];

        return $array;
    }
}
