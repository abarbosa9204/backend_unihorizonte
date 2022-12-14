<?php

namespace App\Http\Controllers\Auth;

use DB;
use \Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\Api\BaseController as BaseController;
use \App\Models\User;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use \Illuminate\Support\Facades\Hash;
use \Illuminate\Validation\Rules\Password;
use \Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use GrahamCampbell\ResultType\Result;
use PhpParser\Node\Stmt\TryCatch;
use Throwable;

class AuthController extends BaseController
{
    private $settings;
    public function __construct()
    {
        $this->middleware('client-credentials')->only('*');
        $this->settings = $this->settings();
    }
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'         =>  ['required', 'string', 'email', 'max:255'],
                'password'      =>  ['required'],
                'remember_me'   =>  ['boolean']
            ]);
            if ($validator->fails()) {
                return response([
                    "status"    =>  Response::HTTP_UNPROCESSABLE_ENTITY,
                    "message"   =>  "Error procesando la solicitud, por favor validar los daots ingresados",
                    "errors"    =>  $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user   =   Auth::user();
                $accessToken = $user->createToken($this->settings['SECRET_AUTH'])->accessToken;                
                return response([
                    "status"        =>  Response::HTTP_OK,
                    "message"       =>  "successfully",
                    "user"          =>  Auth::user()->id,
                    "email"         =>  Auth::user()->email,
                    "acces_token"   =>  $accessToken
                ], Response::HTTP_OK);
            } else {
                return response([
                    'status'    =>  Response::HTTP_UNAUTHORIZED,
                    'message'   =>  'Unauthorised'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Throwable $th) {
            return response([
                'status'    =>  Response::HTTP_UNAUTHORIZED,
                'message'   =>  'No autorizado'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
