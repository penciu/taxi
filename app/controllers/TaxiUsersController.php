<?php

class TaxiUsersController extends \BaseController {

    public function __construct() {
        
    }
    
    public function getTaxiUsers() {

        $taxiUsers = TaxiUser::all();

        $json = array();

        foreach ($taxiUsers as $taxiUser) {

            $attributes = new stdClass;

            $attributes->first_name = $taxiUser->first_name;
            $attributes->last_name = $taxiUser->last_name;
            $attributes->email = $taxiUser->email;

            array_push($json, $attributes);
        }

        return Response::json($json);
    }
    
    public function registerNewTaxiUser() {
        $inputs = Input::except('_token');
        
        $validator = Validator::make($inputs, TaxiUser::$registrationRules);
         if ($validator->passes()) {
            $taxiUser = new TaxiUser;

            foreach ($inputs as $column => $data) {
                $taxiUser->{$column} = $data;
            }
            $taxiUser->save();
                    
        return http_response_code(200);
        } else {
            $error = array();
            $attributes = new stdClass;
            
            $attributes->code = http_response_code(500);
            $attributes->error = $validator->messages()->toJson();

            array_push($error, $attributes);
            
            return $error;
        }

    }
    
    public function deleteTaxiUser() {
        
    }
    
    public function loginTaxiUser() {

        try {
            $email = Input::get('email');
            $password = Input::get('password');
            $hashed_pass = $password;
//            $decrypted_pass = Crypt::decrypt($password);
//            $hashed_pass = Hash::make($decrypted_pass);

            //If the login is from social network the password is the encrypted email address
//            if (Input::has('social')) {
//                $password = Crypt::encrypt($email);
//                $hashed_pass = Hash::make($password);
//            }

            $taxiUser = TaxiUser::where('email', '=', $email)->firstOrFail();
            if ($taxiUser->password === $hashed_pass) {
                $taxiUser->token = str_random(60);
                $taxiUser->save();
                
                return $taxiUser->toJson();
            } else {
                return $this->returnError(404, 'Wrong username or password');
            }
        } catch (Exception $ex) {
            return $this->returnError(404, 'Wrong username or password');
        }
        return $this->returnError(404, 'Wrong username or password');
    }
    
}
