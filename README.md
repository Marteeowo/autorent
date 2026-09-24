# Autorent

Autorent on PHP ja MySQL/MariaDB abil tehtud autorendi veebirakendus.

Rakenduses saab vaadata autosid, otsida neid margi või mudeli järgi, avada auto detailvaate ja teha broneeringuid. Administraator saab autosid lisada, muuta ja kustutada.

Projekt on tehtud ilma PHP raamistiketa ning andmebaasiga suhtlemiseks kasutatakse `mysqli`.

## Käivitamine

Klooni projekt:

```bash
git clone https://github.com/Marteeowo/autorent.git
```

Liigu projekti kausta ja käivita Docker:

```bash
docker-compose up -d
```

Rakendus avaneb aadressil:

```text
http://localhost:8080
```

Esimesel käivitamisel võib minna natuke aega, kuni andmebaas ja vajalikud tabelid luuakse.

Andmebaasi SQL fail asub projektis:

```text
db/cars_rent.sql
```

## Mida rakendusega teha saab

### Autode vaatamine

Avalehel ja autode lehel kuvatakse andmebaasis olevad autod kaartidena.

Autode kohta kuvatakse näiteks:

- mark ja mudel
- aasta
- mootor ja kütusetüüp
- käigukast
- istekohtade arv
- rendihind
- auto staatus
- pilt

Autode nimekirjas kasutatakse ka lehekülgede kaupa kuvamist.

### Otsing

Autosid saab otsida nii margi kui ka mudeli järgi.

Otsing kasutab GET parameetrit ning kui otsingukast on tühi, kuvatakse kõik autod.

### Auto detailvaade

Igal autol on eraldi detailvaade.

Näiteks:

```text
auto.php?id=5
```

Detailvaates kuvatakse valitud auto täpsem info ning sealt saab alustada ka broneeringut.

### Kasutajad ja broneeringud

Kasutaja saab endale konto registreerida ja seejärel auto valitud perioodiks broneerida.

Broneeringul valitakse:

- rendi alguskuupäev
- rendi lõppkuupäev
- auto

Koguhind arvutatakse rendipäevade arvu ja auto päevahinna järgi.

Süsteem kontrollib enne salvestamist ka seda, et sama auto broneeringud omavahel ei kattuks.

Kui valitud periood on juba hõivatud, uut broneeringut ei tehta ja kasutajale kuvatakse veateade.

## Admin

Administraatoril on eraldi haldusvaade.

Admin saab:

- vaadata olemasolevaid autosid
- lisada uusi autosid
- muuta olemasolevate autode andmeid
- autosid kustutada

Admini lehed on kaitstud sessiooniga ning ilma sisselogimata neile ligi ei pääse.

## Admini testkonto

```text
Kasutajanimi: admin
Parool: Passw0rd
```

## Kasutatud tehnoloogiad

- PHP 8.x
- PHP `mysqli`
- MySQL / MariaDB
- Bootstrap 5
- Bootstrap Icons
- HTML
- CSS
- PHP sessioonid
- Bcrypt paroolide räsimiseks
- Prepared Statements SQL päringute jaoks
- Docker

Bootstrapit kasutatakse põhilise kujunduse ja responsiivsuse jaoks ning eraldi CSS-i on kasutatud võimalikult vähe.

## Andmebaas

Projekt kasutab kolme põhilist tabelit:

### `users`

Hoiab registreeritud kasutajate andmeid.

Olulisemad väljad:

```text
id
role
first_name
last_name
email
phone
password_hash
created_at
```

### `cars`

Hoiab renditavate autode andmeid.

Olulisemad väljad:

```text
id
brand
model
year
registration_number
price_per_day
fuel_type
transmission
seats
description
image
status
created_at
```

### `reservations`

Seob kasutaja ja auto broneeringuga.

Olulisemad väljad:

```text
id
user_id
car_id
start_date
end_date
total_price
status
created_at
```

Seosed on põhimõtteliselt järgmised:

```text
users -> reservations <- cars
```

Ühel kasutajal võib olla mitu broneeringut ja ühel autol võib olla mitu broneeringut erinevatel aegadel.

## Projekti eesmärk

Projekt on tehtud PHP, MySQL-i ja lihtsa veebirakenduse ülesehituse harjutamiseks.

Selle käigus on tehtud:

- Bootstrapiga veebiliides
- andmebaasist autode kuvamine
- otsing
- auto detailvaade
- CRUD haldus adminile
- kasutajate autentimine
- registreerimine
- sessioonid
- broneeringud
- rendihinna arvutamine
- kattuvate broneeringute kontroll

## Litsents

Projekt on loodud õppetöö jaoks.