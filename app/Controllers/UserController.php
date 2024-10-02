<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{

	private $session;
    private $UserModel;
    public function __construct()
    {
        helper(['form', 'url','session']);
        $this->UserModel = new UserModel();
        $this->session = session();
    }
    public function register()
    {
        return view('register');
    }

    public function UserRegister()
    {
        helper(['form', 'url']);

        // Define validation rules
        $validationRules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'username' => 'required',
            'password' => 'required|min_length[6]|max_length[10]',
        ];

        if (!$this->validate($validationRules)) {
            return view('register', [
                'validation' => $this->validator // Pass the validator object
            ]);
        } else {
            $userModel = new UserModel();

            $existingEmail = $userModel->where('email', $this->request->getVar('email'))->first();
            if ($existingEmail) {
                $this->validator->setError('email', 'Email already exists');
                return view('register', [
                    'validation' => $this->validator // Pass the validator object
                ]);
            }

            $existingUsername = $userModel->where('username', $this->request->getVar('username'))->first();
            if ($existingUsername) {
                $this->validator->setError('username', 'Username already exists');
                return view('register', [
                    'validation' => $this->validator // Pass the validator object
                ]);
            }

            $password = $this->request->getVar('password');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $userModel->save([
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'username' => $this->request->getVar('username'),
                'password' => $hashedPassword // Save the hashed password
            ]);

            // Set success message in session
            $session = \Config\Services::session();
            $session->setFlashdata('success', 'User Registration Successfully');

            return redirect()->to('/login'); // Redirect after registration
        }
    }



    public function login()
    {
        return view('login');
    }


    public function UserLogin()
    {
        
        helper(['form', 'url']);

        // Define validation rules
        $validationRules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]|max_length[10]',
        ];

        // Validate the form input
        if (!$this->validate($validationRules)) {
            return view('login', [
                'validation' => $this->validator // Pass the validator object
            ]);
        } else {
            // Get user input
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');

            // Check if the email exists in the database
            $existingUser =  $this->UserModel->where('email', $email)->first(); // Ensure this returns the expected data type

            if ($existingUser) {
                $hashedPassword = $existingUser['password'];

                if (password_verify($password, $hashedPassword)) {
                    $sessionData = [
                        'id' => $existingUser['id'],
                        'name' => $existingUser['name'],
                        'username' => $existingUser['username'],
                        'email' => $existingUser['email'],
                        'loggedIn' => true,
                    ];
                    $this->session->set($sessionData);
                    log_message('info', 'User logged in successfully: ' . $email); // Log login success
                    return redirect()->to('/'); // Redirect to home page
                }
                else {
                    $this->session->setFlashdata('error', 'Email or password is incorrect');
                    return redirect()->to('/login');
                }
            } else {
                $this->session->setFlashdata('error', 'Email does not match any account');
                return redirect()->to('/login');
            }
        }
    }


    public function logout()
	{
		$session = session();
		$session->destroy();
		return redirect()->to('login');
	}


}
