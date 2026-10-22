<?php

/*
 * file:  marcas_controller.php
 * class: MarcasController
 *
 */
class MarcasController extends AppController{

    /*
     * accion: index
     * vista: app/views/marcas/index.phtml
     * url: /marcas
     */
    public function index(){
        $this->marcas = (new Marcas) -> find();
    }
    /*
     * accion: ver
     * vista: marcas/ver.phtml
     * url: /marcas/ver/:id
     */
    public function ver($id){
        $this->marca = (new Marcas) -> find($id);
    }

    /*
     * accion: editar
     * vista: marcas/editar.phtml
     * url: /marcas/editar/:id
     */
    public function editar($id){
        $this->marca = (new Marcas) -> find((int) $id);

        if (!$this->marca) {
            Flash::error('La marca no existe');
            Redirect::to('marcas');
            return;
        }

        $this->setPage("Editar: {$this->marca->nombre}", 'Modifica los datos de la marca', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Marcas', 'url' => URL . 'marcas'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('marca.nombre')) {
            $params = Input::post('marca');

            $this->marca->nombre = trim($params['nombre']);
            $this->marca->slug   = trim($params['slug'] ?? $this->marca->slug);

            if ($this->marca->nombre === '') {
                Flash::error('El nombre es obligatorio');
                return;
            }

            try {
                $guardada = $this->marca->save();
            } catch (Throwable $e) {
                $guardada = false;
            }

            if ($guardada) {
                Flash::valid('Marca actualizada correctamente');
            } else {
                Flash::error('No se pudo guardar la marca. Revisa que el slug no esté repetido');
            }
        }
    }

    /*
     * accion: eliminar
     * url: /marcas/eliminar/:id (solo por POST)
     * No borra el registro: lo pone inactivo (activo = 0).
     * Todavia no hay ningun boton conectado a esta accion.
     */
    public function eliminar($id){
        if (!Input::is('POST')) {
            Redirect::to('marcas/ver/' . (int) $id);
            return;
        }

        $marca = (new Marcas) -> find((int) $id);

        if (!$marca) {
            Flash::error('La marca no existe');
            Redirect::to('marcas');
            return;
        }

        $marca->activo = 0;

        try {
            $desactivada = $marca->save();
        } catch (Throwable $e) {
            $desactivada = false;
        }

        if (!$desactivada) {
            Flash::error('No se pudo desactivar la marca');
            Redirect::to('marcas/ver/' . (int) $id);
            return;
        }

        Flash::valid('Marca desactivada correctamente');
        Redirect::to('marcas');
    }
}