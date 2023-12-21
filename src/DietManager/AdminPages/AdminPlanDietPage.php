<?php

  namespace Epr\DietManager\AdminPages;

  use Twig\Environment;

  class AdminPlanDietPage{

    private $page_title = "Planes de alimentación";
    private $menu_title = "Planes de alimentación";
    private $main_menu_slug = 'diet-plans';

    public function __construct(
                                private \Twig\Environment $template
                                ){
      $this->template = $template;
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
        'nuevo-plan-alimentacion',
        array($this, 'nuevoPlanAlimentacionPageContent'),
    );
    }//End createNewPlanDiet();

    public function nuevoPlanAlimentacionPageContent(){
      echo $this->template->render('plan-alimentacion\nuevo-plan-alimentacion.html.twig');
    }//End nuevoPlanAlimentacionPageContent();

    //Carga los estilos.	
    function load_scripts(){
      wp_enqueue_style('eprcss', plugin_dir_url( __FILE__ ) . '../../../assets/css/styles.css');
      wp_enqueue_style('bootstrapcss', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
      wp_enqueue_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
    }



  }//End AdminPlanDietPage;