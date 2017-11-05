SOLUTION
========

Estimation
----------
Estimated: 3-5h
  - Namestitev projekta (uporabljam Windows, prikačujum kakšno dodatno težavo)
  - 5 primerov
  - Implementacija  
  - Pisanje poročila in publish
  
Spent: 3h


Solution
--------

### Instalation
1. SQL import (add indekses): upgrade.sql

2. Run: 

    Current year: $> bin/console report:profiles:yearly

    Year of your choosing: $> bin/console report:profiles:yearly 2015

### 4. Test cases
 
 Primeri uporabe (premalo podatkov, zato sem si jih izmislil)
 1. Članke katerega designerja so bili največkrat brani na portalu
 2. Kreacije (slike) katerega designerja so bile največkrat sherane, likane
 3. Prikaz sorodnih člankov - kateri sorodni članki so bili največkrat klikani v predpostavki, da so bili keratorjevi članki približno enakokrat prikazani
 4. Katere kreacije so bile največkrat ogledane na instagramu
 5. Kako vpliva omemba kreatorja v nazivu članka na št. ogledov
 
 Robni primeri
 1. Februar - prestopno leto
 2. Profil celo leto nima podatkov
 3. Ne obstajo podatki za cel mesec
 4. Meseci imajo različno dolžino dni (preveri pogoje)
 5. Leto je lahko v prihodnost ali preveč v preteklost. Npr. leto pred 2000 definitivno ni smiselno gledati. 
 
 
 
### 5. Implementation
**Podatkovna baza (upgrade.sql)**

Predno začel z delom nastavil indekse:

  1. profiles: 
   - profile_id: primary indeks + AI;
   - profile_name: index - zaradi sort orderja, tukaj smiselno samo če bo postalo veliko imen
   
  2. views
   - profile_id: index
   - Mogoče bom dodal še indeks, vedle po testiranju:  profile_id+date ali samo odate
      Sem testiral in noben od teh dveh primerov ne vpliva na performanco. 
 
**App**
  
1. Nagradil, da se lahko vnese argument leto:
    
    $> bin/console report:profiles:yearly - Izpiše za trenutno leto
    $> bin/console report:profiles:yearly 2015 - Izpiše za leto podano v argumentu


2. Pri fetchanju podatkov sem uporabil:
      - LEFT JOIN - ker mogoče kakšen profil nima zapisov
      - GROUP BY + SUM + IF - ker vso delo opravim MySQL, prav tako ni nobenih subquerijev. 
      - AND v.date >= '" . $year . "-01-01' AND v.date<= '" . $year . "-12-31' - v skopu joina sem takoj podatke zožal samo na izbrano leto
      - Prestopno leto: V februarju je lahko prestopno leto, zato sem vedno uporabil agrument v.date<= '" . $year . "-02-29'. Sem testiral in Mysql pravilno sešteva datume.
      
3. Expect "n/a": 

    Sem uredil znotraj PHPja, ker z uporabo funkcij array_walk. Ocenil sem da glede na majhno št. vrstic ni ni potrebe po SQL rešitvi, ki bi zahtevala uporabo SUBQuerijev. 
 

### 6. Better product

1. V prvo kolono dodati seštevek celega leta - 0.5h
2. Za posamezen mesec vzeti MAX in za ostale prikazati koliko procentov so dosegli drugi - 0.5-1h
3. Primerjava s prejšnjim letom - Koliko se je obisk povečal zmanjšal med npr. Marec 2016 / 2017 
   - Potrebno naložiti prejšnje leto
   - Potrebno izračunati primerjave  (spremeniti array_walk del)
   1h
4. Primerjava prejšnji / trenutni mesec v procentih. S tem bi se videlo, komu raste, komu pada view. 
   - Naložiti še december prejšnje leto
   - izračunati primerjavo (spremeniti array_walk del)
   1h
5. Izdelava WEB strani, da se podatki lahko pregledujejo v brskalniku. Podatke lahko vizualiziramo z grafi, dostopni iz katerekoli lokacije. 
   - PHP aplikacija + HTML, enostaven flat design
   - Predpostavka, da samo en naročnik dostopa, zato bi uporabil kar Basic auth (spletna strani ni javno dostopna, nimamo pa s tem dostanega dela)
   3h
   - Vizualizacija podatkov z grafi (odvisno kaj vse in kako bi prikazovali). Uporabil bi knjižnico jqplot, ki jo poznam. Če bi uporabil bi to vplivalo na čas.    
   2-3h
6. Mail report
   - 1x mesečno se naročniku pošlje mail report s podatki, kot jo aplikacija izpisuje. 
   Ker Synphony ne poznam zelo težko ocenim realni čas. V tutorialu https://github.com/symphonycms/symphony-2/wiki/Email-in-Symphony sicer izgleda enostavno :). 
   2-4h
  

    
 


 




