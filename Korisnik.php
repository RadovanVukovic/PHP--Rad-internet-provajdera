<?php

require_once "IzradaListinga.php";

abstract class Korisnik implements IzradaListinga
{
    public $internetProvajder;
    public $ime;
    public $prezime;
    protected $adresa;
    public $broj_ugovora;
    public $TarifniPaket;

    protected $listingUnosi = [];
    public $TarifniDodaci = [];

    public function __construct(InternetProvajder $internetProvajder, string $ime, string $prezime, string $adresa, string $broj_ugovora, TarifniPaket $TarifniPaket)
    {
        $this->internetProvajder = $internetProvajder;
        $this->ime = $ime;
        $this->prezime = $prezime;
        $this->adresa = $adresa;
        $this->broj_ugovora = $broj_ugovora;
        $this->TarifniPaket = $TarifniPaket;
    }

    protected function dodajListingUnos(ListingUnos $listingUnos)
    {
        foreach ($this->listingUnosi as $pojedinacanUnos)
        {
            if($pojedinacanUnos->url == $listingUnos->url)
            {
                $pojedinacanUnos->dodajMegabajte($listingUnos->megabajti);
                echo("Url vec postoji, megabajti su dodati postojecem unosu! <br>");
                return;
            }
        }

        array_push($this->listingUnosi, $listingUnos);
        echo("Uspesno je dodat listing unos! <br>");
    }

    private function PoredjenjeMegabajta($a, $b) //funkcija za usort()
    {
        $prvi = $a->megabajti;
        $drugi = $b->megabajti;

        if ($prvi == $drugi)
        {
            return 0;
        }
        if($prvi > $drugi)
        {
            return -1;
        }

        return 1;
    }

    public function napraviListing(): string
    {
        usort($this->listingUnosi, array($this , "PoredjenjeMegabajta")); //sortiramo listingUnose po broju MB

        $PoredjaniUnosi = "";

        foreach ($this->listingUnosi as $unos)
        {
            $PoredjaniUnosi .= "URL: {$unos->url} , Broj potrosenih MB: {$unos->megabajti} <br>";
        }

        return $PoredjaniUnosi;
    }

    public abstract function surfuj(string $url, int $megabajti) :bool;
    public abstract function dodajTarifniDodatak(TarifniDodatak $tarifniDodatak);
}