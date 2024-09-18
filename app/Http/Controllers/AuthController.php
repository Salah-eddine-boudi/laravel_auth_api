<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    // Le constructeur doit avoir deux underscores
    public function __construct(){
        $this -> middleware('auth:api', ['except' => ['login', 'register']]);
    }

    // Méthode pour l'enregistrement
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);
    
        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
    
        $user = User::create(array_merge(
            $validator->validated(),[  'password' => bcrypt($request->password)]
        ));
          
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
    

    // Méthode pour la connexion
    public function login(Request $request){
        $credentials = $request->only('email', 'password');
        
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // Méthode pour retourner le token JWT
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function profile()
    {
        return response()->json(auth()->user());
    }
    public function logout(Request $request)
    {
        // Déconnecter l'utilisateur actuellement authentifié
        auth()->logout();
    
        // Retourner une réponse JSON indiquant que la déconnexion a été réussie
        return response()->json([
            'message' => 'User successfully logged out',
        ], 200); // Code 200 pour indiquer une réussite
    }
    

     
}
