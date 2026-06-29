<?php

// Definitie van de klasse ApiService
class ApiService
{
    // Basis-URL van de NBA API
    private $baseUrl = "https://site.api.espn.com/apis/site/v2/sports/basketball/nba/scoreboard";

    // Haalt alle wedstrijden op uit de API
    public function getGames(): array
    {
        // Vraagt de JSON-data op van het endpoint 'games'
        $json = file_get_contents($this->baseUrl . "games");

        // Controleert of de API een geldig antwoord heeft gegeven
        if (!$json) {
            // Geeft een lege array terug als de aanvraag mislukt
            return [];
        }

        // Zet de JSON-data om naar een PHP-array en geeft deze terug
        return json_decode($json, true);
    }
}