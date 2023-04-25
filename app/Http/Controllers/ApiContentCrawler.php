<?php

namespace App\Http\Controllers;


use App\Models\Endpoint;
use App\Models\Field;
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
    public function getCrawlerContent($endpoint_id): void
    {
        try {
            //endpoint table
            $endpoint = Endpoint::find($endpoint_id);
            $response = $this->client->get($endpoint->url); // URL, where you want to fetch the content
            // process on json api
            $content = $response->getBody()->getContents();

            $decodeContent = json_decode($content);

//            dd($decodeContent);


            //final data
            //comment table
//            foreach ($decodeContent->result->productReviews->content as $index => $test) {
//                $data[$index] = $this->getTemplateData($test);
//            }
            //products tables
            $data = $this->getSecondTemplateData($decodeContent, $endpoint_id);
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

        if (property_exists($decodeContent, "mediaFiles")) $image = true;

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
        if ($image) {
            foreach ($decodeContent->mediaFiles as $i => $img)
                $template_data['image'][$i] =
                    [
                        'url' => $img->url
                    ];
        }
        return $template_data;
    }

    private function getSecondTemplateData($decodeContent, $endpoint_id)
    {
        $tables = Template::where('endpoint_id',$endpoint_id)->get();
        $template_data = [];
        foreach ($tables as $table){
            $template_data[$table->table] = [];
            $fields = Field::select()->where('template_id', $table->id)->get();
            foreach ( $fields as $field){
                $source = "$field->source";
//                dd($decodeContent->$source);
                $template_data[$table->table][$field->destination] = $decodeContent->$source;
            }
        }
//        $template_data = [
//            'product' => [
//                'color' => $decodeContent->result->color,
//                'title' => $decodeContent->result->name,
//                'productCode' => $decodeContent->result->productCode,
//                'nameWithProductCode' => $decodeContent->result->nameWithProductCode,
//                'slug' => $decodeContent->result->url,
//                'gender' => $decodeContent->result->gender->name,
//            ],
//            'images' => [
//                'images_url' => $decodeContent->result->images
//            ],
//            'category' => [
//                'id' => $decodeContent->result->category->id,
//                'name' => $decodeContent->result->category->name,
//                'parents/hierarchy' => $decodeContent->result->category->hierarchy
//
//            ],
//            'brand' => [
//                'id' => $decodeContent->result->brand->id,
//                'name' => $decodeContent->result->brand->name,
//                'slug' => $decodeContent->result->brand->path
//            ],
//        ];
        return $template_data;
    }
}
