# Ledenadministratie

Een PHP-gebaseerde webapplicatie voor het beheren van leden, contributies en jaaroverzichten voor een vereniging.

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
    git clone https://github.com/BasdB97/ledenadministratie_final.git
    ```
3. Importeer de database:

    - Open phpMyAdmin (http://localhost/phpmyadmin)
    - Maak een nieuwe database aan genaamd `ledenadministratie`
    - Importeer het `database.sql` bestand uit de `database` map

4. Configureer de database connectie:

    - Kopieer `.env.example` naar `.env`
    - Pas de database instellingen aan indien nodig

5. Start je webserver en database
6. Bezoek de applicatie via: http://localhost/ledenadministratie_final

## Gebruikersaccounts

De applicatie komt met drie vooraf ingestelde accounts:

1. **Beheerder**

    - Gebruikersnaam: `admin`
    - Wachtwoord: `admin123`
    - Toegang tot alle functionaliteiten

2. **Penningmeester**

    - Gebruikersnaam: `penningmeester`
    - Wachtwoord: `penning123`
    - Toegang tot contributies en jaaroverzichten

3. **Secretaris**
    - Gebruikersnaam: `secretaris`
    - Wachtwoord: `secretaris123`
    - Toegang tot ledenbeheer

## Systeemvereisten

-   PHP 7.4 of hoger
-   MySQL 5.7 of hoger
-   Apache/Nginx webserver
-   Moderne webbrowser

## Veiligheid

Voor productiegebruik wordt sterk aangeraden om:

1. De standaard wachtwoorden te wijzigen
2. HTTPS te configureren
3. De `.env` file buiten de webroot te plaatsen
4. Regelmatig backups te maken van de database
