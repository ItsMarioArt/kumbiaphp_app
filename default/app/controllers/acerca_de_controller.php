<?php

/*
 * file:  acerca_de_controller.php
 * class: AcercaDeController
 *
 * accion: index
 * vista:  app/views/acerca_de/index.phtml
 * url:    /acerca_de
 */
class AcercaDeController extends AppController
{
    public function index()
    {
        $this->title = 'Acerca de';

        // Datos del autor (edítalos aquí si algo cambia)
        $this->autor = (object) [
            'nombre'         => 'Mario Alberto Arteaga Argueta',
            'numero_control' => '23590231',
            'carrera'        => 'Ingeniería en Sistemas Computacionales (I.S.C.)',
            'correo'         => 'l23590231@sjuanrio.tecnm.mx',
            // Ruta de la foto dentro de public/ (imagen cuadrada)
            'foto'           => 'img/perfil.jpg',
        ];
    }
}
