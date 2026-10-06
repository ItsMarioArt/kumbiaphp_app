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

    public function editar($id){
        $this->metodo_envio = (new MetodosEnvio) -> find($id);

        if(Input::hasPost('metodos_envio.nombre')){
            $params = Input::post('metodos_envio');
            $this->metodo_envio->nombre = $params['nombre'];
            $this->metodo_envio->costo = $params['costo'];
            $this->metodo_envio->tiempo_entrega = $params['tiempo_entrega'];

            if ($this->metodo_envio->save()) {
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