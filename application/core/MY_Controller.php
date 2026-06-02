<?php
class MY_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        //get your data
        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $global_data = array('csrf'=>$csrf);
        $this->load->vars($global_data);
    }
}
?>
