<?php

class ApiService
{
    private $baseUrl = "https://api.server.nbaapi.com/api/";

    public function getGames(): array
    {
        $json = file_get_contents($this->baseUrl . "games");

        if (!$json) {
            return [];
        }

        return json_decode($json, true);
    }
}