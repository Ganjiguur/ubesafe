<?php

/**
 * Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2015, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
use Cake\Core\Configure;
use Cake\Routing\Router;

$config = [
    'Users' => [
        //Table used to manage users
        'table' => 'AppUsers',
        // Controller used to manage users plugin features & actions
        'controller' => 'AppUsers',
        //configure Auth component
        'auth' => true,
        //Password Hasher
        'passwordHasher' => '\Cake\Auth\DefaultPasswordHasher',
        //token expiration, 1 hour
        'Token' => ['expiration' => 3600],
        'Email' => [
            //determines if the user should include email
            'required' => true,
            //determines if registration workflow includes email validation
            'validate' => true,
        ],
        'Registration' => [
            //determines if the register is enabled
            'active' => false,
            //determines if the reCaptcha is enabled for registration
            'reCaptcha' => false,
            //allow a logged in user to access the registration form
            'allowLoggedIn' => false,
            //ensure user is active (confirmed email) to reset his password
            'ensureActive' => false,
            // default role name used in registration
            'defaultRole' => 'moderator',
        ],
        'reCaptcha' => [
            //reCaptcha key goes here
            'key' => '6LcG9bYUAAAAAF7xOLltFUg3AV4Ubx-jSgS8S1fp',
            //reCaptcha secret
            'secret' => '6LcG9bYUAAAAAJ9aHOF8_nz4JKgKO6nM-8DqA2eu',
            //use reCaptcha in registration
            'registration' => false,
            //use reCaptcha in login, valid values are false, true
            'login' => true,
            //use reCaptcha in hr application submit
            'hrApplication' => true,
            //use reCaptcha in dynamic form submit
            'formSubmit' => true,
        ],
        'Tos' => [
            //determines if the user should include tos accepted
            'required' => false,
        ],
        'Social' => [
            //enable social login
            'login' => false,
        ],
        'GoogleAuthenticator' => [
            //enable Google Authenticator
            'login' => false,
            'issuer' => null,
            // The number of digits the resulting codes will be
            'digits' => 6,
            // The number of seconds a code will be valid
            'period' => 30,
            // The algorithm used
            'algorithm' => 'sha1',
            // QR-code provider (more on this later)
            'qrcodeprovider' => null,
            // Random Number Generator provider (more on this later)
            'rngprovider' => null,
            // Key used for encrypting the user credentials, leave this false to use Security.salt
            'encryptionKey' => false
        ],
        'Profile' => [
            //Allow view other users profiles
            'viewOthers' => true,
            'route' => ['plugin' => null, 'controller' => 'AppUsers', 'action' => 'view'],
        ],
        'Key' => [
            'Session' => [
                //session key to store the social auth data
                'social' => 'Users.social',
                //userId key used in reset password workflow
                'resetPasswordUserId' => 'Users.resetPasswordUserId',
            ],
            //form key to store the social auth data
            'Form' => [
                'social' => 'social'
            ],
            'Data' => [
                //data key to store the users email
                'email' => 'email',
                //data key to store email coming from social networks
                'socialEmail' => 'info.email',
                //data key to check if the remember me option is enabled
                'rememberMe' => 'remember_me',
            ],
        ],
        //Avatar placeholder
        'Avatar' => ['placeholder' => 'defaultprofile.png'],
        'RememberMe' => [
            // configure Remember Me component
            'active' => false,
            'checked' => true,
            'Cookie' => [
                'name' => 'remember_me',
                'Config' => [
                    'expires' => '1 minutes',
                    'httpOnly' => true,
                ]
            ]
        ],
    ],
    'GoogleAuthenticator' => [
        'verifyAction' => [
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => 'verify',
            'prefix' => false,
        ],
    ],
//default configuration used to auto-load the Auth Component, override to change the way Auth works
    'Auth' => [
        'loginAction' => [
            'plugin' => null,
            'controller' => 'AppUsers',
            'action' => 'login',
            'prefix' => false
        ],
        'authenticate' => [
            'all' => [
                'finder' => 'active',
            ],
//            'CakeDC/Auth.ApiKey',
            'CakeDC/Auth.RememberMe',
            'Form',
        ],
        'authorize' => [
            'CakeDC/Auth.Superuser',
            'CakeDC/Auth.SimpleRbac',
        ],
    ],
    'OAuth' => [
        'path' => ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'socialLogin', 'prefix' => null],
        'providers' => [
            'facebook' => [
                'className' => 'League\OAuth2\Client\Provider\Facebook',
                'options' => [
                    'graphApiVersion' => 'v2.5',
                    'redirectUri' => Router::fullBaseUrl() . '/auth/facebook',
                ]
            ],
            'twitter' => [
                'options' => [
                    'redirectUri' => Router::fullBaseUrl() . '/auth/twitter',
                ]
            ],
            'linkedIn' => [
                'className' => 'League\OAuth2\Client\Provider\LinkedIn',
                'options' => [
                    'redirectUri' => Router::fullBaseUrl() . '/auth/linkedIn',
                ]
            ],
            'instagram' => [
                'className' => 'League\OAuth2\Client\Provider\Instagram',
                'options' => [
                    'redirectUri' => Router::fullBaseUrl() . '/auth/instagram',
                ]
            ],
            'google' => [
                'className' => 'League\OAuth2\Client\Provider\Google',
                'options' => [
                    'userFields' => ['url', 'aboutMe'],
                    'redirectUri' => Router::fullBaseUrl() . '/auth/google',
                ]
            ],
        ],
    ]
];

return $config;
