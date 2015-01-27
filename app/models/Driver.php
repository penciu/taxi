<?php

class Driver extends \Eloquent {

    protected $table = 'drivers';
    
    public static $registrationRules = array(
        'first_name' => 'required|min:2|max:50',
        'last_name' => 'required|min:2|max:50',
        'email' => 'required|email|unique:drivers|max:50',
        'password' => 'required|min:6|max:60',
        'image_url' => 'image|max:8000',
        'car_model' => 'required|min:2|max:50',
        'car_number' => 'required|min:2|max:50',
        'car_seats' => 'required|min:1|max:2',
        'car_trunk_volume' => 'required|min:1|max:3',
    );
    
}
