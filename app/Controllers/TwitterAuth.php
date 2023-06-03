<?php

namespace App\Controllers;

require APPPATH . '../vendor/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

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
     * @return \CodeIgniter\HTTP\RedirectResponse|false
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
    
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $twitterAccessToken['oauth_token'],
            $twitterAccessToken['oauth_token_secret']);
    
        $twitterProfile = $connection->get('account/verify_credentials', [
            'tweet_mode' => 'extended', 'include_entities' => 'true'
        ]);
    
        if ($user = $this->userModel->where('screen_name', $twitterProfile->screen_name)->first()) {
            // Save user to session
            $this->session->set([
                'userID' => $user->id,
                'screen_name' => $user->screen_name,
                'email_address' => $user->email_address
            ]);

            $connection->setTimeouts(30, 60);

            /*
            // $photo = $connection->upload('media/upload', ['media' => FCPATH . 'uploads/1675799051.jpg']);
            $photo = $connection->upload('media/upload', ['media' => FCPATH . 'uploads/1685767735.jpg']);

            $parameters = [
                'status' => 'Meow Meow Meow',
                'media_ids' => $photo->media_id_string
            ];
            $result = $connection->post('statuses/update', $parameters);
            $resArray = json_decode(json_encode($result), true);
            print_r($resArray);
            print_r($resArray["entities"]["media"][0]["url"]);
            echo $result;
            */

             // Redirect user to upload page
             return redirect()->to(base_url('photos'));
        }
    
        return false;
    }

    /**
     * Login
     * @return \CodeIgniter\HTTP\RedirectResponse|false
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
     * @return \CodeIgniter\HTTP\RedirectResponse|void
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

            // $connection->setTimeouts(30, 60);

            $pic = $connection->upload('media/upload', ['media' => FCPATH . 'uploads/' . $photo]);

            $parameters = [
                'status' => $caption,
                'media_ids' => $pic->media_id_string
            ];

            // Tweet photo
            $connection->post('statuses/update', $parameters);

            // Return to photo gallery with success notification
            return redirect()->back()->with('notice', 'Your photo (' . $caption . ') tweet was successful!');
        }
    }
}
