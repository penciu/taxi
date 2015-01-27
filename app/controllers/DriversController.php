<?php

class DriversController extends \BaseController {

    public function __construct() {
        
    }

    public function getDrivers() {

        $drivers = Driver::all();

        $json = array();

        foreach ($drivers as $driver) {

            $attributes = new stdClass;

            $attributes->id = $driver->id;
            $attributes->first_name = $driver->first_name;
            $attributes->last_name = $driver->last_name;
            $attributes->email = $driver->email;
            $attributes->car_model = $driver->car_model;
            $attributes->car_number = $driver->car_number;
            $attributes->car_seats = $driver->car_seats;
            $attributes->car_trunk_volume = $driver->car_trunk_volume;

            array_push($json, $attributes);
        }

        return Response::json($json);
    }

    public function getNearestDriver() {

        if (Input::has('token')) {
            $settings_radius = Input::get('radius', 20);
            $settings_unit = Input::get('unit', 'km');

            $user_lat = Input::get('user_latitude', 0);
            $user_lng = Input::get('user_lognitude', 0);

            $unit = 6731;

            if ($settings_unit === 'mi') {
                $unit = 3959;
            }

//            3959 - miles
//            6371 - kilometers

            $token = Input::get('token');
            $email = Input::get('email');

            if ($this->checkToken($token, $email)) {
                try {

                    $driver = Driver::select(
                                    DB::raw("*,
                                          ( " . $unit . " * acos( cos( radians(" . $user_lat . ") ) *
                                            cos( radians( latitude ) )
                                            * cos( radians( longitude ) - radians(" . $user_lng . ")
                                            ) + sin( radians(" . $user_lat . ") ) *
                                            sin( radians( latitude ) ) )
                                          ) AS distance"))
                            ->having("distance", "<", $settings_radius)
                            ->where('status', '=', 1) //check if the driver is avaiable
                            ->orderBy("distance", 'asc')
                            ->firstOrFail();
//                    $driver = Driver::select(
//                                    DB::raw("*,
//                                          ( ? * acos( cos( radians(?) ) *
//                                            cos( radians( latitude ) )
//                                            * cos( radians( longitude ) - radians(?)
//                                            ) + sin( radians(?) ) *
//                                            sin( radians( latitude ) ) )
//                                          ) AS distance"))
//                            ->having("distance", "<", "?")
//                            ->where('status', '=', "?") //check if the driver is avaiable
//                            ->orderBy("distance", 'asc')
//                            ->setBindings([$unit, $user_lat, $user_lng, $user_lat, 1, $settings_radius])
//                            ->first();
//                            dd(DB::getQueryLog());
                } catch (Exception $ex) {
                    return $this->returnError(405, 'There is no avaiable drivers near');
                }

                return Response::json($driver);
            }
        }
        $error = array();
        $attributes = new stdClass;

        $attributes->code = 402;
        $attributes->error = 'Error - wrong token';

        array_push($error, $attributes);

        return $this->returnError(402, 'Error - wrong token');
    }

    public function registerNewDriver() {
        $inputs = Input::except('_token');

        $validator = Validator::make($inputs, Driver::$registrationRules);
        if ($validator->passes()) {
            $driver = new Driver;
            
            $driver->email = Input::get('email');
            $password = Input::get('password');
            
            $driver->password = Input::get('password');
            
            $driver->first_name = Input::get('first_name');
            $driver->last_name = Input::get('last_name');
            $driver->car_model = Input::get('car_model');
            $driver->car_number = Input::get('car_number');
            $driver->car_seats = Input::get('car_seats');
            $driver->car_trunk_volume = Input::get('car_trunk_volume');
            
//            $decrypted_pass = Crypt::decrypt($password);
//            $hashed_pass = Hash::make($decrypted_pass);

            //If the login is from social network the password is the encrypted email address
//            if (Input::has('social')) {
//                $password = Crypt::encrypt($email);
//                $hashed_pass = Hash::make($password);
//            }
            
            if (Input::hasFile('image_url')) {

                $path = public_path() . '/uploads/drivers/';

                if (file_exists($path . $driver->image_url)) {

                    $filePath = $path . $driver->image_url;
                    chmod($filePath, 0777);
                    unlink($filePath);
                }
                $file = Input::file('image_url');
                $path = public_path() . '/uploads/drivers/';
//                $filename = $file->getClientOriginalName();
                $filename = $driver->id;
                $file->move($path, $filename);
                $driver->image_url = $filename;
            }

//            foreach ($inputs as $column => $data) {
//                $driver->{$column} = $data;
//            }
            

            $driver->save();

            return http_response_code(200);
        } else {
            return $this->returnError(406, $validator->messages()->toJson());
        }
    }

    public function deleteDriver() {
        
    }

    public function loginDriver() {

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

            $driver = Driver::where('email', '=', $email)->firstOrFail();
            if ($driver->password === $hashed_pass) {
                $driver->token = str_random(60);
                $driver->save();
                return $driver->toJson();
            } else {
                return $this->returnError(404, 'Wrong username or password');
            }
        } catch (Exception $ex) {
            return $this->returnError(404, 'Wrong username or password');
        }
        return $this->returnError(404, 'Wrong username or password');
    }

}
