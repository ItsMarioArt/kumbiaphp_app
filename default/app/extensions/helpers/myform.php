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





    /**
     * Abre el <form>. Si $action va vacío envía a la misma URL.
     *
     * @example Myform::open()
     * @example Myform::open("", "post", ["class" => "form-edicion"])
     *
     * @param string       $action Acción (ruta de la aplicación), opcional
     * @param string       $method Método HTTP, por defecto post
     * @param string|array $attrs  Atributos del <form>
     *
     * @return string
     */
    public static function open($action = '', $method = 'post', $attrs = [])
    {
        return Form::open($action, $method, $attrs);
    }

    /**
     * Abre un <form> con enctype multipart/form-data (necesario para file).
     *
     * @example Myform::openMultipart("", ["class" => "form-edicion"])
     *
     * @return string
     */
    public static function openMultipart($action = null, $attrs = [])
    {
        return Form::openMultipart($action, $attrs);
    }

    /**
     * Cierra el <form>.
     *
     * @return string
     */
    public static function close()
    {
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

    /**
     * Input genérico con la clase "form-control". Es la base de email,
     * date, time, datetime, url, number y demás tipos.
     *
     * @example Myform::input("email", "usuario.email")
     *
     * @param string      $type  Tipo del input (text, email, date...)
     * @param string      $field Nombre del campo
     * @param array       $attrs Atributos adicionales
     * @param string|null $value Valor (null = tomarlo del modelo)
     *
     * @return string HTML del input
     */
    public static function input($type, $field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        return Form::input($type, $field, $attrs, $value);
    }

    /**
     * Input genérico con su etiqueta <label>.
     *
     * @example Myform::input_label("Correo", "email", "usuario.email")
     *
     * @return string HTML del label + input
     */
    public static function input_label($label, $type, $field, $attrs = [], $value = null)
    {
        return "<label>$label</label>" . Myform::input($type, $field, $attrs, $value);
    }

    /**
     * Campo de correo (type="email"). Con _label agrega la etiqueta.
     *
     * @example Myform::email("cliente.email")
     * @example Myform::email_label("Correo electrónico", "cliente.email")
     */
    public static function email($field, $attrs = [], $value = null)
    {
        return Myform::input('email', $field, $attrs, $value);
    }

    public static function email_label($label, $field, $attrs = [], $value = null)
    {
        return Myform::input_label($label, 'email', $field, $attrs, $value);
    }

    /**
     * Calendario nativo del navegador (type="date"). Con _label agrega la
     * etiqueta. El valor debe ir en formato AAAA-MM-DD.
     *
     * @example Myform::date_label("Fecha de nacimiento", "perfil.fecha_nacimiento")
     */
    public static function date($field, $attrs = [], $value = null)
    {
        return Myform::input('date', $field, $attrs, $value);
    }

    public static function date_label($label, $field, $attrs = [], $value = null)
    {
        return Myform::input_label($label, 'date', $field, $attrs, $value);
    }

    /**
     * Hora nativa (type="time").
     *
     * @example Myform::time_label("Hora", "cita.hora")
     */
    public static function time($field, $attrs = [], $value = null)
    {
        return Myform::input('time', $field, $attrs, $value);
    }

    public static function time_label($label, $field, $attrs = [], $value = null)
    {
        return Myform::input_label($label, 'time', $field, $attrs, $value);
    }

    /**
     * Fecha y hora nativas (type="datetime-local"). El valor debe ir en
     * formato AAAA-MM-DDTHH:MM.
     *
     * @example Myform::datetime_label("Entrega", "orden.entrega")
     */
    public static function datetime($field, $attrs = [], $value = null)
    {
        return Myform::input('datetime-local', $field, $attrs, $value);
    }

    public static function datetime_label($label, $field, $attrs = [], $value = null)
    {
        return Myform::input_label($label, 'datetime-local', $field, $attrs, $value);
    }

    /**
     * Campo de dirección web (type="url").
     *
     * @example Myform::url_label("Sitio web", "marca.sitio")
     */
    public static function url($field, $attrs = [], $value = null)
    {
        return Myform::input('url', $field, $attrs, $value);
    }

    public static function url_label($label, $field, $attrs = [], $value = null)
    {
        return Myform::input_label($label, 'url', $field, $attrs, $value);
    }

    /**
     * Campo numérico sin etiqueta (con etiqueta ver number_label).
     *
     * @example Myform::number("producto.stock", ["min" => "0"])
     */
    public static function number($field, $attrs = [], $value = null)
    {
        return Myform::input('number', $field, $attrs, $value);
    }

    /**
     * Área de texto sin etiqueta (con etiqueta ver textarea_label).
     *
     * @example Myform::textarea("categoria.descripcion")
     */
    public static function textarea($field, $attrs = [], $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        $attrs += ["rows" => "4"];
        return Form::textarea($field, $attrs, $value);
    }

    /**
     * Select sin etiqueta (con etiqueta ver select_label).
     *
     * @example Myform::select("producto.marca_id", $marcas)
     */
    public static function select($field, $data, $attrs = [], $value = null, $show = 'nombre')
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-select"]);
        return Form::select($field, $data, $attrs, $value, '', 'id', $show);
    }

    /**
     * Campo oculto (hidden).
     *
     * @example Myform::hidden("producto.id")
     */
    public static function hidden($field, $attrs = [], $value = null)
    {
        return Form::hidden($field, $attrs, $value);
    }

    /**
     * Campo de contraseña. Siempre sale vacío: no carga el valor del modelo
     * para no mostrar el hash guardado en el HTML.
     *
     * @example Myform::password_label("Contraseña", "usuario.password")
     */
    public static function password($field, $attrs = [])
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        $parts = explode('.', $field, 2);
        $id    = isset($parts[1]) ? "{$parts[0]}_{$parts[1]}" : $parts[0];
        $name  = isset($parts[1]) ? "{$parts[0]}[{$parts[1]}]" : $parts[0];
        return "<input id=\"$id\" name=\"$name\" type=\"password\" value=\"\" " . Tag::getAttrs($attrs) . "/>";
    }

    public static function password_label($label, $field, $attrs = [])
    {
        return "<label>$label</label>" . Myform::password($field, $attrs);
    }

    public static function pass($field, $attrs = [])
    {
        return Myform::password($field, $attrs);
    }

    /**
     * Campo para subir archivos. El formulario debe abrirse con
     * Myform::openMultipart().
     *
     * @example Myform::file_label("Logo", "marca.logo")
     */
    public static function file($field, $attrs = [])
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "form-control"]);
        return Form::file($field, $attrs);
    }

    public static function file_label($label, $field, $attrs = [])
    {
        return "<label>$label</label>" . Myform::file($field, $attrs);
    }

    /**
     * Etiqueta <label> ligada a un campo (con atributo for).
     *
     * @example Myform::label("Nombre", "producto.nombre")
     */
    public static function label($text, $field, $attrs = [])
    {
        return Form::label($text, $field, $attrs);
    }

    /**
     * Botón de limpiar el formulario (reset).
     *
     * @example Myform::reset("Limpiar")
     */
    public static function reset($text = "Limpiar", $attrs = [])
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "btn btn-outline-secondary"]);
        return Form::reset(" " . $text, $attrs);
    }

    /**
     * Botón genérico (por defecto type="button").
     *
     * @example Myform::button("Cancelar", ["class" => "btn-lg"])
     */
    public static function button($text, $attrs = [], $type = 'button', $value = null)
    {
        $attrs = Myform::attrsdefaut($attrs, ["class" => "btn btn-secondary"]);
        return Form::button($text, $attrs, $type, $value);
    }

    /**
     * Botón de envío con imagen (la imagen se busca en public/img/).
     *
     * @example Myform::submit_image("enviar.png")
     */
    public static function submit_image($img, $attrs = [])
    {
        return Form::submitImage($img, $attrs);
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
