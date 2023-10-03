<?php

namespace App\Controllers;

require APPPATH . '../vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use CodeIgniter\HTTP\RedirectResponse;

class TwitterAuth extends BaseController
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $oauthCallback;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->consumerKey = getenv('TWITTER_CONSUMER_KEY');
        $this->consumerSecret = getenv('TWITTER_CONSUMER_SECRET');
        $this->oauthCallback = getenv('TWITTER_OAUTH_CALLBACK');
    }

    /**
     * Authentication
     * @return RedirectResponse|false
     * @throws \Abraham\TwitterOAuth\TwitterOAuthException
     */
    public function index()
    {
        //
        $requestToken = [];
        $requestToken['oauth_token'] = $this->session->oauthToken;
        $requestToken['oauth_token_secret'] = $this->session->oauthTokenSecret;
    
        $oauthToken = $this->request->getVar('oauth_token');
    
        if (isset($oauthToken) && $requestToken['oauth_token'] !== $oauthToken) {
            // Abort! Something is wrong
            return false;
        }
    
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $requestToken['oauth_token'],
            $requestToken['oauth_token_secret']);
    
        if (! $this->request->getVar('oauth_verifier')) {
            return redirect()->to(base_url('login'));
        }

        $accessToken = $connection->oauth("oauth/access_token", [
            "oauth_verifier" => $this->request->getVar('oauth_verifier')
        ]);

        $this->session->set('accessToken', $accessToken);

        $twitterAccessToken = $this->session->accessToken;

        $user = $this->userModel->where('screen_name', $twitterAccessToken['screen_name'])->first();

        if ($user) {
            // Save user to session
            $this->session->set([
                'userID' => $user->id,
                'screen_name' => $user->screen_name,
                'email_address' => $user->email_address
            ]);

             // Redirect user to upload page
             return redirect()->to(base_url('photos'));
        }

        return false;
    }

    /**
     * Login
     * @return RedirectResponse|false
     * @throws \Abraham\TwitterOAuth\TwitterOAuthException
     */
    public function login()
    {
        // Invalidate/remove existing tokens
        if ($this->session->has('oauthToken') || $this->session->has('oauthTokenSecret') || $this->session->has('twitterAccessToken')) {
            return redirect()->to(base_url('photos'));
        }
        
        // Instantiate Twitter authentication
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        
        $requestToken = $connection->oauth('oauth/request_token', [
            'oauth_callback' => base_url($this->oauthCallback)
        ]);

        // Dave tokens in session
        $this->session->set('oauthToken', $requestToken['oauth_token']);
        $this->session->set('oauthTokenSecret', $requestToken['oauth_token_secret']);
        
        $authorizeURL = $connection->url('oauth/authorize', [
            'oauth_token' => $requestToken['oauth_token']
        ]);

        // If token is valid, redirect to auth URL
        if ($this->session->has('oauthToken')) {
            return redirect()->to($authorizeURL);
        }
        
        return false;
    }

    /**
     * Tweet photo
     * @return RedirectResponse|void
     * @throws TwitterOAuthException
     */
    public function tweet()
    {
        // Check if up;oad form was submitted
        if ($this->request->is('post')) {
            $caption = $this->request->getPost('caption');
            $photo = $this->request->getPost('photo');

            $twitterAccessToken = $this->session->accessToken;

            $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitterAccessToken['oauth_token'],
                $twitterAccessToken['oauth_token_secret']);

            $connection->setApiVersion('1.1');

            // $connection->setTimeouts(30, 60);

            $pic = $connection->upload('media/upload', ['media' => FCPATH . 'uploads/' . $photo]);

            /*$parameters['text'] = $caption;
            $parameters['media']['media_ids'][0] = $pic->media_id_string;*/

            $parameters = [
                'text' => $caption,
                'media' => [
                    'media_ids' => [$pic->media_id_string],
                ]
            ];

            $connection->setApiVersion('2');

            // Tweet photo
            $connection->post('tweets', $parameters);

            // Return to photo gallery with success notification
            return redirect()->back()->with('notice', 'Your photo (' . $caption . ') was tweeted successfully!
                <br><a href="https://twitter.com/' . $twitterAccessToken["screen_name"] . '" target="_blank">View tweet</a>');
        }
    }
}
