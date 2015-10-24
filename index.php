<?php 

include "model.php";

// controllers

class Api extends Base{
    public function homepage(){
    }
}


(new Router())
->error(301, function($path, $message='', $code=301){
    header('Location: '. $path, $code);
    die($message);
})
->hook('auth', function($params){
    if (!session('username'))
        $params['router']->error(301, '/login');
    return $params;
})
->hook('after', function($result, $router){
    if($result) echo json_encode($result);
})
->get('/img/:id', function($id){
    header('Content-type: image/jpeg');
    $img = new Image();
    echo $img->find($id)->content;
})
->get('/homepage', function(){
    $banner = (new Banner)->eq('status', 1)->orderby('id desc')->limit(3)->findAll();
    $categories = (new Category)->orderby('id desc')->limit(5)->findAll();
    return array(
        'banners' => array_map(function($b){ return array('id' => $b->id, 'img' => $b->imgurl(true)); }, $banner),
        'categories' => array_map(function($c){ return array('id'=>$c->id, 'name'=>$c->name);}, $categories) ,
    );
})
->get('/category/:id', function($id, $skip=0, $limit=10){
    $word = (new Word)->eq('cid', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->get('/user/:id/comment', function($id, $skip=0, $limit=10){
    $comments = (new Comment)->eq('uid', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'coment' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->content); }, $comments)
    );
})
->get('/user/:id/desc', function($id, $skip=0, $limit=10){
    $descs = (new Description)->eq('uid', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'desc' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->content, 'up'=>$w->up, 'down'=>$w->down); }, $descs)
    );
})
->get('/user/:id/word', function($id, $skip=0, $limit=10){
    $word = (new Word)->eq('uid', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->get('/user/:id/message/:type', function($id, $type, $skip=0, $limit=10){
    $types = array('word'=>1, 'desc'=>2, 'up' => 3, 'down' => 4);
    $messages = (new Message)->eq('uid', $id)->eq('type', isset($types[$type]) ? $types[$type] : 1)
        ->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'message' => array_map(function($w){ return array('id' => $w->id, 'message' => $w->message, 'type' => $w->type); }, $messages)
    );
})
->get('/search/:name', function($name, $skip=0, $limit=10){
    $word = (new Word)->like('name', '%'. $name. '%')->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'word' => array_map(function($w){ return array('id' => $w->id, 'name' => $w->name); }, $word)
    );
})
->get('/word/:id', function($id, $skip=0, $limit=10){
    $desc = (new Description)->eq('wid', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'desc' => array_map(function($w){
            return array('id' => $w->id, 'content' => $w->content, 'image' => array_map(function($i){
                return $i->imgurl(true);
            }, $w->images));
        }, $desc)
    );
})
->get('/desc/:id', function($id, $skip=0, $limit=10){
    $comments = (new Comment)->eq('did', $id)->orderby('id desc')->limit($skip, $limit)->findAll();
    return array(
        'comment' => array_map(function($w){ return array('id' => $w->id, 'content' => $w->content); }, $comments)
    );
})
->post('/login', function($name, $passwd){
    if ($name && $passwd){
        $user = (new User)->eq('name', $name)->find();
        if ($user && $user->passwd == md5($passwd. $user->atime)){
            session('userid', $user->id);
            session('username', $name);
            return array('userid' => $user->id, 'name' => $name);
        }
    }
    $router->error(301, '/word');
})
->get('/logout', function($router){
    session_destroy();
    $router->error(301, '/homepage');
})
->post('/word/create', function($name, $uid, $cid, $router){
    $word = new Word(array('name'=>$name, 'uid'=>$uid, 'cid'=>$cid, 'atime'=>$time=time(), 'descs'=>0));
    $word->insert();
    return array('id' => $word->id);
}, 'auth')
->post('/comment/create', function($did, $uid, $content, $router){
    $comment = new Comment(array('did'=>$did, 'uid'=>$uid, 'atime'=>$time=time(), 'content'=>$content));
    $comment->insert();
    $message = new Message();
    $message->uid = $uid;
    $message->message = '你的描述被评论了';
    $message->type = 2;
    $message->insert();
    return array('id' => $comment->id);
}, 'auth')
->post('/desc/create', function($wid, $uid, $content, $images, $router){
    $description = new Description(array('wid'=>$wid, 'uid'=>$uid, 'atime'=>$time=time(), 'up'=>0, 'down'=>0, 'share'=>0, 'content'=>$content));
    $description->insert();
    foreach ($images['tmp_name'] as $i => $name){
         $img = new Image();
         $img->content = file_get_contents($name);
         $img->atime = time();
         $img->did = $description->id;
         $img->insert();
    }
    $message = new Message();
    $message->uid = $uid;
    $message->message = '你的词条添加了新的描述';
    $message->type = 1;
    $message->insert();
    return array('id' => $description->id);
}, 'auth')
->post('/desc/:did/:action', function($did, $uid, $action){
    $desc = new Description;
    $desc->id = $did;
    $message = new Message();
    $message->uid = $uid;
    if ($action == 'up') {
        $desc->set('up=up+1')->update();
        $message->message = '你的描述被赞了';
        $message->type = 3;
        $message->insert();
        return 'success';
    }
    if ($action == 'down'){
        $desc->set('down=down+1')->update();
        $message->message = '你的描述被举报了';
        $message->type = 4;
        $message->insert();
        return 'success';
    }
    return 'error';
}, 'auth')
->execute();
