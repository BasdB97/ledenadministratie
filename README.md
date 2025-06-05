# Ledenadministratie

'Ledenadministratie' is een PHP & MySQL CRUD-applicatie voor het beheren van leden en contributies voor een vereniging. Dit project is gemaakt als eindopdracht voor de module PHP & MySQL van de opleiding Front-End Developer van LOI. In de applicatie kunnen families en leden worden toegevoegd, gewijzigd en verwijderd. Per lid wordt er contributie toegevoegd, dit is gekoppeld aan een boekjaar.
Er zijn 3 accounts beschikbaar met elk hun eigen toegangsniveau. De admin heeft alle rechten, de penningmeester heeft alleen toegang tot de contributies en de secretaris heeft alleen toegang tot het ledenbeheer.
De applicatie biedt een gebruiksvriendelijke interface waarmee de gebruiker makkelijk leden en contributies kan toevoegen, wijzigen en verwijderen.
Ook heeft de applicatie enkele beveiligingsfunctionaliteiten zoals het controleren en sanitizen van ingevulde gegevens, hashen van wachtwoorden, routing en een authenticatie systeem.

Omdat er in dit project niet alle beveiligingsfunctionaliteiten zijn toegepast, is het niet geschikt voor productie maar enkel voor lokaal gebruik.

## Installatie

1. Zorg ervoor dat je XAMPP (of vergelijkbare stack) hebt geïnstalleerd met PHP en MySQL
2. Clone deze repository naar je `htdocs` map:
    ```bash
    git clone https://github.com/BasdB97/ledenadministratie
    ```
3. Importeer de database:

    - Open phpMyAdmin (http://localhost/phpmyadmin)
    - Maak een nieuwe database aan genaamd `ledenadministratie`
    - Importeer het `creatiescript.sql` bestand uit de `database` map
    - Importeer het `data.sql` bestand uit de `database` map

4. Start je webserver en database
5. Bezoek de applicatie via: http://localhost/ledenadministratie

## Apache Configuratie

Om de .htaccess bestanden correct te laten werken, moet je de volgende Apache modules en instellingen activeren:

1. Open het Apache configuratiebestand (`httpd.conf`) in je XAMPP installatie
2. Zorg ervoor dat de volgende modules zijn geactiveerd (verwijder het # teken indien aanwezig):
    ```
    LoadModule rewrite_module modules/mod_rewrite.so
    ```
3. Zoek de `<Directory>` sectie voor je htdocs map en zorg ervoor dat `AllowOverride` is ingesteld op `All`:
    ```
    <Directory "C:/xampp/htdocs">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted
    </Directory>
    ```
4. Herstart Apache na het maken van deze wijzigingen

Zonder deze configuratie zullen de URL-rewriting en andere .htaccess functionaliteiten niet werken.

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
