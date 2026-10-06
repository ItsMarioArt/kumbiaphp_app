<?php
/*
 * file:  app/controllers/categorias_controller.php
 * class: CategoriasController
 */
class CategoriasController extends AppController {

    /*
     * accion: index
     * vista: app/views/categorias/index.phtml
     * url: /categorias
     */
    public function index() {
        $this->categorias = (new Categorias)->find();
    }

    /*
     * accion: ver
     * vista: app/views/categorias/ver.phtml
     * url: /categorias/ver/:id
     */
    public function ver($id) {
        $this->categoria = (new Categorias)->find($id);
    }
}