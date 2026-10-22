<?php

/*
 * file:  paises_controller.php
 * class: PaisesController
 *
 */
class PaisesController extends AppController{
    /*
     * accion: index
     * vista: app/views/paises/index.phtml
     * url: /paises
     */
    public function index(){
        $this->paises = (new Paises)->find();
    }

    /*
     * accion: ver
     * vista: paises/ver.phtml
     * url: /paises/ver/:id
     */
    public function ver($id){
        $this->pais = (new Paises)->find((int) $id);

        if (!$this->pais) {
            Flash::error('El país no existe');
            Redirect::to('paises');
        }
    }

    /*
     * accion: editar
     * vista: paises/editar.phtml
     * url: /paises/editar/:id
     */
    public function editar($id){
        $this->pais = (new Paises)->find((int) $id);

        if (!$this->pais) {
            Flash::error('El país no existe');
            Redirect::to('paises');
            return;
        }

        $this->setPage("Editar: {$this->pais->nombre}", 'Modifica los datos del país', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Países', 'url' => URL . 'paises'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('pais.nombre')) {
            $params = Input::post('pais');

            $this->pais->nombre     = trim($params['nombre']);
            $this->pais->capital    = trim($params['capital']);
            $this->pais->codigo_iso = strtoupper(trim($params['codigo_iso']));
            $this->pais->continente = trim($params['continente']);
            $this->pais->moneda     = trim($params['moneda']);
            $this->pais->poblacion  = trim($params['poblacion']);

            if ($this->pais->nombre === '' || $this->pais->capital === '' ||
                $this->pais->continente === '' || $this->pais->moneda === '') {
                Flash::error('Nombre, capital, continente y moneda son obligatorios');
                return;
            }

            if (!preg_match('/^[A-Z]{2}$/', $this->pais->codigo_iso)) {
                Flash::error('El código ISO debe tener 2 letras (por ejemplo MX)');
                return;
            }

            if (!ctype_digit((string) $this->pais->poblacion)) {
                Flash::error('La población debe ser un número entero mayor o igual a 0');
                return;
            }

            try {
                $guardado = $this->pais->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if ($guardado) {
                Flash::valid('País actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el país. Revisa que el código ISO no esté repetido');
            }
        }
    }
}