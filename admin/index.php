<?php 

include "../model.php";
MicroTpl::$debug = true;
// controllers
/* extends ActiveRecord.php Base class*/
class BaseController extends Base{
    public function render($view, $params=array(), $layout='layout.html', $templatePath='template/', $return=false){
        if ($return) ob_start();
        MicroTpl::render($templatePath. $view, array_merge($params, array('controller'=>$this), $this->data), $layout?$templatePath. $layout:false);
        if ($return) return ob_get_clean();
    }
}
/* widgets */
class Widget extends BaseController{
    public function __toString(){
        //if ($content = mcache(get_class($this))) return $content;
        $this->run();
        return $content = $this->render($this->template, array(), '', 'template/', true);
        //return mcache(get_class($this), $content);
    }
}


class UserWidget extends Widget{
    public function run(){
        $this->template = 'user.html';
        $this->header = 'Manage User';
        $this->users = (new User)->orderby('id desc')->findAll();
    }
}

class WordWidget extends Widget{
    public function run(){
        $this->template = 'word.html';
        $this->header = 'Manage Word';
        $this->categories = (new Category)->orderby('id desc')->findAll();
        $this->words = (new Word)->orderby('id desc')->findAll();
    }
}

class DescriptionWidget extends Widget{
    public function run(){
        $this->template = 'description.html';
        $this->header = 'Manage Description';
        $this->words = (new Word)->orderby('id desc')->findAll();
        $this->descs = (new Description)->orderby('id desc')->findAll();
    }
}

class CommentWidget extends Widget{
    public function run(){
        $this->template = 'comment.html';
        $this->header = 'Manage Comment';
        $this->comments = (new Comment)->orderby('id desc')->findAll();
        $this->descs = (new Description)->orderby('id desc')->findAll();
    }
}

class CategoryWidget extends Widget{
    public function run(){
        $this->template = 'category.html';
        $this->header = 'Manage Category';
        $this->categories = (new Category)->orderby('id desc')->findAll();
    }
}

class BannerWidget extends Widget{
    public function run(){
        $this->template = 'banner.html';
        $this->header = 'Manage Banner';
        $this->words = (new Word)->orderby('id desc')->findAll();
        $this->banners = (new Banner)->orderby('id desc')->findAll();
    }
}

class MessageWidget extends Widget{
    public function run(){
        $this->template = 'message.html';
        $this->header = 'Manage Message';
        $this->messages = (new Message)->orderby('id desc')->findAll();
    }
}



class Admin extends BaseController{
    public function test(){
        $this->render('test.html');
    }
    public function user(){
        $this->render('content.html', array('content'=>new UserWidget));
    }
    public function createuser($name, $passwd, $profile, $router){
        $user = new User(array('name'=>$name, 'atime'=>$time=time(), 'passwd'=>md5($passwd. $time), 'profile'=>$profile));
        $user->insert();
        $router->error(301, '/user');
    }
    public function word(){
        $this->render('content.html', array('content'=>new WordWidget));
    }
    public function createword($name, $uid, $cid, $router){
        $word = new Word(array('name'=>$name, 'uid'=>$uid, 'cid'=>$cid, 'atime'=>$time=time(), 'descs'=>0));
        $word->insert();
        $router->error(301, '/word');
    }
    public function category(){
        $this->render('content.html', array('content'=>new CategoryWidget));
    }
    public function createcategory($name, $router){
        $word = new Category(array('name'=>$name, 'atime'=>$time=time(), 'num'=>0));
        $word->insert();
        $router->error(301, '/category');
    }
    public function banner(){
        $this->render('content.html', array('content'=>new BannerWidget));
    }
    public function createbanner($wid, $image, $router){
        $img = new Image();
        $img->content = file_get_contents($image['tmp_name']);
        $img->atime = time();
        $img->insert();
        $banner = new Banner(array('wid'=>$wid, 'imgid'=>$img->id, 'atime'=>$time=time(), 'status'=>1));
        $banner->insert();
        $router->error(301, '/banner');
    }
    public function comment(){
        $this->render('content.html', array('content'=>new CommentWidget));
    }
    public function createcomment($did, $uid, $content, $router){
        $comment = new Comment(array('did'=>$did, 'uid'=>$uid, 'atime'=>$time=time(), 'content'=>$content));
        $comment->insert();
        $router->error(301, '/comment');
    }
    public function description(){
        $this->render('content.html', array('content'=>new DescriptionWidget));
    }
    public function createdescription($wid, $uid, $content, $images, $router){
        $description = new Description(array('wid'=>$wid, 'uid'=>$uid, 'atime'=>$time=time(), 'up'=>0, 'down'=>0, 'share'=>0, 'content'=>$content));
        $description->insert();
        foreach ($images['tmp_name'] as $i => $name){
             $img = new Image();
             $img->content = file_get_contents($name);
             $img->atime = time();
             $img->did = $description->id;
             $img->insert();
        }
        $router->error(301, '/desc');
    }
    public function message(){
        $this->render('content.html', array('content'=>new MessageWidget));
    }
    public function createmessage($type, $uid, $message, $router){
        $message = new Message(array('type'=>$type, 'uid'=>$uid, 'atime'=>$time=time(), 'message'=>$message));
        $message->insert();
        $router->error(301, '/message');
    }
    public function img($id){
        $img = (new Image);
        header('Content-type:image/png');
        echo $img->eq('id', $id)->find()->content;
    }
    public function delete($name, $id){
        $name = ucfirst($name);
        $data = new $name;
        $data->id = $id;
        $data->delete();
        die('<script>history.back();</script>');
    }
}

$admin = new Admin;

(new Router())
->error(301, function($path, $message=''){
    header('Location: '. $path, 301);
    die($message);
})
->hook('after', function($result, $router){
    if($result) echo json_encode($result);
})
->get('/', array($admin, 'test'))
->get('/delete/:name/:id', array($admin, 'delete'))
->get('/img/:id', array($admin, 'img'))
->get('/user', array($admin, 'user'))
->post('/user', array($admin, 'createuser'))
->get('/word', array($admin, 'word'))
->post('/word', array($admin, 'createword'))
->get('/category', array($admin, 'category'))
->post('/category', array($admin, 'createcategory'))
->get('/banner', array($admin, 'banner'))
->post('/banner', array($admin, 'createbanner'))
->get('/comment', array($admin, 'comment'))
->post('/comment', array($admin, 'createcomment'))
->get('/desc', array($admin, 'description'))
->post('/desc', array($admin, 'createdescription'))
->get('/message', array($admin, 'message'))
->post('/message', array($admin, 'createmessage'))
->execute();
