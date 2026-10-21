<?php

class HomeController {
    
    public function __construct() {}
    
    public function show($params=null) {
        
        $homeModel = new HomeModel();
        $data = $homeModel->getSesiones();
        
        $loginModel = new LoginModel();
        
        if (!$loginModel->isLoggedIn()) {
            $vHome = new LoginView();
            $vHome->show();
        } else {
            $vHome = new HomeView();
            // pasar tanto sesiones como precios a la vista
            if ($data && isset($data['sesiones']) && isset($data['precios'])) {
                $vHome->show($data['sesiones'], $data['precios']);
            } else {
                $vHome->show(null, []);
            }
        }
    }
    
}