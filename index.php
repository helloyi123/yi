<?php 

include "model.php";

// controllers

class Api extends Base{
    public function homepage(){
    }
}


(new Router())
->hook('after', function($result, $router){
    if($result) echo json_encode($result);
})
->get('/img/:id', function($id){
    header('Content-type: image/jpeg');
    $img = new Image();
    echo $img->find($id)->content;
})
->get('/homepage', function(){
    $banner = (new Banner)->eq('status', 1)->orderby('id desc')->find();
    $categories = (new Category)->orderby('id desc')->limit(5)->findAll();
    return array(
        'banner' => array('id' => $banner->id, 'img' => $banner->imgurl(true)),
        'categories' => array_map(function($c){ return array('id'=>$c->id, 'name'=>$c->name);}, $categories) ,
    );
})
->get('/', function(){
})
->execute();
