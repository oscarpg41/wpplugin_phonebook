<?php
/*
Plugin Name: Phone Book
Plugin URI: http://www.oscarperez.es/wordpress-plugins/opg_phonebook.zip
Description: This PhoneBook plugin helps to manage the phone book easily over the WordPress blog. 
Author: Oskar Pérez
Author URI: http://www.oscarperez.es/
Version: 1.0
License: GPLv2
*/
?>
<?php

    /* Con este codigo, se crea el enlace dentro del submenú Ajustes */
	/*function opg_show_submenu_phonebook(){
	    add_options_page('Agenda de Teléfonos','Agenda telefónica','read','plugin_opg_phonebook','opg_plugin_phonebook_show_form_in_wpadmin');
	}
	add_action('admin_menu','opg_show_submenu_phonebook');
    */


    /* Con este código, se crea una linea en el menú de Administración */
    function opg_show_menu_phonebook(){
        //add_menu_page( 'Agenda de Teléfonos','Agenda telefónica','read','plugin_opg_phonebook','opg_plugin_phonebook_show_form_in_wpadmin'); 
        add_menu_page('Phone Book','Phone Book','manage_options','plugin_opg_phonebook','opg_plugin_phonebook_show_form_in_wpadmin', plugins_url('images/phone-book.png', __FILE__));
        //le hemos añadido al menú una imagen
    }
    add_action( 'admin_menu', 'opg_show_menu_phonebook' );


    //Hook al activar y desactivar el plugin
    register_activation_hook( __FILE__, 'opg_plugin_phonebook_activate' );
    register_deactivation_hook( __FILE__, 'opg_plugin_phonebook_deactivate' );


    // Se crea la tabla al activar el plugin
    function opg_plugin_phonebook_activate() {
        global $wpdb;

        $sql = 'CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'opg_plugin_phonebook` 
            ( `idPhone` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , 
              `name` VARCHAR( 255 ) NOT NULL , 
              `phone` VARCHAR( 40 ) NOT NULL )';
        $wpdb->query($sql);
    }

    // Se borra la tabla al desactivar el plugin
    function opg_plugin_phonebook_deactivate() {
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
            _e('<div class="updated"><p><strong>Phone stored in database</strong></p></div>');
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
            _e('<div class="updated"><p><strong>Phone deleted to database</strong></p></div>');
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
            _e('<div class="updated"><p><strong>Phone updated in database</strong></p></div>');
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
            <h2>Phone Book</h2>
            <table class="wp-list-table widefat manage-column" style="width:95%">            
             <thead>
                <tr>
                    <th scope="col" id="description" class="manage-column" style=""><span>Name</span></a></th>
                    <th scope="col" id="description" class="manage-column" style=""><span>Phone</span></a></th>
                    <th scope="col" id="description" class="manage-column" style=""><span>Edit</span></a></th>
                    <th scope="col" id="description" class="manage-column" style=""><span>Delete</span></a></th>
                </tr>
             </thead>

<?php
            foreach ( $phonebook as $phone ) {
?>
             <tbody>
                <tr>
                    <td><?php echo( $phone->name ); ?></td>
                    <td><?php echo( $phone->phone ); ?></td>
                    <td><a href="admin.php?page=plugin_opg_phonebook&amp;task=edit_phone&amp;id=<?php echo( $phone->idPhone ); ?>">Edit</a></td>
                    <td><a href="admin.php?page=plugin_opg_phonebook&amp;task=remove_phone&amp;id=<?php echo( $phone->idPhone ); ?>">Delete</a></td>                    
                </tr>
             </tbody>
<?php                
            }
        }

?>
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

	    echo("<div class='wrap'><h2>Add a phone</h2></div>"); 

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
                    $row = opg_plugin_phonebook_getId($id);
                    $valueInputPhone = $row->phone;
                    $valueInputName  = $row->name;
                    $valueInputId    = $id;
                    break;
                case 'remove_phone':
                    opg_phonebook_remove($id);
                    break;
                default:
                    break;
            }
        }
?>
        <p>Plugin to create a phone book</p>
        <form method='post' action='options-general.php?page=plugin_opg_phonebook' name='opgPluginAdminForm' id='opgPluginAdminForm'>
            <input type='hidden' name='action' value='salvaropciones'> 
            <table class='form-table'>
                <tbody>
                    <tr>
                        <th><label for='name'>Name</label></th>
                        <td>
                            <input type='text' name='name' id='name' placeholder='Enter a name' value="<?php echo $valueInputName ?>" style='width: 300px'>
                        </td>
                    </tr>
                    <tr>
                        <th><label for='phone'>Phone</label></th>
                        <td>
                            <input type='text' name='phone' id='phone' placeholder='Enter a phone number' value="<?php echo $valueInputPhone ?>" style='width: 300px'>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' style='padding-left:140px'>
                            <input type='submit' value='Send information'>
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