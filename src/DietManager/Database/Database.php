<?php

namespace Epr\DietManager\Database;

class Database{

    private $eprdb = null;
    private $table_users = 'epr_users';
    private $table_daily_diet = 'epr_daily_diet';

    public function __construct(){
        global $wpdb;
        $this->eprdb = $wpdb;
        //$this->table_users = 'epr_users';
    }//End __construct();

    public function getResults($sql){
        $results = $this->eprdb->get_results($this->eprdb->prepare($sql));
        return $results;
    }//End getResults();

    //Devuelve una fila de la tabla especificada.
    public function getRow(string $table, string $field, string $id){
        $item = $this->eprdb->get_row($this->eprdb->prepare("SELECT * FROM $table WHERE $field = %s", $id), ARRAY_A);
        return $item;

    }//End getRow();

    //Create the tables needed for the plugin.
    public function createTables() {
        require_once ( ABSPATH . 'wp-admin/includes/upgrade.php');
        $this->createTableUsers();
        $this->createTableDailyDiet();
    }//End createTables();

    //Create table for users
    // private function createTableUsers() {
    //     dbDelta( "CREATE TABLE IF NOT EXISTS {$this->table_users}
    //                 (
    //                 user_id bigint (20) unsigned NOT NULL ,
    //                 user_name text (30),
    //                 user_apellidos text (70),
    //                 user_telefono text (10),
    //                 user_email text (50),
    //                 user_direccion text (100),
    //                 user_provincia text (50),
    //                 user_localidad text (70),
    //                 user_cp text (5),
    //                 key (user_id)
    //                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    //                 ");
    // }//End createTableUser();

    private function createTableUsers() {
        dbDelta( "CREATE TABLE IF NOT EXISTS {$this->table_users}
                    (
                    user_id bigint (20) unsigned NOT NULL AUTO_INCREMENT ,
                    user_name text (30),
                    user_apellidos text (70),
                    user_telefono text (10),
                    user_email text (50),
                    user_direccion text (100),
                    user_provincia text (50),
                    user_localidad text (70),
                    user_cp text (5),
                    PRIMARY KEY (user_id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                    ");
    }//End createTableUser();

    //Create table for daily diets.
    private function createTableDailyDiet() {
        dbDelta( "CREATE TABLE IF NOT EXISTS {$this->table_daily_diet}
                (
                    daily_diet_id bigint (20) unsigned NOT NULL,
                    dia datetime,
                    desayuno text (80),
                    almuerzo text (80),
                    merienda text (80),
                    cena text (80)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ");
    }//End createTableDailyDient()

    public function updateUser($user, $user_id){
        $this->eprdb->update(
                                $this->table_users,
                                $user,
                                array('user_id' => $user_id)
                            );
    }//End updateUser();

    //añade un registro con datos de usuario a la tabla epr_users
    public function insertNewEprUser($datosUsuario){
        echo "hola";
        //Inserto un usuario en la tabla epr_users
        $result = $this->eprdb->insert(
            $this->table_users,
            $datosUsuario,
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
            )
        );

        return $result;

    }//End insertNewEprUser();


    //Elimina un conjunto de usuarios de la tabla epr_users a partir de un array de id de usuarios.
    public function deleteEprUsers(  $listaIdUsers ){
        $sql = "DELETE FROM {$this->table_users} WHERE user_id IN (" . implode(', ', array_fill(0, count($listaIdUsers), '%s')) . ")";
        $query = call_user_func_array(array($this->eprdb, 'prepare'), array_merge(array($sql), $listaIdUsers));
        $queryResult = $queryResult = $this->eprdb->query($query);

        return $queryResult;
    }//End $listaIdUsers()


}//End database.