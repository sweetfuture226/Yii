<?php
/**
 * 
 * EPasswordStrength class
 * 
 * Validate if password is strong enought
 *
 * The validator check if password has at least min characters,
 * and if password contain at least one lower case letter, at least one upper case letter,
 * and at least one number
 * 
 *
 *
 *
 * @see      http://www.yiiframework.com
 * @version  1.0
 * @access   public
 * @author   ivica Nedeljkovic (ivica.nedeljkovic@gmail.com)
 */
class EPasswordStrength extends CValidator{
    
    //Minimum password length
    const MIN = 6;
    
    /**
	 * (non-PHPdoc)
	 * @see CValidator::validateAttribute()
	 */
    protected function validateAttribute($object,$attribute){
       if(!$this->checkPasswordStrength($object->$attribute)){
            $message=$this->message!==null?$this->message:Yii::t("EPasswordStrength","Para atender os requisitos de segurança, é necessário que a {attribute} contenha pelo menos {$this->min} caracteres, uma letra minúscula, uma letra maiúscula, e um número.");
			$this->addError($object,$attribute,$message);
       }
    }
    
    /**
     * Check if password is strong enought
     * @param string $password
     * @return boolean 
     */
    public function checkPasswordStrength($password)
    {
        if (preg_match("/^.*(?=.{" . self::MIN . ",})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $password)) {
            return true;
        } else {
            return false;
        }
    }
        
}

