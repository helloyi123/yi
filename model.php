<?php 

include "vendor/autoload.php";
// models 
ActiveRecord::setDb(new PDO('mysql:host=127.0.0.1;dbname=yi', 'root', 'helloyi1024'));
class User extends ActiveRecord{
    public $table = 'user';
    public $relations = array(
        'words' => array(self::HAS_MANY, 'World', 'uid'),
        'descs' => array(self::HAS_MANY, 'Description', 'uid'),
        'comments' => array(self::HAS_MANY, 'Comment', 'uid'),
    );
}

class Category extends ActiveRecord{
    public $table = 'category';
    public $relations = array(
        'words' => array(self::HAS_MANY, 'World', 'cid'),
    );
}

class Word extends ActiveRecord{
    public $table = 'word';
    public $relations = array(
        'descs' => array(self::HAS_MANY, 'Description', 'wid'),
        'user' => array(self::BELONGS_TO, 'User', 'uid'),
        'category' => array(self::BELONGS_TO, 'Category', 'cid'),
    );
}

class Banner extends ActiveRecord{
    public $table = 'banner';
    public $relations = array(
        'word' => array(self::BELONGS_TO, 'Word', 'wid'),
        'image' => array(self::BELONGS_TO, 'Image', 'imgid'),
    );
    public function imgurl($full=false){
        return ($full?'http://'.$_SERVER['HTTP_HOST']:'').  '/img/'. $this->image->id;
    }
}

class Comment extends ActiveRecord{
    public $table = 'comment';
    public $relations = array(
        'descs' => array(self::BELONGS_TO, 'Description', 'did'),
        'user' => array(self::BELONGS_TO, 'User', 'uid')
    );
}

class Description extends ActiveRecord{
    public $table = 'description';
    public $relations = array(
        'world' => array(self::BELONGS_TO, 'World', 'wid'),
        'user' => array(self::BELONGS_TO, 'User', 'uid')
    );
}

class Image extends ActiveRecord{
    public $table = 'image';
    public $relations = array(
        'desc' => array(self::BELONGS_TO, 'Description', 'did'),
    );
}

