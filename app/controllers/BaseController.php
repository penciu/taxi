<?php

class BaseController extends Controller {

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    protected function checkToken($token, $email) {
        if ($token && $email) {

            try {

                $taxiUser = TaxiUser::where('email', '=', $email)->first();
                $checkedToken = $taxiUser->token;
//                echo "token is " . $token . " checked token is" . $checkedToken;

                if ($checkedToken === $token) {
                    return TRUE;
                }
                return FALSE;
            } catch (Exception $ex) {
                return FALSE;
            }
        }
    }
    
    protected function returnError($code, $error_str) {
        $error = array();
            $attributes = new stdClass;

            $attributes->code = $code;
            $attributes->error = $error_str;

            array_push($error, $attributes);

            return $error;
    }

}
