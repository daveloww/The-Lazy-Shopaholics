<?php

class Product {

    private $id;
    private $p_id;
    private $name;
    private $photo;
    private $url;
    private $price;
    private $ecommerce;
    
    public function __construct($id, $p_id, $name, $photo, $url, $price, $ecommerce) {
        $this->id = $id;
        $this->p_id = $p_id;
        $this->name = $name;
        $this->photo = $photo;
        $this->url = $url;
        $this->price = $price;
        $this->ecommerce = $ecommerce;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getPid()
    {
        return $this->p_id;
    }

    
    public function getName()
    {
        return $this->name;
    }

    
    public function getPhoto()
    {
        return $this->photo;
    }

    
    public function getUrl()
    {
        return $this->url;
    }

    
    public function getPrice()
    {
        return $this->price;
    }

    
    public function getEcommerce()
    {
        return $this->ecommerce;
    }
}

?>