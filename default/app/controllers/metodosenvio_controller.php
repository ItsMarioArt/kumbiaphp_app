<?php
/*
 * file:  app/controllers/metodos_envio_controller.php
 * class: MetodosEnvioController
 */
class MetodosEnvioController extends AppController {

    /*
     * accion: index
     * vista: app/views/metodos_envio/index.phtml
     * url: /metodos_envio
     */
    public function index() {
        // Llamamos a la clase MetodosEnvio sin guion bajo
        $this->metodos_envio = (new MetodosEnvio)->find();
    }

    /*
     * accion: ver
     * vista: app/views/metodos_envio/ver.phtml
     * url: /metodos_envio/ver/:id
     */
    public function ver($id) {
        // La variable se llama metodo_envio (con guion bajo)
        $this->metodo_envio = (new MetodosEnvio)->find($id);
    }

    /*
     * accion: editar
     * vista: app/views/metodosenvio/editar.phtml
     * url: /metodosenvio/editar/:id
     */
    public function editar($id){
        $this->metodo_envio = (new MetodosEnvio)->find((int) $id);

        if (!$this->metodo_envio) {
            Flash::error('El método de envío no existe');
            Redirect::to('metodosenvio');
            return;
        }

        $this->setPage("Editar: {$this->metodo_envio->nombre}", 'Modifica los datos del método de envío', [
            ['label' => 'Inicio', 'url' => URL],
            ['label' => 'Métodos de envío', 'url' => URL . 'metodosenvio'],
            ['label' => 'Editar']
        ]);

        if (Input::hasPost('metodo_envio.nombre')) {
            $params = Input::post('metodo_envio');

            $this->metodo_envio->nombre         = trim($params['nombre']);
            $this->metodo_envio->costo          = trim($params['costo']);
            $this->metodo_envio->tiempo_entrega = trim($params['tiempo_entrega']);

            if ($this->metodo_envio->nombre === '' || $this->metodo_envio->tiempo_entrega === '') {
                Flash::error('El nombre y el tiempo de entrega son obligatorios');
                return;
            }

            if (!is_numeric($this->metodo_envio->costo) || $this->metodo_envio->costo < 0) {
                Flash::error('El costo debe ser un número mayor o igual a 0');
                return;
            }

            try {
                $guardado = $this->metodo_envio->save();
            } catch (Throwable $e) {
                $guardado = false;
            }

            if ($guardado) {
                Flash::valid('Método de envío actualizado correctamente');
            } else {
                Flash::error('No se pudo guardar el método de envío');
            }
        }
    }

    public function crear(){
        if (Input::hasPost('metodos_envio')) {
            $params = Input::post('metodos_envio');
            $this->metodo_envio = new MetodosEnvio($params);


            if ($this->metodo_envio->create()) {
                Flash::valid('Método de envío creado correctamente');
            } else {
                Flash::error('No se pudo crear el método de envio');
            }

        }
    }
}