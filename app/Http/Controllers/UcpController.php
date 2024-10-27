<?php

namespace App\Http\Controllers;

use App\Models\PlayerUcp;
use App\Models\User;
use Illuminate\Http\Request;
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
            'ucp' => 'required',
            'password' => 'required',
            'pin' => 'required|numeric'
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
            // 1. Generate a random 16-character salt
            $salt = '';
            for ($i = 0; $i < 16; $i++) {
                $salt .= chr(random_int(33, 126)); // Generates characters from ASCII range 33-126
            }
    
            // 2. Hash the password with SHA-256 using the salt
            $hashedPassword = hash('sha256', $salt . $request->password);
    
            // BEGIN UPDATE DATA TO DATABASE
            $new_ucp = new User();
            $new_ucp->ucp = $request->ucp;
            $new_ucp->verifycode = $request->pin;
            $new_ucp->password = $hashedPassword;
            $new_ucp->salt = $salt;
            $new_ucp->extrac = 0;
            $new_ucp->save();
            DB::commit();

            return response()->json([
                'code' => 200,
                'message' => 'Register Success',
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
}
