<?php

namespace App\Controllers;


use CodeIgniter\HTTP\RedirectResponse;

class UserController extends BaseController
{
    /**
     * User profile
     * @return RedirectResponse|string
     */
    public function index()
    {
        // Check for active user session
        if (! $this->session->has('isLoggedIn')) return redirect()->to(base_url('login'));
        
        // Check that supplied user token is valid
        if ($this->session->has('id')) {
            $user = $this->userModel->find($this->session->id);
        }
        
        return view('profile', [
            'title' => 'User profile',
            'user' => $user,
            'photos' => $this->photoModel->where('user_id', $this->session->id)->findAll(),
        ]);
    }
    
    /**
     * Register user account
     * @return RedirectResponse|string
     */
    public function register()
    {
        // Check for active user session
        if ($this->session->has('isLoggedIn'))  return redirect()->to(base_url('profile'));
        
        if ($this->request->is('post')) {
            $data = [
                'first_name' => trim($this->request->getPost('first_name', FILTER_SANITIZE_STRING)),
                'last_name' => trim($this->request->getPost('last_name', FILTER_SANITIZE_STRING)),
                'email_address' => trim($this->request->getPost('email_address', FILTER_SANITIZE_STRING)),
                'screen_name' => trim($this->request->getPost('screen_name', FILTER_SANITIZE_STRING)),
                'password' => $this->request->getPost('password'),
                'confirm_password' => $this->request->getPost('confirm_password'),
                'token' => random_string('alnum', 16),
                'created_at' => $this->time->toDateTimeString()
            ];
            
            if ($this->userModel->save($data)) {
                return redirect()->to(base_url('login'))->with('notice', '
                    <div class="ui success message">
                        <i class="close icon"></i>
                        <div class="header">
                            Your user registration was successful.
                        </div>
                        <p>You may now log in with the email and password you have chosen.</p>
                    </div>
                ');
            }

            $data = ['signup_errors' => $this->userModel->errors(), 'title' => 'Register'];
            return view('register', $data);
        }
        
        return view('register', [
            'title' => 'Register',
        ]);
    }
    
    public function login()
    {
        // Check for active user session
        if ($this->session->has('isLoggedIn')) return redirect()->to(base_url('profile'));
        
        $data['errors'] = '';
        
        if ($this->request->is('post')) {
            $rules = [
                'email_address' => [
                    'rules' => 'required|valid_email',
                    'errors' => [
                        'required' => 'Email address is required!',
                        'valid_email' => 'Email address in not valid!'
                    ]
                ],
                'password' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Password is required!'
                    ]
                ]
            ];

            $data = [
                'email_address' => trim($this->request->getPost('email_address', FILTER_SANITIZE_STRING)),
                'password' => $this->request->getPost('password')
            ];
    
            if ($this->validateData($data, $rules)) {
                // Check that user email exists in the database
                if ($user = $this->userModel->where('email_address', $data['email_address'])->first()) {
                    // Compare password with hashed password
                    if (password_verify($data['password'], $user->password)) {
                        // Set session
                        $this->session->set([
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email_address' => $user->email_address,
                            'screen_name' => $user->screen_name,
                            'isLoggedIn' => TRUE
                        ]);
                
                        // Redirect to user page
                        return redirect()->to(base_url('profile'))->with('notice', '
                            <div class="ui success message">
                                <i class="close icon"></i>
                                <div class="header">
                                    Login successful!
                                </div>
                                <p>Welcome ' . ucwords($user->first_name . ' ' . $user->last_name) .  '</p>
                            </div>'
                        );
                    }
            
                    // Invalid password supplied
                    $this->session->setFlashData('notice', '
                        <div class="ui negative message">
                            <i class="close icon"></i>
                            <div class="header">
                                Login failed!
                            </div>
                            <p>Your username / password is incorrect!</p>
                        </div>
                    ');
            
                    return redirect()->to(base_url('login'))->withInput();
                }
        
                $this->session->setFlashData('notice', '
                    <div class="ui negative message">
                         <i class="close icon"></i>
                        <div class="header">
                            User not found!
                        </div>
                        <p>Your account is not on record! <a href="' . base_url('register') . '">Register</a> to create an account.</p>
                    </div>'
                );
        
                return redirect()->to(base_url('login'))->withInput();
            }

            $data = ['login_errors' => $this->validator->getErrors(), 'title' => 'Login'];
            return view('login', $data);
        }
    
        return view('login', [
            'title' => 'Login',
            'errors' => validation_errors()
        ]);
    }

    /**
     * Logout
     * @return RedirectResponse
     */
    public function logout()
    {
        $this->session->destroy();
        $this->session->remove('oauthToken');
        $this->session->remove('screen_name');

        return redirect()->to(base_url('login'));
    }
}
