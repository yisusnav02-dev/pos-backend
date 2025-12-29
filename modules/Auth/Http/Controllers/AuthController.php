<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auth\Models\User;
use Modules\Auth\Models\OtpCode;
use Illuminate\Support\Carbon;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function requestCode(Request $request)
    {
        $request->validate(['phone_number' => 'required|string']);

        $code = rand(100000, 999999);

        OtpCode::updateOrCreate(
            ['phone_number' => $request->phone_number],
            ['code' => $code, 'expires_at' => now()->addMinutes(5)]
        );

        $this->sendWhatsappMessage($request->phone_number, $code);

        return response()->json(['message' => 'Código enviado correctamente']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'code' => 'required|string'
        ]);

        $otp = OtpCode::where('phone_number', $request->phone_number)
                      ->where('code', $request->code)
                      ->where('expires_at', '>', now())
                      ->first();

        if (!$otp) {
            return response()->json(['message' => 'Código inválido o expirado'], 401);
        }

        $user = User::firstOrCreate(['phone_number' => $request->phone_number]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function logoutAllDevices(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesiones cerradas en todos los dispositivos']);
    }

    private function sendWhatsappMessage($to, $code)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $from = 'whatsapp:' . env('TWILIO_WHATSAPP_FROM');

        $client = new Client($sid, $token);
        $client->messages->create(
            "whatsapp:$to",
            [
                'from' => $from,
                'body' => "Tu código de acceso es: $code (válido 5 minutos)"
            ]
        );
    }
}
