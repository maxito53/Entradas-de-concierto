<?php

class EntradaExitoController extends Controller {
    
    public function __construct() {}
    
    public function show($entrada) {
       
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $vHome = new LoginView();
            $vHome->show();
        } else {
            $vHome = new EntradaExitoView();
            $vHome->show($entrada);
        } 
    }
    
}