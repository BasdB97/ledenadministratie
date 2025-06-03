# Ledenadministratie

Een PHP-gebaseerde webapplicatie voor het beheren van leden en contributies voor een vereniging. Deze applicatie is gemaakt als eindopdracht voor de opleiding Front-End Developer van LOI.
Er zijn 3 accounts beschikbaar met elk hun eigen toegangsniveau. 

## Functionaliteiten

-   Beheer van leden en hun gegevens
-   Beheer van contributies per lid
-   Genereren van jaaroverzichten
-   Beheer van familie-eenheden
-   Gebruikersbeheer met verschillende toegangsniveaus

## Installatie

1. Zorg ervoor dat je XAMPP (of vergelijkbare stack) hebt geïnstalleerd met PHP en MySQL
2. Clone deze repository naar je `htdocs` map:
    ```bash
    git clone https://github.com/BasdB97/ledenadministratie
    ```
3. Importeer de database:

    - Open phpMyAdmin (http://localhost/phpmyadmin)
    - Maak een nieuwe database aan genaamd `ledenadministratie`
    - Importeer het `database.sql` bestand uit de `database` map

4. Start je webserver en database
5. Bezoek de applicatie via: http://localhost/ledenadministratie

## Gebruikersaccounts

De applicatie is te gebruiken met de volgende accounts:

1. **Beheerder**

    - Gebruikersnaam: `admin`
    - Wachtwoord: `admin123`
    - Toegang tot alle functionaliteiten

2. **Penningmeester**

    - Gebruikersnaam: `penningmeester`
    - Wachtwoord: `penningmeester123`
    - Toegang tot contributies

3. **Secretaris**
    - Gebruikersnaam: `secretaris`
    - Wachtwoord: `secretaris123`
    - Toegang tot ledenbeheer

## Systeemvereisten

-   PHP 7.4 of hoger
-   MySQL 5.7 of hoger
-   Apache webserver
-   Moderne webbrowser
