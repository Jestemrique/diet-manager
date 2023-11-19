<?php

namespace Epr\DietManager\AdminPages;

use Epr\Users;
use Twig\Environment;

class AdminUsersPage{

    private $page_title = 'Diet users';
    private $menu_title = 'Diet users';
    private $main_menu_slug = 'diet-users';
    //private $template;

    public function __construct(
                                //private Database $eprdb
                                private \Epr\DietManager\Users\User $user,
                                private \Twig\Environment $template
                                ){

        $this->template = $template;
        
        //Create admin menu for users.
        add_action('admin_menu', array($this, 'createAdminMenu'), 10);
        add_action('admin_menu', array($this, 'createNewUserMenu'), 10);
  
        add_action( 'admin_enqueue_scripts', array($this, 'load_scripts' ));
        
    }//End construct.

    public function createNewUserMenu() {
        add_submenu_page( 
            $this->main_menu_slug,
            'Nuevo usuario',
            'Nuevo usuario',
            'activate_plugins',
            'user-form',
            //array($this, 'userFormPageContent'),
            array($this, 'userFormPageContent'),
        );
    }//End createNewUserMenu();


    public function userFormPageContent(){
        $defaultUser = array(
            'user_id' => '',
            'user_name' => '',
            'user_apellidos' => '',
            'user_telefono' => '',
            'user_email' => '',
            'user_direccion' => '',
            'user_provincia' => '',
            'user_localidad' => '',
            'user_cp' => '',
        );

        $completeUser = shortcode_atts($defaultUser, $_REQUEST);
        $action = '';
        $messages[]= null;
        $notice = '';


        if ( isset( $_POST['_wpnonce'] )  && wp_verify_nonce( $_POST['_wpnonce'], 'guardar_usuario' )   ){
            echo "Guarda el usuario complete_user <br>";
            if ( isset($completeUser['user_id']) && ($completeUser['user_id'] != "") ){
                echo "Actualiza usuario <br>";
                $this->user->updateUser($completeUser, $completeUser['user_id']);
            } else {
                echo "Nuevo usuario<br>";
                //REgistrar el usuario en la tabla wp-user y también en la tabla epr-users.
                
                //do_action('user_register', array($this->user,'registerUser'));
                $resultado = $this->user->creaNuevoUsuario();

                if (gettype($resultado) == 'integer'){
                    //$message = "Nuevo usuario añadido con éxito.";
                    array_push($messages, "Nuevo usuario añadido con éxito.");

                }

                if ( is_a($resultado, 'WP_Error' )) {
                    //$message = $resultado->errors;
                    $messages = $resultado->get_error_messages();
                }

            }

        } else {
            echo "No guardes el usuario";
            
        }

        $action = isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = 'new';
        $nonce = wp_create_nonce('guardar_usuario');
        
        switch($action){
            case 'edit';
                //Obtener el usuario actual.
                $currentUser = $this->user->getUserById($completeUser['user_id']);
                echo  $this->template->render('edit-user-form.html.twig', ['user' => $currentUser, 'nonce' => $nonce]);
                break;
            default;
                //$templateUser = null;

                echo  $this->template->render('new-user-form.html.twig', ['user' => $defaultUser, 'messages' => $messages, 'nonce' => $nonce]);
                break;
        }//End switch;
    }//End renderUserFormTwig()


    //Create main menu for users
    public function createAdminMenu(){
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            'manage_options',
            $this->main_menu_slug,
            array($this, 'userAdminPageContent'),
            'dashicons-admin-users',
            3
        );
    }//End createAdminMenu();


    /**
     * Presenta la lista de usuarios.
     */
    public function userAdminPageContent() {
        $usersList = $this->user->getUsers();
        echo $this->template->render('users-list.html.twig', ['userList' => $usersList]);
    }//End UserAdminPageContent();


    /**
    * Simple function that validates data and retrieve bool on success
    * and error message(s) on error
    *
    * @param $item
    * @return bool|string
    */
    public function validate_user($item){
        $messages = array();
        //echo "custom_table_validate_epr_user (item): ". "<pre>" . json_encode( $item) . "</pre>";
        if (empty($item['user_name'])) $messages[] = 'Name is required';
        //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
        //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
        //...
        if (empty($messages)) return true;
        return implode('<br />', $messages);
    }//End validate_epr_user();


    //Carga los estilos.	
    function load_scripts(){
        wp_enqueue_style('eprcss', plugin_dir_url( __FILE__ ) . '../../../assets/css/styles.css');
        wp_enqueue_style('bootstrapcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
        wp_enqueue_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
    }

    private function crea_nuevo_usuario(){
        echo "hola";
    }

}//End AdminUsersPage