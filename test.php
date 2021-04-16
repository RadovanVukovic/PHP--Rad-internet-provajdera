<?php

//ovaj fajl sluzi za testiranje domaceg zadatka

require_once "InternetProvajder.php";
require_once "PostpaidKorisnik.php";
require_once "PrepaidKorisnik.php";
require_once "ListingUnos.php";
require_once "TarifniDodatak.php";
require_once "TarifniPaket.php";

$provajder = new InternetProvajder("KBCnet");

$tarifniPaket = new TarifniPaket("Biznis paket" ,50, 1200, true, 500, 10);
$tarifniPaket2 = new TarifniPaket("Standard paket" ,60, 1500, false, 500, 10);

$tarifniDodatak = new TarifniDodatak(500, "Facebook");
$tarifniDodatak2 = new TarifniDodatak(500, "Instagram");
$tarifniDodatak3 = new TarifniDodatak(500, "Fiksna_Telefonija");

$petar = new PrepaidKorisnik($provajder, "Petar", "Petrovic", "Makedonska 6", "123456", $tarifniPaket, 1000);
$ana = new PrepaidKorisnik($provajder, "Ana", "Anic", "Makedonska 12", "485959", $tarifniPaket, 10000);

$marko = new PostpaidKorisnik($provajder, "Marko", "Markovic" ,"Knez Mihailova 6", "987654", $tarifniPaket2);
$sinisa = new PostpaidKorisnik($provajder, "Sinisa", "Maric" ,"Knez Mihailova 10", "1544896", $tarifniPaket);

$provajder->dodajKorisnika($petar);
$provajder->dodajKorisnika($marko);
$provajder->dodajKorisnika($sinisa);
$provajder->dodajKorisnika($ana);
























