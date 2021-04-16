<?php


class TarifniPaket
{
    public $imePaketa;
    protected $maksimalna_brzina;
    public $cena_paketa;
    public $neogranicenSadrzaj;
    public $megabajti;
    public $cenaPoMegabajtu;

    public function __construct(string $imePaketa,int $maksimalna_brzina, float $cena_paketa, bool $neogranicenSadrzaj, int $megabajti, float $cenaPoMegabajtu)
    {
        $this->imePaketa = $imePaketa;
        $this->maksimalna_brzina = $maksimalna_brzina;
        $this->cena_paketa = $cena_paketa;
        $this->neogranicenSadrzaj = $neogranicenSadrzaj;
        $this->megabajti = $megabajti;
        $this->cenaPoMegabajtu = $cenaPoMegabajtu;
    }
}