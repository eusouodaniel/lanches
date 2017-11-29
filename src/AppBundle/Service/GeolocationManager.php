<?php

namespace AppBundle\Service;

use Google\GeolocationBundle\Geolocation\GeolocationApi;

class GeolocationManager {

    protected $geolocationApi;

    /**
     * Construtor padrão.
     */
    public function __construct(GeolocationApi $geolocationApi) {
        $this->geolocationApi = $geolocationApi;
    }

    /**
     * Método que retorna a latitude e longitude a partir de um endereço.
     * @param string $address Endereço a ser buscado.
     * @return array Array contendo latitude e longitude.
     */
    public function getLatLng($address) {
        $location = $this->geolocationApi->locateAddress($address);

        if ($location->getMatches() > 0) {
            return $location->getLatLng(0);
        } else {
            return null;
        }
    }

}
