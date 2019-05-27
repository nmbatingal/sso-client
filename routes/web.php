<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/login/{provider}', 'SocialAuthGoogleController@redirectToProvider')->name('socialite.login');
// Route::get('/login/{provider}/callback', 'SocialAuthGoogleController@handleProviderCallback');

// Route::get('/home', 'HomeController@index')->name('home');

use Illuminate\Http\Request;

Route::get('/', function () {
    // $query = http_build_query([
    //     'client_id' => 3, // Replace with Client ID
    //     'redirect_uri' => 'http://127.0.0.1:8004/callback',
    //     'response_type' => 'code',
    //     'scope' => ''
    // ]);

    // return redirect('http://127.0.0.1:8000/oauth/authorize?'.$query);

    $client = new GuzzleHttp\Client;

    $response = $client->post('http://127.0.0.1:8000/oauth/token', [
        'form_params' => [
            'client_id' => 2,
            // The secret generated when you ran: php artisan passport:install
            'client_secret' => 'v376otRTufq5ZhmE16H5GYfO7JNiqGZosYF9OkjV',
            'grant_type' => 'password',
            'username' => 'batingalnarz11@gmail.com',
            'password' => 'nmbatingal',
            'scope' => '*',
        ]
    ]);

    session()->put('token', json_decode((string) $response->getBody(), true));

    return redirect('/todos');

});

// Route::get('/callback', function (Request $request) {
//     $response = (new GuzzleHttp\Client)->post('http://127.0.0.1:8000/oauth/token', [
//         'form_params' => [
//             'grant_type' => 'authorization_code',
//             'client_id' => 3, // Replace with Client ID
//             'client_secret' => 'pfxTwgsCCY7nNGZl1jK9DgiEmRMqswvzx4eGUJII', // Replace with client secret
//             'redirect_uri' => 'http://127.0.0.1:8004/callback',
//             'code' => $request->code,
//         ]
//     ]);

//     session()->put('token', json_decode((string) $response->getBody(), true));

//     return redirect('/todos');
// });

Route::get('/todos', function () {

    $client   = new GuzzleHttp\Client;

    $response = $client->get('http://127.0.0.1:8000/api/todos', [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.session()->get('token.access_token')
        ]
    ]);

    // return Response::view('hello')->header('Content-Type', $type);
    // return json_decode((string) $response->getBody(), true);
    return view('todos');
});