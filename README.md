# NBA Games CRUD Project

## Beschrijving

Voor dit project heb ik een webapplicatie gemaakt in PHP waarbij gegevens van een externe NBA API worden opgehaald. De ontvangen data wordt opgeslagen in een MySQL-database en vervolgens weergegeven op de website.

De applicatie maakt gebruik van CRUD-functionaliteiten (Create, Read, Update, Delete), zodat wedstrijden kunnen worden bekeken, toegevoegd, aangepast en verwijderd.

## Functionaliteiten

* Verbinding maken met een externe NBA API.
* Data ophalen van de API.
* Gegevens opslaan in een MySQL-database.
* Overzicht tonen van alle opgeslagen wedstrijden.
* Nieuwe wedstrijden toevoegen.
* Bestaande wedstrijden bewerken.
* Wedstrijden verwijderen.
* Gebruik van PDO voor veilige databaseverbindingen.
* Responsive interface met Bootstrap.

## Gebruikte Technologieën

* PHP
* MySQL
* PDO
* HTML
* Bootstrap 5

## CRUD Overzicht

### Create

Nieuwe wedstrijdgegevens kunnen worden toegevoegd aan de database.

### Read

Alle opgeslagen wedstrijden worden opgehaald uit de database en weergegeven op de website.

### Update

Bestaande wedstrijdgegevens kunnen worden aangepast.

### Delete

Wedstrijden kunnen uit de database worden verwijderd.

## Doel van het Project

Het doel van dit project is het leren werken met API's, databases en CRUD-operaties binnen een PHP-webapplicatie. Daarnaast wordt ervaring opgedaan met het verwerken en beheren van externe data.

## Database prompt

CREATE DATABASE IF NOT EXISTS nba;
USE nba;

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_id VARCHAR(50) NOT NULL UNIQUE,
    game_date DATE NOT NULL,
    arena VARCHAR(255),
    game_duration VARCHAR(20)
);

CREATE TABLE teams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    home_team VARCHAR(50) NOT NULL,
    visitor_team VARCHAR(50) NOT NULL,
    home_pts INT NOT NULL,
    visitor_pts INT NOT NULL
);

## URL die gebruikt moet worden

http://localhost/Web_Database3/Extern_database/index.php
