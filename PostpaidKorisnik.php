<?php

require_once "Korisnik.php";

class PostpaidKorisnik extends Korisnik
{

    protected $prekoracenje = 0.0;

    public function __construct(InternetProvajder $internetProvajder, string $ime, string $prezime, string $adresa, string $broj_ugovora, TarifniPaket $TarifniPaket)
    {
        parent::__construct($internetProvajder, $ime, $prezime, $adresa, $broj_ugovora, $TarifniPaket);
    }

    private function UkupnoZaNaplatu()
    {
        $cena_paketa = $this->TarifniPaket->cena_paketa;
        $cena_dodataka = 0;

        foreach ($this->TarifniDodaci as $dodatak)
        {
            $cena_dodataka += $dodatak->cena;
        }

        $cena_prekoracenja = $this->prekoracenje;

        $ukupno = $cena_paketa + $cena_dodataka + $cena_prekoracenja;

        return $ukupno;
    }

    public function surfuj(string $url, int $megabajti): bool
    {
        if($this->TarifniPaket->neogranicenSadrzaj == true)
        {
            $this->internetProvajder->zabeleziSaobracaj($this, $url, $megabajti);
            $unos = new ListingUnos($url, $megabajti);
            $this->dodajListingUnos($unos);
            echo ("Imas neograniceni saobracaj , samo sam zabelezio saobracaj kod Internet provajdera i dodao listing unos. <br>");
            return true;
        }else{

            foreach ($this->TarifniDodaci as $dodatak)
            {
                $url = strtolower($url);
                $dodatak = strtolower($dodatak->tip_dodatka);

                if(strpos($url, $dodatak) == true)
                {
                    $this->internetProvajder->zabeleziSaobracaj($this, $url, $megabajti);
                    $unos = new ListingUnos($url, $megabajti);
                    $this->dodajListingUnos($unos);
                    echo ("Imas besplatan surf na ovom url: $url , samo sam zabelezio saobracaj kod Internet provajdera i dodao listing unos. <br>");

                    return true;
                }
            }

            if($this->TarifniPaket->megabajti >= $megabajti)
            {
                $this->TarifniPaket->megabajti -= $megabajti;
                echo("Megabajti u paketu su sada manji. Sad imas {$this->TarifniPaket->megabajti} MB <br>");

            }else{

                $this->prekoracenje += ($megabajti - $this->TarifniPaket->megabajti) * $this->TarifniPaket->cenaPoMegabajtu;
                $this->TarifniPaket->megabajti = 0;
                echo("Desilo se prekoracenje! Cena prekoracenja je {$this->prekoracenje} dinara <br>");
            }

            $this->internetProvajder->zabeleziSaobracaj($this, $url, $megabajti);
            echo("Zabelezio sam saobracaj! <br>");
            $unos = new ListingUnos($url, $megabajti);
            $this->dodajListingUnos($unos);
            return true;


        }
    }

    public function generisiRacun()
    {
        $racun = "Broj ugovora: {$this->broj_ugovora} <br> Ime: {$this->ime} <br> Prezime: {$this->prezime} <br> Cena paketa: {$this->TarifniPaket->cena_paketa} dinara <br> Tarifni dodaci: " ;
        foreach ($this->TarifniDodaci as $dodatak)
        {
            $ime_dodatka = $dodatak->tip_dodatka;
            $cena_dodatka = $dodatak->cena;

            $racun .= "$ime_dodatka -> $cena_dodatka , ";
        }
        $racun .= "<br> Iznos prekoracenja: {$this->prekoracenje} dinara <br> Ukupna cena: {$this->UkupnoZaNaplatu()} dinara <br>";

        return $racun;
    }

    public function dodajTarifniDodatak(TarifniDodatak $tarifniDodatak)
    {
        if($this->TarifniPaket->neogranicenSadrzaj == true)
        {
            if($tarifniDodatak->tip_dodatka != "IPTV" AND $tarifniDodatak->tip_dodatka != "Fiksna_Telefonija")
            {
                echo("Mozete dodati samo IPTV i Fiksnu telefoniju zato sto imate neogranicen saobracaj <br>");
                return;
            }
        }

        foreach ($this->TarifniDodaci as $dodatak)
        {
            if($tarifniDodatak->tip_dodatka == $dodatak->tip_dodatka)
            {
                echo("Vec imate ovaj tarifni dodatak! <br>");
                return;
            }
        }

        array_push($this->TarifniDodaci, $tarifniDodatak);
        echo("Uspesno ste dodali tarifni dodatak! <br>");

    }
}