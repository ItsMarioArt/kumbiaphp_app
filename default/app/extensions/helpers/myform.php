<?php
/**
 * KumbiaPHP web & app Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 *
 * @category   KumbiaPHP
 * @package    Helpers
 *
 * @copyright  Copyright (c) 2005 - 2023 KumbiaPHP Team (http://www.kumbiaphp.com)
 * @license    https://github.com/KumbiaPHP/KumbiaPHP/blob/master/LICENSE   New BSD License
 */

/**
 * Helper con utilidades para generar formularios rápidos con estilos de
 * Bootstrap, sin escribir las clases CSS a mano en cada campo.
 *
 * Se apoya en el helper Form del core y le agrega las clases
 * "form-control" / "btn btn-primary" automáticamente.
 *
 * Uso típico en una vista .phtml:
 *   <?= Myform::text_label("Email", "usuario.email") ?>
 *   <?= Myform::btn_aceptar("Guardar") ?>
 *
 * @category   KumbiaPHP
 * @package    Helpers
 */
class Myform
{
    /**
     * Mezcla los atributos pasados por el usuario con los que vienen por
     * defecto. Si el atributo ya existe no lo pisa; solo agrega el valor
     * por defecto al final (para no perder clases CSS que ya trajera).
     *
     * @param array $attrs    Atributos dados por el usuario
     * @param array $defaults Atributos por defecto (ej. ['class' => 'form-control'])
     *
     * @return array
     */
    protected static function attrsdefaut($attrs, $defaults)
    {
        foreach ($defaults as $k => $v) {
            if (isset($attrs[$k])) {
                if (strpos($attrs[$k], $v) === false) {
                    $attrs[$k] .= ' ' . $v;
                }
            } else {
                $attrs[$k] = $v;
            }
        }
        return $attrs;
    }

    /**
     * Crea un botón de envío (submit) con estilo primario de Bootstrap
     * y un icono ✅. El valor por defecto del texto es "Aceptar".
     *
     * @example Myform::btn_aceptar("Aceptar")
     * @example Myform::btn_aceptar("Guardar", ["class" => "btn-lg"])
     *
     * @param string $text  Texto del botón
     * @param array  $attrs Atributos adicionales (mezclados con los por defecto)
     *
     * @return string HTML del botón submit
     */
    public static function btn_aceptar($text = "Aceptar", $attrs = [])
    {
        $text = " " . $text;
        $attrs = Myform::attrsdefaut($attrs, ["class" => "btn btn-success"]);
        return Form::submit($text, $attrs);
    }

    /**
     * Campo de texto con la clase "form-control" de Bootstrap.
     * Si no se pasa $value, lo toma del modelo/entidad del formulario.
     *
     * @example Myform::text("usuario.nombre")
     * @example Myform::text("usuario.nombre", ["placeholder" => "Tu nombre"])
     *
     * @param string      $field Nombre del campo
     * @param array       $attrs Atributos adicionales del input
     * @param string|null $value Valor del campo (null = tomarlo del modelo)
     *
     * @return string HTML del input
     */
    public static function text($field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        return Form::input('text', $field, $attrs, $value);
    }

    /**
     * Campo de texto con su etiqueta <label>, siguiendo esta estructura:
     *
     *   <label>Email</label>
     *   <input type="text" class="form-control" ...>
     *
     * @example Myform::text_label("Email", "usuario.email")
     * @example Myform::text_label("Email", "usuario.email", ["placeholder" => "correo@ejemplo.com"])
     *
     * @param string      $label Texto de la etiqueta
     * @param string      $field Nombre del campo
     * @param array       $attrs Atributos adicionales del input
     * @param string|null $value Valor del campo (null = tomarlo del modelo)
     *
     * @return string HTML del label + input
     */
    public static function text_label($label, $field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        $html =  "<label>$label</label>";
        return $html . Form::input('text', $field, $attrs, $value);
    }

    /**
     * Campo numérico con su etiqueta <label>, misma estructura que text_label.
     *
     * @example Myform::number_label("Stock", "producto.stock")
     * @example Myform::number_label("Precio", "producto.precio", ["readonly" => "readonly"])
     *
     * @param string      $label Texto de la etiqueta
     * @param string      $field Nombre del campo
     * @param array       $attrs Atributos adicionales del input
     * @param string|null $value Valor del campo (null = tomarlo del modelo)
     *
     * @return string HTML del label + input
     */
    public static function number_label($label, $field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        $html =  "<label>$label</label>";
        return $html . Form::input('number', $field, $attrs, $value);
    }

    /**
     * Lista desplegable (select) con su etiqueta <label>. Recibe el arreglo
     * de registros (por ejemplo (new Categorias)->get_activos()) y deja
     * seleccionado el valor que tenga el modelo en $field.
     *
     * @example Myform::select_label("Marca", "producto.marca_id", $marcas)
     *
     * @param string      $label Texto de la etiqueta
     * @param string      $field Nombre del campo
     * @param array       $data  Registros que llenan las opciones (usan "id" como valor)
     * @param array       $attrs Atributos adicionales del select
     * @param string|null $value Valor seleccionado (null = tomarlo del modelo)
     * @param string      $show  Propiedad de cada registro que se muestra como texto
     *
     * @return string HTML del label + select
     */
    public static function select_label($label, $field, $data, $attrs = [], $value = null, $show = 'nombre')
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-select"]);
        $html =  "<label>$label</label>";
        return $html . Form::select($field, $data, $attrs, $value, '', 'id', $show);
    }





    public static function open($action = '', $method = 'post', $attrs = '')
    {
        return Form::open($action = '', $method = 'post', $attrs = '');
    }
    public static function close(){
        return Form::close();
    }


    /**
     * Área de texto con su etiqueta <label>, misma estructura que text_label.
     *
     * @example Myform::textarea_label("Descripción", "categoria.descripcion")
     *
     * @param string      $label Texto de la etiqueta
     * @param string      $field Nombre del campo
     * @param array       $attrs Atributos adicionales del textarea
     * @param string|null $value Valor del campo (null = tomarlo del modelo)
     *
     * @return string HTML del label + textarea
     */
    public static function textarea_label($label, $field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        $attrs += ["rows" => "4"];
        $html =  "<label>$label</label>";
        return $html . Form::textarea($field, $attrs, $value);
    }

    /* Crear los metodos para
    Estructura del formulario
1 open($action, $method, $attrs) — abre <form>
2. openMultipart($action, $attrs) — abre formulario con enctype="multipart/form-data"
3. close() — cierra </form>
Botones
4. submit($text, $attrs) — botón de envío
5. reset($text, $attrs) — botón de limpiar
6. button($text, $attrs, $type, $value) — botón genérico
7. submitImage($img, $attrs) — imagen tipo submit
Campos de entrada
8. input($type, $field, $attrs, $value) — input genérico (base de los demás)
9. text($field, $attrs, $value)
10. hidden($field, $attrs, $value)
11. pass($field, $attrs, $value) — alias de password
12. password($field, $attrs, $value)
13. file($field, $attrs)
14. textarea($field, $attrs, $value)
15. label($text, $field, $attrs)
Tipos HTML5
16. date($field, $attrs, $value)
17. time($field, $attrs, $value)
18. datetime($field, $attrs, $value)
19. number($field, $attrs, $value)
20. url($field, $attrs, $value)
21. email($field, $attrs, $value)
22. datepicker($field, $class, $attrs, $value)
    */
}
