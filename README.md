# PHP Paginator

Jednoduchý generický paginator v PHP.

---

## Co projekt umí
- Stránkování libovolného pole
- Nastavitelný počet položek na stránku (`per_page`)
- Nastavení aktuální stránky (`page`)
- Možnost vynutit prázdné pole pro test (`empty=1`)
- Validace vstupních parametrů (doporučeno `filter_input`)

---

## Struktura projektu
```
/src          - zdrojové PHP třídy a kód
/tests        - PHPUnit testy
/vendor       - Composer závislosti
/public       - veřejně dostupné soubory
```

---

## Paginator.php (stručně)
Třída `Paginator` kontroluje prázdné pole, nastaví `totalPages` na `0` a `currentPage` na `0` pokud žádná data nejsou. `getPage()` pak vrací prázdné pole.

---

## Spuštění (doporučeno)
1. Otevři terminál v kořeni projektu (tam, kde je `docker-compose.yml`).
2. Spusť:
```bash
docker-compose up --build
```
3. Otevři v prohlížeči:
```
http://localhost:8080
```

---

## Alternativa bez Compose
```bash
docker build -t php-paginator .
docker run -p 8080:80 --rm php-paginator
```

---

## Jak volat URL s parametry

Paginator podporuje tři parametry v URL:

| Parametr      | Popis                                | Příklad        | Výchozí hodnota |
|---------------|--------------------------------------|----------------|-----------------|
| `page`        | Číslo aktuální stránky (>= 1)        | `?page=2`      | `1`             |
| `per_page`    | Počet položek na stránku (>= 1)      | `?per_page=20` | `10`            |
| `empty`       | Pokud `1`, vrátí prázdná data        | `?empty=1`     | `false`         |

### Příklady použití
1. **Druhá stránka, 10 položek na stránku**  
http://localhost:8080?page=2&per_page=10

2. **První stránka, 20 položek na stránku**  
http://localhost:8080?page=1&per_page=20

3. **Testovací prázdná data**  
http://localhost:8080?empty=1

4. **Více parametrů dohromady**  
http://localhost:8080?page=3&per_page=5&empty=0
> Parametry můžeš libovolně kombinovat.  
> Pokud není parametr zadán, použije se výchozí hodnota.

## API - stručné instrukce

### Základní URL API

API je dostupné na stejném serveru jako frontend, ale s prefixem `/api`.  
Například:  
http://localhost:8080/api/paginator

---

### Podporované parametry API

| Parametr   | Popis                                  | Typ      | Výchozí hodnota |
|------------|---------------------------------------|----------|-----------------|
| `page`     | Číslo aktuální stránky (min. 1)       | integer  | 1               |
| `per_page` | Počet položek na stránku (min. 1)     | integer  | 10              |
| `empty`    | Pokud `1`, vrátí prázdná data         | boolean  | false           |

---

### Jak volat API

- Volání API se provádí pomocí HTTP GET requestu s parametry v URL.

Příklad:
GET http://localhost:8080/api/paginator?page=2&per_page=15

---

### Odpověď API

- API vrací JSON objekt s následujícími daty:

```json
{
  "currentPage": 2,
  "perPage": 15,
  "totalPages": 5,
  "data": [ /* pole aktuálních položek */ ]
}
```
>Pokud je empty=1, vrací se prázdné pole data a totalPages je 0.

---

### Testování API
 - Pomocí nástrojů jako Postman, curl nebo přímo v prohlížeči zavolej API URL s parametry.

 Příklad:
 ```bash
 curl "http://localhost:8080/api/paginator?page=1&per_page=10"
 ```

## Testování

Projekt obsahuje PHPUnit testy, které ověřují správnou funkčnost paginatoru.

### Spuštění testů

V terminálu spusť příkaz:

```bash
vendor/bin/phpunit
```

## Možná rozšíření projektu

- Podpora stránkování dat z databáze (např. přes PDO nebo ORM)
- Přidání více možností řazení a filtrování dat
- Vylepšení validace a sanitace vstupních parametrů
- Podpora dalších formátů výstupu (JSON, XML)
- Integrace s frontend frameworky pro dynamické načítání stránek
