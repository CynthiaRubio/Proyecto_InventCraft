<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FreeSoundServiceInterface;
use App\Models\Zone;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class FreesoundService implements FreeSoundServiceInterface
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
     * Obtiene la URL del sonido asociado a una zona
     * Utiliza la sesión para cachear el sonido y evitar múltiples llamadas a la API
     * 
     * @param Zone $zone Zona de la que obtener el sonido
     * @return string|null URL del sonido o null si no se encontró
     */
    public function getSound(Zone $zone): ?string
    {

        $zoneSounds = [
            'Pradera' => 'meadow birds',
            'Bosque' => 'forest',
            'Selva' => 'jungle birds',
            'Desierto' => 'desert wind',
            'Montaña' => 'mountain wind',
            'Lagos' => 'lake water',
            'Polo Norte' => 'arctic wind',
            'Glaciar de Montaña' => 'glacier ice',
            'Polo Sur' => 'antarctica'
        ];
        $soundQuery = $zoneSounds[$zone->name] ?? 'nature ambience'; 

        /* Comprobamos si ya hay sonido guardado en la sesion */
        if (Session::has('zonesound' . $zone->id)) {
            $sound_url = Session::get('zonesound' . $zone->id);
        
        } else {
            /* Sino, buscamos el sonido en la api */
            $sound_url = $this->getSoundUrl($soundQuery);
            Session::put('zonesound' . $zone->id, $sound_url);
        }

        return $sound_url;

    }
    
    
    
    /**
     * Se conecta a la API de Freesound para obtener un sonido según la consulta
     * Busca sonidos, selecciona uno aleatorio y devuelve su URL de preview
     * 
     * @param string $query Término de búsqueda para el sonido
     * @return string|null URL del preview del sonido (alta calidad preferida) o null si no hay resultados
     */
    public function getSoundUrl(string $query): ?string
    {

        /* Hacemos la solicitud GET a la API de Freesound */
        $response = $this->client->get('search/text/', [
            'query' => [
                'query' => $query,
                'token' => env('FREESOUND_API_KEY')
            ]
        ]);

        /* Guardamos el resultado de la solicitud */
        $sounds = json_decode((string) $response->getBody(), true);

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

        /* Guardamos los detalles */
        $soundDetails = json_decode((string) $response->getBody(), true);

        /* Devolvemos la URL del sonido en alta calidad (o baja si no está disponible) */
        return $soundDetails['previews']['preview-hq-mp3'] ?? $soundDetails['previews']['preview-lq-mp3'] ?? null;
    }
}
