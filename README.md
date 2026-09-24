# Autorent

Autorent on PHP ja MySQL/MariaDB abil tehtud veebirakendus, kus kasutaja saab autosid vaadata, otsida ja broneerida.

Administraator saab autosid lisada, muuta ja kustutada ning admini vaated on kaitstud sisselogimisega.

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

Andmebaasi SQL fail asub siin:

```text
db/cars_rent.sql
```

## Funktsioonid

### Autod

- autode kuvamine andmebaasist
- autode otsimine margi ja mudeli järgi
- lehekülgede kaupa kuvamine
- eraldi detailvaade igale autole

Detailvaade avaneb auto ID järgi, näiteks:

```text
auto.php?id=5
```

### Broneerimine

Registreeritud kasutaja saab valida autole rendiperioodi.

Broneeringu tegemisel:

- valitakse algus- ja lõppkuupäev
- arvutatakse rendi koguhind
- kontrollitakse, et sama auto broneeringud ei kattuks

Kui auto on valitud perioodil juba broneeritud, uut broneeringut ei salvestata.

### Admin

Administraator saab:

- autosid lisada
- autode andmeid muuta
- autosid kustutada
- vaadata olemasolevat autoparki

Admini lehed kasutavad sessiooni ning ilma sisselogimata neile ligi ei pääse.

## Tehnoloogiad

- PHP 8.x
- PHP `mysqli`
- MySQL / MariaDB
- Bootstrap 5
- HTML / CSS
- PHP sessioonid
- Bcrypt
- Prepared Statements

## Andmebaas

Rakendus kasutab kolme põhilist tabelit:

```mermaid
erDiagram
  USERS ||--o{ RESERVATIONS : teeb
  CARS ||--o{ RESERVATIONS : on_seotud

  USERS {
    INT id PK
    ENUM role
    VARCHAR first_name
    VARCHAR last_name
    VARCHAR email
    VARCHAR phone
    VARCHAR password_hash
    TIMESTAMP created_at
  }

  CARS {
    INT id PK
    VARCHAR brand
    VARCHAR model
    INT year
    VARCHAR registration_number
    DECIMAL price_per_day
    VARCHAR fuel_type
    VARCHAR transmission
    INT seats
    TEXT description
    VARCHAR image
    ENUM status
    TIMESTAMP created_at
  }

  RESERVATIONS {
    INT id PK
    INT user_id FK
    INT car_id FK
    DATE start_date
    DATE end_date
    DECIMAL total_price
    ENUM status
    TIMESTAMP created_at
  }
```

## Admini testkonto

```text
Kasutajanimi: admin
Parool: Passw0rd
```
