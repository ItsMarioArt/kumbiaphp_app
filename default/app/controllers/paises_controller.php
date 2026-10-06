<?php

/*
 * file:  paises_controller.php
 * class: PaisesController
 *
 */
class PaisesController extends AppController{
    public function items(){
        return [
            1 => (object) ['id' => 1, 'nombre' => 'México', 'capital' => 'Ciudad de México', 'codigo_iso' => 'MX', 'continente' => 'América', 'moneda' => 'Peso (MXN)', 'poblacion' => 128900000],
            2 => (object) ['id' => 2, 'nombre' => 'Argentina', 'capital' => 'Buenos Aires', 'codigo_iso' => 'AR', 'continente' => 'América', 'moneda' => 'Peso (ARS)', 'poblacion' => 45700000],
            3 => (object) ['id' => 3, 'nombre' => 'Colombia', 'capital' => 'Bogotá', 'codigo_iso' => 'CO', 'continente' => 'América', 'moneda' => 'Peso (COP)', 'poblacion' => 52000000],
            4 => (object) ['id' => 4, 'nombre' => 'España', 'capital' => 'Madrid', 'codigo_iso' => 'ES', 'continente' => 'Europa', 'moneda' => 'Euro (EUR)', 'poblacion' => 48000000],
            5 => (object) ['id' => 5, 'nombre' => 'Estados Unidos', 'capital' => 'Washington D.C.', 'codigo_iso' => 'US', 'continente' => 'América', 'moneda' => 'Dólar (USD)', 'poblacion' => 335000000],
            6 => (object) ['id' => 6, 'nombre' => 'Brasil', 'capital' => 'Brasilia', 'codigo_iso' => 'BR', 'continente' => 'América', 'moneda' => 'Real (BRL)', 'poblacion' => 216000000],
            7 => (object) ['id' => 7, 'nombre' => 'Japón', 'capital' => 'Tokio', 'codigo_iso' => 'JP', 'continente' => 'Asia', 'moneda' => 'Yen (JPY)', 'poblacion' => 124000000],
            8 => (object) ['id' => 8, 'nombre' => 'Alemania', 'capital' => 'Berlín', 'codigo_iso' => 'DE', 'continente' => 'Europa', 'moneda' => 'Euro (EUR)', 'poblacion' => 84000000],
        ];
    }
    /*
     * accion: index
     * vista: app/views/paises/index.phtml
     * url: /paises
     */
    public function index(){
        $this->paises = $this->items();
    }
    /*
     * accion: ver
     * vista: paises/ver.phtml
     * url: /paises/ver/:id
     */
    public function ver($id){


        $this->paises = $this->items()[$id];
    }
}