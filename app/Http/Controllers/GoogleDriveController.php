<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleDriveController extends Controller
{
    //
    public function getClient(): Client
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri('http://127.0.0.1:8000/google/callback');
        $client->addScope(Drive::DRIVE_FILE);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->getClient();
        return redirect($client->createAuthUrl());
    }

    public function handleCallBack(Request $request)
    {
        $client = $this->getClient();
        $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

        if(isset($token['error'])){
            return redirect('/')->with('error', 'Google Auth Failed');
        }

        Session::put('google-token', $token);
        return redirect('/google/upload-form');
    }

    public function displayUploadForm()
    {
        return view('google-upload');
    }

    public function uploadFilee(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf'
        ]);

        $client = $this->getClient();
        $token = Session::get('google-token');

        if(!$token || !isset($token['access_token'])){
            return redirect('google/login')->with('error', 'Missing Or Invalid Goole Token.');
        }

        $client->setAccessToken($token);    

        if($client->isAccessTokenExpired()){
            return redirect('google/login')->with('error', 'Token Expired');
        }

        $driveService = new Drive($client);

        $fileMetaData = new Drive\DriveFile([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => ['root']
        ]);

        $file = $driveService->files->create($fileMetaData, [
            'data' => file_get_contents($request->file('file')->getRealPath()),
            'mimeType' => $request->file('file')->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => '*'
        ]);

        return back()->with('success', 'Uploaded! File ID: ' . $file->id);
    
    }
}
