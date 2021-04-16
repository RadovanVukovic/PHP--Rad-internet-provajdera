<?php

require_once "Korisnik.php";

class PrepaidKorisnik extends Korisnik
{

    public $kredit;

    public function __construct(InternetProvajder $internetProvajder, string $ime, string $prezime, string $adresa, string $broj_ugovora, TarifniPaket $TarifniPaket, float $kredit)
    {
        parent::__construct($internetProvajder, $ime, $prezime, $adresa, $broj_ugovora, $TarifniPaket);
        $this->kredit = $kredit;
    }

    public function dopuniKredit(float $kredit)
    {
        if($kredit < 0)
        {
            echo("Ne mozete dodati negativan broj kredita! <br>");
            return;
        }

        $this->kredit += $kredit;
        echo("Uspesno ste dodali $kredit dinara. Vase stanje na racunu je {$this->kredit} <br>");

    }

    public function surfuj(string $url, int $megabajti) :bool
    {
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

        $trosak = $megabajti * $this->TarifniPaket->cenaPoMegabajtu;
        if($this->kredit >= $trosak)
        {
            $this->kredit -= $trosak;
            $this->internetProvajder->zabeleziSaobracaj($this, $url, $megabajti);
            $unos = new ListingUnos($url, $megabajti);
            $this->dodajListingUnos($unos);
            echo("Potrosio si $trosak dinara, sada na racunu imas {$this->kredit} dinara. <br>");

            return true;
        }else{
            echo("Nemas dovoljno kredita! <br>");

            return false;
        }

    }

    public function dodajTarifniDodatak(TarifniDodatak $tarifniDodatak)
    {
        if($tarifniDodatak->tip_dodatka == "IPTV" OR $tarifniDodatak->tip_dodatka == "Fiksna_Telefonija") //provera tipa tarifnog dodatka
        {
            echo("Tip tarifnog dodatka ne sme biti ni IPTV ni Fiksna telefonija! <br>");
            return;
        }

        foreach ($this->TarifniDodaci as $dodatak)
        {
            if($tarifniDodatak->tip_dodatka == $dodatak->tip_dodatka)
            {
                echo("Vec imate ovaj tarifni dodatak! <br>");
                return;
            }
        }

        $cena = $tarifniDodatak->cena;

        if($this->kredit >= $cena)
        {
            $this->kredit -= $cena;
            array_push($this->TarifniDodaci, $tarifniDodatak);
            echo("Novo stanje na racunu je {$this->kredit} , tarifni dodatak je uspesno dodat! <br>");
        }else{
            echo("Nemate dovoljno kredita za kupovinu tarifnog dodatka! <br>");
            return;
        }
    }
}