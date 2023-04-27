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

            //final data
            //comment table
            $data = [];
//            foreach ($decodeContent->result->productReviews->content as $index => $test) {
            $data = $this->getTemplateData($decodeContent);
            dd($data);
//            }
            //products tables
//            $data = $this->getSecondTemplateData($content, $endpoint_id);
//            dd($data);
//            dd("آخرشه اینجا");

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $content
     * @return array
     */

    private function getTemplateData($decodeContent): array
    {
        $template_data = [];
//        dd(property_exists($decodeContent->result->productReviews->content[27], "mediaFiles"));
//        dd($decodeContent->result->productReviews->content[27]->mediaFiles[0]->url);
        foreach ($decodeContent->result->productReviews->content as $index => $test) {
            if (property_exists($test, "mediaFiles")) {
                $image = true;
            }else{
                $image = false;
            }

            $template_data[$index] = [
                'comment' => [
                    'title' => $test->commentTitle,
                    'description' => $test->comment,
                    'productSize' => $test->productSize,
                    'username' => $test->userFullName,
                    'id' => $test->id,
                    'rate' => $test->rate,
//                    'test' => $decodeContent->result->productReviews->content[27]->mediaFiles[0]->url
                ]
            ];

            if ($image) {
                foreach ($test->mediaFiles as $i => $img)
                    $template_data['image'][$i] =
                        [
                            'url' => $img->url
                        ];
            }
        }
        return $template_data;
    }
//    private function test($dest, $keys, $template, $field)
//    {
//        foreach ($keys as $key) {
//            if (isset($dest[$key])) {
//                $dest = $dest[$key];
//            } else {
//                foreach ($dest as $item) {
//                    if (isset($item[$key])) {
//                        $template_data[$template->table][$field->destination] = $item[$key];
//                    } else {
//                        $this->test($dest, $item, $template, $field);
//                    }
//                }
//            }
//        }
//    }

//    private function getSecondTemplateData($content, $endpoint_id): array
//    {
//        $templates = Template::where('endpoint_id', $endpoint_id)->get();
//        $template_data = [];
//        foreach ($templates as $template) {
//            $template_data[$template->table] = [];
//            $fields = Field::where('template_id', $template->id)->first();
//            $key_fields = explode('->', $fields->source);
//            $p =[];
//            foreach ($key_fields as $key_field) {
//                if (isset($key_field)) {
//                    $p[] = $key_field;
//                }
//            }
//            if (is_array($p)) {
//                for ($i=0;$i<count($p);$i++){
//                    $template_data[$template->table][$fields->destination] = json_decode($content,true)[$p[$i]][$p[++$i]][$p[++$i]][$i][$p[++$i]];
//                }
////                foreach ($p as $n) {
////                    $template_data[$template->table][$fields->destination] = json_decode($content,true)['result']['productReviews']['content'][1]['comment'];
////                }
//            } else {
//               dd(1);
//            }
//        }
//                foreach ($fields as $h => $field) {
//                $source = $field->source;
//                $keys = explode('->', $source);
//
//                $dest = json_decode($content, true);

//                dd($dest);
//                dd($keys);
//                foreach ($keys as $key){
//                    dd($key);
//
//                }
//                $template_data[$template->table][$field->destination] = $item[$key];
//                foreach ($keys as $t => $key) {
////                    if ($t == 3) dd($key);
//                    if (array_key_exists($key, $dest)) {
//                        if (is_array($dest[$key])) {
//                            $dest = $dest[$key];
//                        }
//                    }else{
//                        if (is_array($dest)){
//                            foreach ($dest as $i => $item){
//                                if (array_key_exists( $key, $item)){
//                                    if (!is_array($item[$key])){
//                                        $template_data[$template->table][$i][$field->destination] = $item[$key];
//                                    }else{
//                                        foreach ($item[$key] as $d => $sag){
//                                            if (array_key_exists( $key[$t++], $sag)) {
//                                                if (!is_array($sag[$key++])) {
//                                                    $template_data[$template->table][$i][$field->destination] = $sag[$key];
//                                                }else{
//                                                    $template_data[$template->table][$i][$field->destination][$key] = $sag[$key];
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            }
//                        }else{
//                            dd('test');
//                        }
//                    }
//                    if (isset($dest[$key])) {
//                        $dest = $dest[$key];
//                    } else {
//                        foreach ($dest as $item) {
//                            if (isset($item[$key])){
//                                $template_data[$template->table][$field->destination] = $item[$key];
//                            }else{
//                                $this->test($dest , $item, $template, $field);
//                            }
//                        }
//                    }
//                }
//                $this->test($dest , $keys, $template , $field);


//                return $dest;
//                $template_data[$template->table][$field->destination] = $content->{"-" . $source};
//            }
//            dd($template_data , 'test');
//        }
//        $template_data = [
//            'product' => [
//                'color' => $content->result->color,
//                'title' => $content->result->name,
//                'productCode' => $content->result->productCode,
//                'nameWithProductCode' => $content->result->nameWithProductCode,
//                'slug' => $content->result->url,
//                'gender' => $content->result->gender->name,
//            ],
//            'images' => [
//                'images_url' => $content->result->images
//            ],
//            'category' => [
//                'id' => $content->result->category->id,
//                'name' => $content->result->category->name,
//                'parents/hierarchy' => $content->result->category->hierarchy
//
//            ],
//            'brand' => [
//                'id' => $content->result->brand->id,
//                'name' => $content->result->brand->name,
//                'slug' => $content->result->brand->path
//            ],
//        ];
//        return $template_data;
//    }
}
