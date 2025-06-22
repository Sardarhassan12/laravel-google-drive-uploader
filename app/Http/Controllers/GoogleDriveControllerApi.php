<?php

namespace App\Http\Controllers;

use App\Models\GoogleDrive;
use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleDriveControllerApi extends Controller
{
    //

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->scopes(['https://www.googleapis.com/auth/drive.file'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent select_account'])
            ->redirect();
        
    }

    public function handleCallback()
    {
        
        $googleUser = Socialite::driver('google')->stateless()->user();
        
        // $googleUserCredentials = GoogleDrive::create([
        //     'access_token' => $googleUser->token,
        //     'refresh_token' => $googleUser->refreshToken,
        //     'token_expire_at' => Carbon::now()->addSeconds($googleUser->expiresIn),
        // ]);

        $tokenArray = [
            'access_token' => $googleUser->token,
            'refresh_token' => $googleUser->refreshToken,
            'expires_in' => $googleUser->expiresIn,
            'created' => time(), 
        ];

        $googleUserCredentials = GoogleDrive::create([
            'token_json' => json_encode($tokenArray),
            'refresh_token' => $googleUser->refreshToken, 
        ]);

        return response()->json([
            'data' => $googleUserCredentials
        ]);
    }

    public function uploadFile(Request $request)
    {

        $file = $request->validate([
            'file' => 'required|file|mimes:pdf'
        ]);
        // $accessToken = GoogleDrive::first()->access_token;
        $record = GoogleDrive::first();
        $token = json_decode($record->token_json, true);

        if(!$token){
            return response()->json([
                'error' => "Token Not Set"
            ]);
        }

        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($token);

        if($client->isAccessTokenExpired()){
            return response()->json([
                'error' => "Token Expired"
            ]);
        }

        $driveService = new Drive($client);

        $fileMetaData = new DriveFile([
            'name' => $request->file('file')->getClientOriginalName(),
            'parents' => ['root']
        ]);

        $file = $driveService->files->create($fileMetaData, [
            'data' => file_get_contents($request->file('file')->getRealPath()),
            'mimeType' => $request->file('file')->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => '*',
        ]);

        return response()->json([
            'success' => 'File Uploaded'
        ]);
    }
}
