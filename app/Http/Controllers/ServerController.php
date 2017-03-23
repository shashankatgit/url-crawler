<?php

namespace App\Http\Controllers;

use App\Detail;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;

use App\Http\Requests;

class ServerController extends Controller
{
    public function getFetchDetails(Request $request)
    {
        $url = $request['url'];
//        $url = 'http://wikipedia.org';

        if(strpos($url,'http') === false)
        {
            $url = 'http://' . $url;
        }
        $desc=null;
        $image=null;
        $title=null;

        $dom = new DOMDocument();
        $html = file_get_contents($url);

        //echo file_get_contents($url);
        @$dom->loadHTML($html);
        $title = $dom->getElementsByTagName('title')->item(0)->textContent;

        $metas = $dom->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length && $desc==null; $i++)
        {
            $meta = $metas->item($i);

            if(strpos($meta->getAttribute('name'), 'description') !== false)
                $desc = $meta->getAttribute('content');
            else if(strpos($meta->getAttribute('property'), 'description') !== false)
                $desc = $meta->getAttribute('content');
        }

        if($desc ==null)
        {
            $texts= $dom->getElementsByTagName('span');
            if($texts==null or $texts[0]==null)
            {
                $texts= $dom->getElementsByTagName('p');
            }
            else
                $text = $texts[0];


            //echo $text->textContent;

            $content = strip_tags($text->textContent);
            $desc = substr($content,0,50).'...';
        }

        if($image==null)
        {
            $tags = $dom->getElementsByTagName('img');
            $imgtag = $tags[0];
            //echo $tags."asdadsad";
            if($imgtag!=null) {
                $value = $imgtag->getAttribute('src');
                $image = $value;
                $parsedImgUrl = parse_url($image);
//
//              if(strpos($image,'http') === false)
                if($parsedImgUrl==null or !isset($parsedImgUrl['host']))
                {
                    $parsedURL = parse_url($url);
                    $prefix = $parsedURL['host'];
                    $image = 'http://'.$prefix . '/'.$image;
                }
            }

        }
        //echo $title;
        return response()->json(["success"=>true, "title"=>$title, "description"=>$desc, "image"=>$image]);
    }

    public function getSaveDetails(Request $request)
    {
        $url = $request['url'];
        if(strpos($url,'http') === false)
        {
            $url = 'http://' . $url;
        }

        $image = $request['image'];
        $desc = $request['description'];
        $title = $request['title'];

        $detail = Detail::where('url', '=', $url)
            ->first();

        if($detail == null)
        {
            $detail = new Detail();
        }

        $detail->url = $url;
        $detail->title = $title;
        $detail->description = $desc;
        $detail->image = $image;

        $detail->save();
        
        return redirect()->route('crawler.home');
    }
}
