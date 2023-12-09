<?php

namespace Epr\DietManager\Users;

use Epr\Database;

class User{

    private $user_id = null;
    private $user_name = null;

    private $db = null;
    private $table_users = 'epr_users';


    public function __construct(
                      private \Epr\DietManager\Database\Database $eprdb
                    ){
    }//End __construct();

    public function getUsers(){
      $sql = "SELECT * FROM $this->table_users";
      $users = $this->eprdb->getResults($sql);
      
      return $users;
    }//End getUsers();

    public function creaNuevoUsuario() {
      if ( isset( $_POST['_wpnonce'] )  && wp_verify_nonce( $_POST['_wpnonce'], 'guardar_usuario' )   ){
        $userData = array(
          'user_pass' => 'random_password',
          'user_login' => $_POST['email'],
          'user_email' => $_POST['email'],
          'user_name' => $_POST['nombre'],
          'user_apellidos' => $_POST['apellidos'],
          'user_telefono' => $_POST['telefono'],
          'user_direccion' => $_POST['direccion'],
          'user_provincia' => $_POST['provincia'],
          'user_localidad' => $_POST['localidad'],
          'user_cp' => $_POST['cp'],

        );
       
        //añado un usuario a la tabla wp_user
        $result = wp_insert_user($userData);
        //Si on ha habido un error añado un nuevo registro a la tabla epr_users con la información del usuario.
        //Añadir registro a epr_users
        //Devuelvo el valor de $result.
        if ( !is_a($result, 'WP_Error')){
          //No ha habido error, añado un usuario a la tabla epr_users.
          //Añado user_id al usuario para identificación
          $userData['user_id'] = $result;
          //Eliminao el password porque no lo necesito en la tabla epr_users
          unset($userData['user_pass']);
          unset($userData['user_login']);
          $this->eprdb->insertNewEprUser($userData);
        } 

        return $result;


      }

    }//End creaNuevoUsuario()


    public function registerUser($user_id){
      if ( isset( $_POST['_wpnonce'] )  && wp_verify_nonce( $_POST['_wpnonce'], 'guardar_usuario' )   ){
        // $userName = $_POST['email'];
        // $password = 'random_password';
        // $email = $_POST['email'];
        $userData = array(
          'user_pass' => 'random_password',
          'user_login' => $_POST['email'],
          'user_email' => $_POST['email']
        );

        //$result = wp_create_user($userName, $password, $email);
        //$result = wp_create_user($userName, $password, $email);
        $result = wp_insert_user($userData);
        echo $result;

      }

      // $user_login = $_POST['user_login'];
      // $user_email = $_POST['user_email'];
      // $user = array(
      //   'user_id' => $user_id,
      //   'user_name' => $user_login,
      //   'user_email' => $user_email
      //);
      
//      $this->saveUser($user);
    }//End registerUser();


    public function saveUser($user){
      //To-do:
      // Obtener $wpdb mediante dependency injection.
      global $wpdb;
      $this->db = $wpdb;

      $this->db->insert($this->table_users, $user);

    }//End saveUser();

    public function getUserById($id){
      
      $table_column = 'user_id';
      $item = $this->eprdb->getRow($this->table_users, $table_column, $id);
      return $item;
    }//End ugetUserById();

    public function updateUser($user, $user_id){
      $this->eprdb->updateUser($user, $user_id);
    }

    public function deleteUsers( $user_ids) {
      $result = $this->eprdb->deleteEprUsers ( $user_ids);
      return $result;
    }//End deleteUSers();



}//End Users.