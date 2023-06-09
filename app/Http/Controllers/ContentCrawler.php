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
    public function getCrawlerContent(): void
    {
        try {
            $temp = Template::with('sites')->first();
            //$url = Site::where('id', $temp->sites->id);

            $url = Site::find( $temp->sites->id);

            $response = $this->client->get($url->site_url); // URL, where you want to fetch the content ,($url->site_url)

            // get content and pass to the crawler
            $content = $response->getBody()->getContents();

            $crawler = new Crawler($content);

            $_this = $this;

            $data = $crawler->filter($temp['card'])
                ->each(function (Crawler $node, $i) use ($_this , $temp) {
                    return $_this->getNodeContent($node , $temp);
                });
            Content::insert($data);
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
     * Get node values
     * @filter function required the identifires, which we want to filter from the content. Pars HTML
     */
    private function getNodeContent($node , $temp): array
    {
        return [
            'title' => $this->hasContent($node->filter($temp['title'])) ? $node->filter($temp['title'])->text() : '',
            'score' => $this->hasContent($node->filter($temp['score'])) ? $node->filter($temp['score'])->attr('aria-label') : '',
            'price' => $this->hasContent($node->filter($temp['price'])) ? $node->filter($temp['price'])->text() : '',
            'discount-price' => $this->hasContent($node->filter($temp['discount-price'])) ? $node->filter($temp['discount-price'])->text() : '',
            'discount-percent' => $this->hasContent($node->filter($temp['discount-percent'])) ? $node->filter($temp['discount-percent'])->text() : '',
            'stock' => $this->hasContent($node->filter($temp['stock'])) ? 'Out of Stock' : 'In Stock',
            'url' => $this->hasContent($node->filter($temp['url'])) ? $node->filter($temp['url'])->attr('href') : '',
            'featured_image' => $this->hasContent($node->filter($temp['featured_image'])) ? $node->filter($temp['featured_image'])->eq(0)->attr('src') : '',
            'site_id' => $temp->sites->id
        ];
    }
}
