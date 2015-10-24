<?php 

include "vendor/autoload.php";
// models 
class User extends ActiveRecord{
    public $table = 'user';
    public $relations = array(
        array(self::HAS_MANY, 'World', 'uid'),
        array(self::HAS_MANY, 'Description', 'uid'),
        array(self::HAS_MANY, 'Comment', 'uid'),
    );
}

class Category extends ActiveRecord{
    public $table = 'category';
    public $relations = array(
        array(self::HAS_MANY, 'World', 'cid'),
    );
}

class Word extends ActiveRecord{
    public $table = 'word';
    public $relations = array(
        array(self::HAS_MANY, 'Description', 'wid'),
        array(self::BELONGS_TO, 'User', 'uid'),
        array(self::BELONGS_TO, 'Category', 'cid'),
    );
}

class Banner extends ActiveRecord{
    public $table = 'banner';
    public $relations = array(
        array(self::BELONGS_TO, 'World', 'cid'),
        array(self::BELONGS_TO, 'Image', 'imgid'),
    );
}

class Comment extends ActiveRecord{
    public $table = 'comment';
    public $relations = array(
        array(self::BELONGS_TO, 'Description', 'did'),
        array(self::BELONGS_TO, 'User', 'uid')
    );
}

class Description extends ActiveRecord{
    public $table = 'description';
    public $relations = array(
        array(self::BELONGS_TO, 'World', 'wid'),
        array(self::BELONGS_TO, 'User', 'uid')
    );
}

class Image extends ActiveRecord{
    public $table = 'image';
    public $relations = array(
        array(self::BELONGS_TO, 'Description', 'did'),
    );
}

