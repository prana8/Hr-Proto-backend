<?php

namespace App\Http\Controllers;

use App\Models\ProfileForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login', 'register']]);
        }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $profile_form = ProfileForm::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);


        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => $user,
            'ProfileForm' => $profile_form,
            'authorization' => [
                'token' => $token,
                'type' => 'Bearer',
            ]
        ]);
    }

    public function login(Request $request){

        // untuk hit api data untuk sementara hardcode
        $apiData = [
            "grant_type" => "password",
            // "username" => $request->input('email'), 
            // "password" => $request->input('password'),
            "username" => 'admin', 
            "password" => 'admin',
            "company_code" => "hcm",
            "group" => "company",
            "client_id" => "psvJp4QNw6CAgXzfgATupKMvT8P1uOE68kvgeNXI",
            "client_secret" => "deJiuGhwchG7UinhD35egITOExc4vUSMCsjYokg2bQdTkka1vxpppnEdMPBpqOWw9rTI5AmqhxSmesQ1xvD0HReqAyn45MvMy62hh4SDwafrp1oUXW6cKmb8Un1rnE60"
        ];

        $ApiUrl = "https://d3v-manage-adm-api.guestpro.co.id/auth/token"; 
        $apiResponse = Http::post($ApiUrl, $apiData);

        $credentials = $request->only('email', 'password');
        $expirationToken = now()->addMinutes(JWTAuth::factory()->getTTL())->timestamp;
        $responseData = $apiResponse->json();

        if (isset($responseData['success']) !== false ) {

            if (!$token = JWTAuth::attempt($credentials, ['exp' => $expirationToken])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = Auth::user();

            return response()->json([
                'status' => true,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ],
                'authorization' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => $expirationToken,
                ],
                "acmResponse"=> $responseData,

            ]);

        } else {
            $errorResponseData = $apiResponse->json();
            return response()->json([
                'status' => $errorResponseData['success'],
                "message" => $errorResponseData['message'],
            ]);
        }
       
    }

    public function logout(Request $request)
    {
        
        // hit data api untuk sementara hardcode untuk client_id dan client_secret
        $revoke_token_acm = $request->input('acm_access_token');
        $revoke_token_hcm = $request->input('token');
        $ApiUrl = "https://d3v-manage-adm-api.guestpro.co.id/auth/revoke";
        $apiData = [
            "token" => $revoke_token_acm,
            "client_id" => "psvJp4QNw6CAgXzfgATupKMvT8P1uOE68kvgeNXI",
            "client_secret" => "deJiuGhwchG7UinhD35egITOExc4vUSMCsjYokg2bQdTkka1vxpppnEdMPBpqOWw9rTI5AmqhxSmesQ1xvD0HReqAyn45MvMy62hh4SDwafrp1oUXW6cKmb8Un1rnE60"
        ];

        $apiResponse = Http::post($ApiUrl, $apiData);
        $apiResponseJson = $apiResponse->json();

        Auth::logout($revoke_token_hcm);
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out',
            'acm_revoke' => $apiResponseJson,
        ]);
    }

    public function revokeToken(Request $request)
    {
        $token = $request->input('token');
        JWTAuth::invalidate($token);
        return response()->json(['message' => 'Token revoked']);
    }

    public function refresh(Request $request)
    {

        $ApiUrl = "https://d3v-manage-adm-api.guestpro.co.id/auth/refresh"; 
        $apiData = [
            "refresh_token" => $request->input('refresh_token'),
            "client_id" => "psvJp4QNw6CAgXzfgATupKMvT8P1uOE68kvgeNXI",
            "client_secret" => "deJiuGhwchG7UinhD35egITOExc4vUSMCsjYokg2bQdTkka1vxpppnEdMPBpqOWw9rTI5AmqhxSmesQ1xvD0HReqAyn45MvMy62hh4SDwafrp1oUXW6cKmb8Un1rnE60",
            "grant_type" => "refresh_token"
        ];

            $apiResponse = Http::post($ApiUrl, $apiData);
            $responseJson = $apiResponse->json();

            $newToken = Auth::refresh();
            $user = Auth::user();
            $expirationToken = now()->addMinutes(JWTAuth::factory()->getTTL())->timestamp;
          
            return response()->json([
                'status' => true,
                'authorization' => [
                    // 'user' => $user['name'],
                    'access_token' => $newToken,
                    "expires_in" => $expirationToken,
                    'type' => 'Bearer',
                ],
                "acm_authorisation" => $responseJson,
            ]);
    }

}
