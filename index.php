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
->get('/category/:id', function($id){
    $word = (new Word)->eq('cid', $id)->orderby('id desc')->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->get('/user/:id/comment', function($id){
    $comments = (new Comment)->eq('uid', $id)->orderby('id desc')->findAll();
    return array(
        'coment' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->content); }, $comments)
    );
})
->get('/user/:id/desc', function($id){
    $descs = (new Description)->eq('uid', $id)->orderby('id desc')->findAll();
    return array(
        'desc' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->content); }, $descs)
    );
})
->get('/user/:id/word', function($id){
    $word = (new Word)->eq('uid', $id)->orderby('id desc')->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->get('/search/:name', function($name){
    $word = (new Word)->like('name', '%'. $name. '%')->orderby('id desc')->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->execute();
