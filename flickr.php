<?php
// Script to grab image from flickr
// By sigit prasetya nugroho
// Website : seegatesite.com
class flickr
{
    var $api;
    function __construct($api) {
        $this->api = $api;
    }
    function flickr_photos_search($search,$count_image,$size)
    {
        $params = array(
        'api_key'   => $this->api,
        'method'    => 'flickr.photos.search',
        'text'  => $search,
        'format'    => 'rest',
        'per_page' => $count_image,
        'page'      => 1,);
        $xml = $this->create_url($params);
        if(@$rsp = simplexml_load_file($xml))
        {
            if (count($rsp)<>0)
            {
                foreach($rsp->photos->children() as $photo)
                {
                    if ($photo->getName()=='photo')
                    {
                        $farm=$photo->attributes()->farm;
                        $server=$photo->attributes()->server;
                        $id=$photo->attributes()->id;
                        $secret=$photo->attributes()->secret;
                        if ($size=='Med')
                        {
                            $sz="";
                        }
                        else
                        {
                            $sz = "_".$size;
                        }
                        $gbr[]='<img src="https://farm'.$farm.'.staticflickr.com/'.$server.'/'.$id.'_'.$secret.$sz.'.jpg'.'" /> ';
                    }
                }
            }
            else
            {
                die("No images found!");
            }
        }else
        {
            die("wrong parameter");
        }
        return $gbr;
    }
    function create_url($params)
    {
        $encoded_params = array();
        foreach ($params as $k => $v){
 
            $encoded_params[] = urlencode($k).'='.urlencode($v);
        }
        $url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
        return $url;
    }
}
    echo '<form method="post">';
    echo '<label>Search : </label><input type"text" name="search" /><br/>';
    echo '<label>Result : </label><input type"text" name="count" /> * max : 500<br/>';
    echo '<label>Size : </label>
            <select name="size">
                <option value="s">Square</option>
                <option value="q">Large Square</option>
                <option value="t">Thumbnail</option>
                <option value="m">Small</option>
                <option value="n">Small 320</option>
                <option value="Med" selected>Medium</option>
                <option value="o">Original</option>
            </select><br/>';
    echo '<input type="submit" name="submit" value="search" /><br/><hr/>';
    echo '</form>';
if(isset($_POST['submit']))
{
    if(!isset($_POST['search']))
    {
        echo 'Please fill your search';
    }else
    if(!isset($_POST['count']))
    {
        echo 'Please fill image result';
    }else
    {
        $search=$_POST['search'];
        $result=$_POST['count'];
        $size=$_POST['size'];
        if($size > 500)
        {
            $size = 500;
        }
        $flickr= new flickr('your-api');
        $gbr = $flickr->flickr_photos_search($search,$result,$size);
        foreach($gbr as $hasil)
        {
            echo $hasil.' ';
        }
    }
}
?>