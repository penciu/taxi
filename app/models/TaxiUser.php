<?php

class TaxiUser extends \Eloquent {
    
	protected $table = 'taxiusers';
        
        public static $registrationRules = array(
        'first_name' => 'required|min:2|max:50',
        'last_name' => 'required|min:2|max:50',
        'email' => 'required|email|unique:taxiusers|max:50'
    );
}