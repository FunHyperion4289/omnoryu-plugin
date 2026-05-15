<?php

defined('ABSPATH') || exit;

class Omnoryu_Macro {

    private $db;

    public function __construct( Omnoryu_DB $db)
    {
        $this->db = $db;
    }

    public function init(){
        add_action( 'jet-engine/register-macros', [$this, 'register_my_macros'] );
    }

    public function register_my_macros()
    {
        if ( ! class_exists( '\Jet_Engine_Base_Macros' ) ) {
            return;
        }
        require_once __DIR__ . '/macro-code.php';
        new Omnoryu_Macro_Total_Views($this->db);
        new Omnoryu_Macro_User_Views($this->db);
    }

}

