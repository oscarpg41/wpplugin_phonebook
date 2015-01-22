<?php
/*
Plugin Name: OPG Phone Book
Plugin URI: http://www.oscarperez.es/wordpress-plugins/opg_phonebook.zip
Description: This PhoneBook plugin helps to manage the phone book easily over the WordPress blog. This phonebook have three fields: idPhone, name and phone
Author: Oskar Pérez
Author URI: http://www.oscarperez.es/
Version: 1.1
License: GPLv2
*/
?>
<?php

    //registramos el fichero js que necesitamos
    //wp_register_script('myPhoneBookScript', WP_PLUGIN_URL . '/opg_phonebook/opg_phonebook.js');
    wp_register_script('myPhoneBookScript', WP_PLUGIN_URL .'/opg_phonebook/opg_phonebook.js', array('jquery','media-upload','thickbox'));
    wp_enqueue_script('myPhoneBookScript');    


    /* Con este código, se crea una linea en el menú de Administración */
    function opg_show_menu_phonebook(){
        add_menu_page('Oscar Pérez Plugins','Oscar Pérez Plugins','manage_options','opg_plugins','opg_plugin_links_show_form_in_wpadmin', '', 110);
        add_submenu_page( 'opg_plugins', 'Agenda telefónica', 'Agenda telefónica', 'manage_options', 'opg_phonebook', 'opg_plugin_phonebook_show_form_in_wpadmin');
        remove_submenu_page( 'opg_plugins', 'opg_plugins' );
        wp_enqueue_script('myPhoneBookScript');                
    }
    add_action( 'admin_menu', 'opg_show_menu_phonebook' );


    //Hook al activar y desactivar el plugin
    register_activation_hook( __FILE__, 'opg_plugin_phonebook_activate' );
    register_uninstall_hook( __FILE__, 'opg_plugin_phonebook_unistall' );


    // Se crea la tabla al activar el plugin
    function opg_plugin_phonebook_activate() {
        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'opg_plugin_phonebook` 
            ( `idPhone` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
              `name` VARCHAR( 255 ) NOT NULL , 
              `phone` VARCHAR( 40 ) NOT NULL )';
        $wpdb->query($sql);
    }

    // Se borra la tabla al borrar el plugin
    function opg_plugin_phonebook_unistall() {
        global $wpdb;
        $sql = 'DROP TABLE `' . $wpdb->prefix . 'opg_plugin_phonebook`';
        $wpdb->query($sql);
    }





    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
        F U N C I O N E S   D E   A C C E S O   A   B A S E   D E   D A T O S
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

    //función que guarda en base de datos la información introducida en el formulario
    function opg_phonebook_save($name, $phone)
    {
        global $wpdb;
        if (!( isset($name) && isset($phone) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        $save_or_no = $wpdb->insert($wpdb->prefix . 'opg_plugin_phonebook', array(
                'idPhone' => NULL, 'name' => esc_js(trim ($name)), 'phone' => trim ($phone),
            ),
            array('%d', '%s', '%s' )
        );
        if (!$save_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Información del teléfono guardada correctamente</strong></p></div>');
        }
        return true;
    }


    //función que borra un teléfono de la base de datos
    function opg_phonebook_remove($id)
    {
        global $wpdb;
        if ( !isset($id) ) {
            _e('cannot get \$_GET[]');
            exit;
        }

        $delete_or_no = $wpdb->delete($wpdb->prefix . 'opg_plugin_phonebook', array('idPhone' => $id), array( '%d' ) );
        if (!$delete_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Se ha borrado la información del teléfono</strong></p></div>');
        }
        return true;
    }

    //función para actualizar un teléfono
    function opg_phonebook_update($id, $name, $phone)
    {
        global $wpdb;
        if (!( isset($name) && isset($phone) )) {
            _e('cannot get \$_POST[]');
            exit;
        }

        $update_or_no = $wpdb->update($wpdb->prefix . 'opg_plugin_phonebook', 
            array('name' => esc_js(trim ($name)), 'phone' => trim ($phone)),
            array('idPhone' => $id),
            array('%s', '%s')
        );
        if (!$update_or_no) {
            _e('<div class="updated"><p><strong>Error. Please install plugin again</strong></p></div>');
            return false;
        }
        else{
            _e('<div class="updated"><p><strong>Teléfono modificado correctamente</strong></p></div>');
        }
        return true;
    }


    //función que recupera un telefono usando el ID
    function opg_plugin_phonebook_getId($id)
    {
        global $wpdb;
        $row1 = $wpdb->get_row("SELECT name, phone  FROM " . $wpdb->prefix . "opg_plugin_phonebook  WHERE idPhone=".$id);
        return $row1;
    }


    //función que recupera los teléfonos guardados de la base de datos
    function opg_plugin_phonebook_getData()
    {
        global $wpdb;

        $phonebook = $wpdb->get_results( 'SELECT idPhone, name, phone FROM ' . $wpdb->prefix . 'opg_plugin_phonebook ORDER BY name' );
        if (count($phonebook)>0){            
?>
	        <hr style="width:94%; margin:20px 0">	
            <h2>Directorio telefónico</h2>
            <table class="wp-list-table widefat manage-column" style="width:98%">            
             <thead>
                <tr>
                    <th scope="col" class="manage-column"<span>Nombre</span></th>
                    <th scope="col" class="manage-column"<span>Teléfono</span></th>
                    <th scope="col" class="manage-column">&nbsp;</th>
                    <th scope="col" class="manage-column">&nbsp;</th>
                </tr>
             </thead>
             <tbody>

<?php
            $cont = 0;
            foreach ( $phonebook as $phone ) {
                $cont++;
                if ($cont%2 ==1){ echo '<tr class="alternate">'; }
                else{ echo '<tr>'; }
?>
                    <td><?php echo( $phone->name ); ?></td>
                    <td><?php echo( $phone->phone ); ?></td>
                    <td><a href="admin.php?page=opg_phonebook&amp;task=edit_phone&amp;id=<?php echo( $phone->idPhone ); ?>"><img src="<?php echo WP_PLUGIN_URL.'/opg_phonebook/img/modificar.png'?>" alt="Modificar"></a></td>
                    <td><a href="#"><img src="<?php echo WP_PLUGIN_URL.'/opg_aside/img/papelera.png'?>" alt="Borrar" id="<?php echo( $phone->idPhone ); ?>" class="btnDeletePhone"></a></td>
                </tr>
<?php                
            }
        }
?>
             </tbody>
            </table>
<?php
        return true;
    }



	/*
	   F U N C I O N   Q U E   S E   E J E C U T A   A L   A C C E D E R   A L   P L U G I N   D E S D E   A D M I N I S T R A C I O N
       La función la definimos en la llamada add_menu_page()
	*/
	function opg_plugin_phonebook_show_form_in_wpadmin(){
 
        $valueInputPhone = "";
        $valueInputName  = "";
        $valueInputId    = "";

	    
    	if(isset($_POST['action']) && $_POST['action'] == 'salvaropciones'){

            //si el input idPhone (hidden) está vacio, se trata de un nuevo registro
            if( strlen($_POST['idPhone']) == 0 ){
                //guardamos el teléfono
                opg_phonebook_save($_POST['name'], $_POST['phone']);
            }
            else{
                opg_phonebook_update($_POST['idPhone'], $_POST['name'], $_POST['phone']);
            }   
	    }
        else{
            //recuperamos la tarea a realizar (edit o delete)
            if (isset($_GET["task"]))
                $task = $_GET["task"]; //get task for choosing function
            else
                $task = '';
            //recuperamos el id del telefono
            if (isset($_GET["id"]))
                $id = $_GET["id"];
            else
                $id = 0;


            switch ($task) {
                case 'edit_phone':
                    echo("<div class='wrap'><h2>Modificar información del teléfono</h2></div>"); 
                    $row = opg_plugin_phonebook_getId($id);
                    $valueInputPhone = $row->phone;
                    $valueInputName  = $row->name;
                    $valueInputId    = $id;
                    break;
                case 'remove_phone':
                    opg_phonebook_remove($id);
                    break;
                default:
                    echo("<div class='wrap'><h2>Añadir un nuevo teléfono</h2></div>"); 
                    break;
            }
        }
?>
        <form method='post' action='admin.php?page=opg_phonebook' name='opgPluginAdminForm' id='opgPluginAdminForm'>
            <input type='hidden' name='action' value='salvaropciones'> 
            <table class='form-table'>
                <tbody>
                    <tr>
                        <th><label for='name'>Nombre</label></th>
                        <td>
                            <input type='text' name='name' id='name' placeholder='Introduzca el nombre' value="<?php echo $valueInputName ?>" style='width: 300px'>
                        </td>
                    </tr>
                    <tr>
                        <th><label for='phone'>Teléfono</label></th>
                        <td>
                            <input type='text' name='phone' id='phone' placeholder='Introduzca el teléfono' value="<?php echo $valueInputPhone ?>" style='width: 300px'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' style='padding-left:140px'>
                            <input type='submit' value='Enviar'>
                            <input type='hidden' name="idPhone" value="<?php echo $valueInputId ?>">
                        </td>
                    </tr>
                </tbody>
            </table>        
        </form>

<?php
        //se muestra el listado de todos los teléfonos guardados
        opg_plugin_phonebook_getData();
?>        
<?php }?>