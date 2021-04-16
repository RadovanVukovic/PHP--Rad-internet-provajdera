<?php


class InternetProvajder
{
    protected $ime;
    protected $korisnici = [];
    protected $beleznikSaobracaja = [];

    public function __construct(string $ime)
    {
        $this->ime = $ime;
    }

    public function prikazSvihPrepaidKorisnika()
    {
        echo("<br> Prepaid korisnici: <br>");
        foreach ($this->korisnici as $korisnik)
        {
            if($korisnik instanceof PrepaidKorisnik)
            {
                $opis = "<br> Broj ugovora: {$korisnik->broj_ugovora} <br> Ime: {$korisnik->ime} <br> Prezime:{$korisnik->prezime} <br> Kredit:{$korisnik->kredit} <br> Tarifni dodaci: ";
                foreach ($korisnik->TarifniDodaci as $dodatak)
                {
                    $opis .= " {$dodatak->tip_dodatka}   ";
                }
                echo ($opis . "<br>");
            }
        }
        echo ("<br>");
    }

    public function prikazSvihPostpaidKorisnika()
    {
        echo"<br> Postpaid korisnici: <br>";
        foreach ($this->korisnici as $korisnik)
        {
            if($korisnik instanceof PostpaidKorisnik)
            {
                $opis = "Broj ugovora: {$korisnik->broj_ugovora} <br> Ime: {$korisnik->ime} <br> Prezime:{$korisnik->prezime} <br> Tarifni paket:{$korisnik->TarifniPaket->imePaketa} <br> Tarifni dodaci: ";
                foreach ($korisnik->TarifniDodaci as $dodatak)
                {
                    $opis .= " {$dodatak->tip_dodatka}   ";
                }
                echo ($opis . "<br><br>");

            }
        }
        echo ("<br>");
    }


    public function generisiRacune()
    {
        echo"<br>";
        foreach ($this->korisnici as $korisnik)
        {
            if($korisnik instanceof PostpaidKorisnik)
            {
                $racun = $korisnik->generisiRacun();
                echo ($racun . "<br>");
            }
        }

    }

    public function zabeleziSaobracaj(Korisnik $korisnik, string $url, int $mb)
    {
        $opis = "<br> Broj ugovora: {$korisnik->broj_ugovora} <br> URL: $url <br> Broj potrosenih megabajta: $mb <br><br>";

        array_push($this->beleznikSaobracaja, $opis);

        foreach ($this->beleznikSaobracaja as $PojedinacanSaobracaj)
        {
            echo $PojedinacanSaobracaj;
        }
    }


    public function dodajKorisnika(Korisnik $korisnik)
    {
        if($korisnik->internetProvajder !== $this)
        {
            echo("Greska! Korisnik treba da se doda drugom internet provajderu! <br>");
            return;
        }

        foreach ($this->korisnici as $klijent) //provera broja ugovora sa ostalim korisnicima
        {
            if($korisnik->broj_ugovora == $klijent->broj_ugovora)
            {
                echo("Korisnik sa tim brojem ugovora vec postoji! <br>");
                return;
            }
        }
        array_push($this->korisnici, $korisnik);
        echo("Uspesno ste dodali korisnika! <br>");

    }
}