<?php
namespace App\Services;

use GuzzleHttp\Client;

class FreesoundService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://freesound.org/apiv2/',
            'timeout'  => 10.0, 
        ]);
    }

    /**
     * Función que se conecta para obtener un sonido
     */
    public function getSoundUrl($query)
    {

        /* Hacemos la solicitud GET a la API de Freesound */
        $response = $this->client->get('search/text/', [
            'query' => [
                'query' => $query,
                'token' => env('FREESOUND_API_KEY')
            ]
        ]);

        /* Guardamos el resultado de la solicitud */
        $sounds = json_decode($response->getBody(), true);

        /* Si no hay resultados, devolvemos null */
        if (!isset($sounds['results']) || empty($sounds['results'])) {
            return null;
        }

        /* Elegimos un sonido aleatorio de los resultados */
        $randomSound = $sounds['results'][array_rand($sounds['results'])];

        /* Pedimos los detalles del sonido seleccionado */
        $response = $this->client->get("sounds/{$randomSound['id']}/", [
            'query' => ['token' => env('FREESOUND_API_KEY')]
        ]);

        /* Guaardamos los detalles */
        $soundDetails = json_decode($response->getBody(), true);

        /* Devolvemos la URL del sonido en alta calidad (o baja si no está disponible) */
        return $soundDetails['previews']['preview-hq-mp3'] ?? $soundDetails['previews']['preview-lq-mp3'] ?? null;
    }
}
