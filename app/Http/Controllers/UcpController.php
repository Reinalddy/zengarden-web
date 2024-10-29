<?php

namespace App\Http\Controllers;

use App\Models\PlayerUcp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UcpController extends Controller
{
    public function login_index() {
        return view('member.login.login');
    }

    public function register_index() {
        return view('member.register.register');
    }

    public function register(Request $request) {
        $valdidated = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users',
        ]);
        if ($valdidated->fails()) {
            return response()->json([
                'code' => 422,
                'data' => $valdidated->errors(),
                'message' => $valdidated->messages()
            ]);
        }

        try {
            DB::beginTransaction();
            
    
            // BEGIN UPDATE DATA TO DATABASE
            $new_user = new User();
            $new_user->name = $request->name;
            $new_user->email = $request->email;
            $new_user->password = bcrypt($request->password);
            $new_user->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Register Success',
                'data' => $new_user
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }

    }

    public function login(Request $request) {
        $valdidated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($valdidated->fails()) {
            return response()->json([
                'code' => 422,
                'data' => $valdidated->errors(),
                'message' => $valdidated->messages()
            ]);
        }

        try {
            
            // BEGIN PROSES LOGIN
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'code' => 400,
                    'message' => 'User Not Found'
                ]);
            }

            if (!password_verify($request->password, $user->password)) {
                return response()->json([
                    'code' => 400,
                    'message' => 'Wrong Password'
                ]);
            }

            // SEMUA DATA USER VALID LOGINKAN USER
            Auth::login($user);

            return response()->json([
                'code' => 200,
                'message' => 'Login Success',
                'data' => $user
            ]);

            
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function dashboard_index(Request $request) {
        $user_data = Auth::user();
        return view('member.dashboard.dashboard', [
            'user' => $user_data
        ]);
    }

    public function ucp_index(Request $request) {
        $user_data = Auth::user();
        $ucp_account = DB::table('playerucp')->where('user_id', $user_data->id)->first();
        return view('member.dashboard.ucp.ucp', [
            'user' => $user_data,
            'ucp' => $ucp_account
        ]);
    }

    public function createUcp(Request $request) {
        $validated = Validator::make($request->all(), [
            'pin' => 'required|numeric'
        ]);
        $user_data = Auth::user();
        try {

            DB::beginTransaction();
            $new_ucp = new PlayerUcp();
            $new_ucp->ucp = $user_data->name;
            $new_ucp->verifycode = $request->pin;
            $new_ucp->extrac = 0;
            $new_ucp->user_id = $user_data->id;
            $new_ucp->password = "";
            $new_ucp->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Register Ucp Success',
                'data' => $new_ucp
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => $th->getMessage()
            ]);
        }


    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
