<?php 

include "model.php";

// controllers

class Api extends Base{
    public function homepage(){
    }
}


(new Router())
->get('/', function(){
    echo "hello word";
})
->get('/homepage', array($api, 'homepage'))
->execute();
