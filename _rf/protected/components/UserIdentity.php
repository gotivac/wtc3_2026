<?php

class UserIdentity extends CUserIdentity {

    private $id;

    public function authenticate() {
        $record = User::model()->findByAttributes(array('email' => $this->username, 'roles'=>'rf_korisnik', 'active' => 1));
        $r = User::model()->findAll();

       
        if ($record === null) {
             
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            
        } else if ($record->password !== md5($this->password)) {
            
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            
        } else {
            $this->id = $record->id;
            
            
            $this->setState('roles', $record->roles);
            $this->setState('name', $record->name);
            $this->errorCode = self::ERROR_NONE;
        }
        
        return !$this->errorCode;
    }

    public function getId() {
        return $this->id;
    }

}
