<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
            ['login','register','handleProviderCallback','redirectToProvider','loginWithoutPassword','registerWithoutPassword']
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email'=>'required|email|unique:users',
            'password'=>'required|min:4|max:12'
        ]);
        $credentials = $request->only('email', 'password');
        $name = $request->email;
        $email = $request->email;
        $password = $request->password;
        $user = new User();
        $user->name = $name;
        $user->email = $name;
        $user->password = Hash::make($password);
        $user->save();
        return $this->login($request);

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|min:4|max:12'
        ]);
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
        // return 're';
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()

    {
        // $newToken = JWTAuth::parseToken()->refresh();
        return $this->respondWithToken(auth()->refresh());

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 3600,
            'user'=>auth()->user()->name,
            'user_id'=>auth()->user()->id,
        ]);
    }

    public function redirectToProvider()
    {
//        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {

//        $user_soc = Socialite::driver('facebook')->user();
//
////        return $user_soc->name;
//        $findUser = User::where('email', $user_soc->email)->first();
//        if ($findUser) {
//            Auth::login($findUser);
//            return redirect()->route('home');
//        } else {
//            $user = new User();
//            $user->name = $user_soc->name;
//            $user->email = $user_soc->email;
//            $user->password = bcrypt(123456);
//            $user->save();
//            Auth::login($user);
//            return redirect()->route('home');
//
//        }

//        $user_soc = Socialite::driver('facebook')->user();
        return response([
            'data'=>"rererer"
        ]);

    }

    public function loginWithoutPassword(Request $request) {
        $user = User::where('email', '=', request(['email']))->firstOrFail();
        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }
    public function registerWithoutPassword(Request $request) {
//        $credentials = $request->only('email', 'password');
        $name = $request->email;
        $email = $request->email;
        $password = '123456';
        $user = new User();
        $user->name = $name;
        $user->email = $name;
        $user->password = Hash::make($password);
        $user->save();
        return $this->loginWithoutPassword($request);
    }
}