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

/**
 *
 */
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
            $data = $this->getTemplateData($content, $endpoint_id);
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
     * @param $endpoint_id
     * @return array
     */
    private function getTemplateData($content, $endpoint_id)
    {
        $templates = Template::where('endpoint_id', $endpoint_id)->get();
        foreach ($templates as $template) {
            var_dump(-2);
            $template_data[$template->table] = [];
            $fields = Field::where('template_id', $template->id)->get();
            foreach ($fields as $field) {
                var_dump(-1);
                $key_fields = explode('->', $field->source);
                $source = json_decode($content, true);
                foreach ($key_fields as $k => $key_field) {
                    var_dump(0, $key_field);
                    if (!array_key_exists($key_field, $source)) {
                        var_dump(1);
                        foreach ($source as $s => $res) {
                            if (array_key_exists($key_field, $res)) {
                                var_dump(2);
                                $template_data[$template->table][$s][$field->destination] = $res[$key_field];
                            } else {
                                var_dump(9);
                            }
                        }
                    } else {
                        var_dump(3);
                        $source = $source[$key_field];
                    }
//                    $template_data[$template->table][$fields->destination] = $source;
//                dd($template_data);
                }
            }
        }
        return $template_data;
    }
//        foreach ($decodeContent->result->productReviews->content as $index => $source) {
//            $image = property_exists($source, "mediaFiles");
//            $template_data[$index] = [
//                'comment' => [
//                    'title' => $source->commentTitle,
//                    'description' => $source->comment,
//                    'productSize' => $source->productSize,
//                    'username' => $source->userFullName,
//                    'id' => $source->id,
//                    'rate' => $source->rate,
//                ]
//            ];
//            if ($image) {
//                foreach ($source->mediaFiles as $i => $img) {
//                    $template_data[$index]['image'][$i] =
//                        [
//                            'url' => $img->url,
//                            'thumbnailUrl' => $img->thumbnailUrl
//                        ];
//                }
//            }
//        }

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
