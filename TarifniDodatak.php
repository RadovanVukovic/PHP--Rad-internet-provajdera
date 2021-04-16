<?php


class TarifniDodatak
{
    public $cena;
    public $tip_dodatka;

    public function __construct(int $cena, string $tip_dodatka)
    {
        if($tip_dodatka != "Facebook" AND $tip_dodatka != "Instagram" AND $tip_dodatka != "Twitter" AND $tip_dodatka != "IPTV" AND $tip_dodatka != "Viber" AND $tip_dodatka != "Fiksna_Telefonija")
        {
            echo ("Tip dodatka nije validan! <br>");
            return;
        }

        $this->cena = $cena;
        $this->tip_dodatka = $tip_dodatka;
    }
}