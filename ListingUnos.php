<?php


class ListingUnos
{
    public $url;
    public $megabajti;

    public function __construct(string $url,int $megabajti)
    {
        $this->url = $url;
        $this->megabajti = $megabajti;
    }

    public function dodajMegabajte(int $megabajti)
    {
        $this->megabajti += $megabajti;
    }
}