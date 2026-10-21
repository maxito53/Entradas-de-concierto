<?php

use Entradas\Entity\Admin;
use Doctrine\ORM\EntityManager;

class AdminModel {
    
    private EntityManager $entityManager;
    
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    /**
     * inserta el admin a la base de datos
     * 
     * @return void
     */
    public function insertarAdmin(): void {
        if (!$this->entityManager->getRepository(Admin::class)->findOneBy(['email' => 'admin@admin.com'])) {
            $admin = new Admin();
            $admin->setEmail('admin@admin.com');
            $admin->setPassword('Password123!!');
            $this->entityManager->persist($admin);
        }
    }
    
}

