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
        //$url = 'http://wikipedia.org';

        if(strpos($url,'http://') === false)
        {
            $url = 'http://' . $url;
        }
        $desc=null;
        $image=null;
        $title=null;

        $dom = new DOMDocument();
        //echo file_get_contents($url);
        @$dom->loadHTML(file_get_contents($url));
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

        if($image==null)
        {
            $xpath = new DOMXPath($dom);
            $nodelist = $xpath->query("//img");
            $node = $nodelist->item(0); // gets the 1st image
            if($node!=null) {
                $value = $node->attributes->getNamedItem('src')->nodeValue;
                $image = $value;
                if(strpos($image,'http://') === false)
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
        if(strpos($url,'http://') === false)
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
