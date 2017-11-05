SOLUTION
========

Estimation
----------
Estimated: 3-5h
  - Namestitev projekta (uporabljam Windows, prikačujum kakšno dodatno težavo)
  - Implementacija  
  - 5 testnih primerov
  - Pisanje poročila in publish
  
Spent: 3 hours


Solution
--------

### Instalation
1. SQL import (add indekses): upgrade.sql

2. Run: 

    Current year: $> bin/console report:profiles:yearly

    Year of your choosing: $> bin/console report:profiles:yearly 2015

### 4. Test cases
 
 1. Februar - prestopno leto
 2. Profil celo leto nima podatkov
 3. Ne obstajo podatki za cel mesec
 4. Meseci imajo različno dolžino dni (preveri pogoje)
 5. Leto je lahko v prihodnost ali preveč v preteklost. Npr. leto pred 2000 definitivno ni smiselno gledati. 
 6. 
 
 
### 5. Implementation
**Podatkovna baza (upgrade.sql)**
Predno začel z delom nastavil indekse:
  profiles: 
   - profile_id: primary indeks + AI;
   - profile_name: index - zaradi sort orderja, tukaj smiselno samo če bo postalo veliko imen
   
  views
   - profile_id: index
   - Mogoče bom dodal še indeks, vedle po testiranju:  profile_id+date ali samo odate
      Sem testiral in noben od teh dveh primerov ne vpliva na performanco. 
 
**App**
  
Nagradil, da se lahko vnese argument leto:
$> bin/console report:profiles:yearly - Izpiše za trenutno leto
$> bin/console report:profiles:yearly 2015 - Izpiše za leto podano v argumentu

Pri fetchanju podatkov sem uporabil:
  - LEFT JOIN - ker mogoče kakšen profil nima zapisov
  - GROUP BY + SUM + IF - ker vso delo opravim MySQL, prav tako ni nobenih subquerijev. 
  - AND v.date >= '" . $year . "-01-01' AND v.date<= '" . $year . "-12-31' - v skopu joina sem takoj podatke zožal samo na izbrano leto
  - Prestopno leto: V februarju je lahko prestopno leto, zato sem vedno uporabil agrument v.date<= '" . $year . "-02-29'. Sem testiral in Mysql pravilno sešteva datume.
  
Expect "n/a": Sem uredil znotraj PHPja, ker z uporabo funkcij array_walk. Ocenil sem da glede na majhno št. vrstic ni ni potrebe po SQL rešitvi, ki bi zahtevala uporabo SUBQuerijev. 
 

### 6. Better product
