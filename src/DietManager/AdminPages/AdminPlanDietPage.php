<?php

  namespace Epr\DietManager\AdminPages;

  use Twig\Environment;

  class AdminPlanDietPage{

    private $page_title = "Planes de alimentación";
    private $menu_title = "Planes de alimentación";
    private $main_menu_slug = 'diet-plans';

    public function __construct(
                                private \Epr\DietManager\PlanDieta\PlanAlimentation $planAlimentacion,
                                private \Twig\Environment $template
                                ){

      $this->template = $template;
      if ( isset($_REQUEST['page'])){
        $this->thisAdminPage = $_REQUEST['page'];
      }  

      //Crea admin menu para plan de alimentación.
      add_action('admin_menu', array($this, 'createAdminMenu'), 10);
      add_action('admin_menu', array($this, 'createNewPlanDiet'), 10);
      add_action( 'admin_enqueue_scripts', array($this, 'load_scripts' ));
    }//End __constructor();

    public function createAdminMenu(){
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            'manage_options',
            $this->main_menu_slug,
            array($this, 'planAlimentacionPageContent'),
            'dashicons-food',
            3
        );
    }//End createAdminMenu();

    public function planAlimentacionPageContent(){
        echo $this->template->render('plan-alimentacion\plan-alimentacion.html.twig');
    }

    public function createNewPlanDiet(){
      add_submenu_page( 
        $this->main_menu_slug,
        'Nuevo plan de alimentación',
        'Nuevo plan de alimentación',
        'activate_plugins',
        'plan-alimentacion-form',
        array($this, 'planAlimentacionFormPageContent'),
    );
    }//End createNewPlanDiet();

    public function planAlimentacionFormPageContent(){
      $defaultPlan = array(
        'plan_id' => '',
        'plan_nombre' => '',
        'plan_usuario' => '',
        'plan_observaciones' => '',
      );

      $completePlan = shortcode_atts($defaultPlan, $_REQUEST);
      $action = '';
      $messages[] = null;
      $notice = '';

      if( isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'guardar_plan') ){
        echo "Guardar el plan";
        //Aqui ver cómo actualizar el plan.
        if ( isset($completePlan['plan_id']) && ($completePlan['plan_id'] != "") ){
          echo "Actualiza Plan <br>";
          //$this->user->updateUser($completeUser, $completeUser['user_id']);
        } else {
          echo "Nuevo plan<br>";
          $resultado = $this->planAlimentation->creaNuevoPlanAlimentacion();
          //Registrar el plan en la tabla  y también en la tabla .
          //$resultado = $this->user->creaNuevoUsuario();

          // if (gettype($resultado) == 'integer'){
          //     array_push($messages, "Nuevo plan añadido con éxito.");
          // }

          // if ( is_a($resultado, 'WP_Error' )) {
          //     $messages = $resultado->get_error_messages();
          // }
      }
      } else {
        echo "No guardar el plan";
      }//End if;

      $action = isset($_REQUEST['action']) ? $action = $_REQUEST['action'] : $action = 'new';
        $nonce = wp_create_nonce('guardar_plan');
        
        switch($action){
            case 'edit';
                echo "Editar plan alimentaciíon";
                //Obtener el plam actual.
                //$currentUser = $this->user->getUserById($completeUser['user_id']);
                //echo  $this->template->render('edit-user-form.html.twig', ['user' => $currentUser, 'nonce' => $nonce]);
                break;
            default;
                //$templateUser = null;
                echo $this->template->render('plan-alimentacion\nuevo-plan-alimentacion.html.twig', ['plan' => $defaultPlan, 'messages' => $messages, 'nonce' => $nonce]);
                //echo  $this->template->render('new-user-form.html.twig', ['user' => $defaultUser, 'messages' => $messages, 'nonce' => $nonce]);
                break;
        }//End switch;



      
    }//End nuevoPlanAlimentacionPageContent();

    //Carga los estilos.	
    function load_scripts(){
      global $pagenow;
      wp_enqueue_style('eprcss', plugin_dir_url( __FILE__ ) . '../../../assets/css/styles.css');
      wp_enqueue_style('bootstrapcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
      wp_enqueue_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
       if ( isset($this->thisAdminPage) && $this->thisAdminPage === 'nuevo-plan-alimentacion' ) {
         wp_enqueue_script('epjs',
          plugin_dir_url( __FILE__ ) . '../../../assets/js/adminplandietpage.js',
          array(),
          '1.0.0',
          array(
            'strategy'  => 'defer',
            )
          );
        }
    }//End loadScripts();



  }//End AdminPlanDietPage;