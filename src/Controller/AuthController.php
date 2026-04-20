<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsersTable;
use Cake\I18n\DateTime;
use Cake\Mailer\Mailer;
use Cake\Utility\Security;

/**
 * Auth Controller
 *
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class AuthController extends AppController
{
    /**
     * @var \App\Model\Table\UsersTable $Users
     */
    private UsersTable $Users;

    /**
     * Controller initialize override
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated(['login', 'register', 'forgetPassword', 'resetPassword']);

        $this->Users = $this->fetchTable('Users');

        // Load Turnstile CAPTCHA component
        $this->loadComponent('Turnstile');
    }

    /**
     * Register method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            $data['role'] = 'customer';

            // Password confirmation check
            if (empty($data['password_confirm']) || $data['password'] !== $data['password_confirm']) {
                $this->Flash->error('Passwords do not match. Please try again.');
                $this->set(compact('user'));
                return;
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('Account created! Please log in.');
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('The account could not be created. Please check your details and try again.');
        }
        $this->set(compact('user'));
    }

    /**
     * Forget Password method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful email send, renders view otherwise.
     */
    public function forgetPassword()
    {
        if ($this->request->is('post')) {
            // Retrieve the user entity by provided email address
            // Note findBy* is a magic/dynamic finder
            $user = $this->Users->findByEmail($this->request->getData('email'))->first();
            if ($user) {
                // Set nonce and expiry date
                $user->nonce = Security::randomString(128);
                $user->nonce_expiry = new DateTime('7 days');
                if ($this->Users->save($user)) {
                    // Now let's send the password reset email
                    $mailer = new Mailer('default');

                    // email basic config
                    $mailer
                        ->setEmailFormat('both')
                        ->setTo($user->email)
                        ->setSubject('Reset your account password');

                    // select email template
                    $mailer
                        ->viewBuilder()
                        ->setTemplate('reset_password');

                    // transfer required view variables to email template
                    $mailer
                        ->setViewVars([
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'nonce' => $user->nonce,
                            'email' => $user->email,
                        ]);

                    //Send email
                    if (!$mailer->deliver()) {
                        // Just in case something goes wrong when sending emails
                        $this->Flash->error('We have encountered an issue when sending you emails. Please try again. ');

                        return $this->render(); // Skip the rest of the controller and render the view
                    }
                } else {
                    // Just in case something goes wrong when saving nonce and expiry
                    $this->Flash->error('We are having issue to reset your password. Please try again. ');

                    return $this->render(); // Skip the rest of the controller and render the view
                }
            }

            /*
             * **This is a bit of a special design**
             * We don't tell the user if their account exists, or if the email has been sent,
             * because it may be used by someone with malicious intent. We only need to tell
             * the user that they'll get an email.
             */
            $this->Flash->success(
                'Please check your inbox (or spam folder) for ' .
                'an email regarding how to reset your account password. ',
            );

            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Reset Password method
     *
     * @param string|null $nonce Reset password nonce
     * @return \Cake\Http\Response|null|void Redirects on successful password reset, renders view otherwise.
     */
    public function resetPassword(?string $nonce = null)
    {
        // Bounce user away if no nonce provided
        if (!$nonce) {
            return $this->redirect(['action' => 'login']);
        }

        // Note findBy* is a magic/dynamic finder
        $user = $this->Users->findByNonce($nonce)->first();

        // If nonce cannot find the user, or nonce is expired, prompt for re-reset password
        if (!$user || $user->nonce_expiry < DateTime::now()) {
            $this->Flash->error('Your link is invalid or expired. Please try again.');

            return $this->redirect(['action' => 'forgetPassword']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            if ($this->request->getData('password') !== $this->request->getData('password_confirm')) {
                $this->Flash->error('Passwords do not match. Please try again.');
                $this->set(compact('user'));

                return;
            }

            // Used a different validation set in Model/Table file to ensure both fields are filled
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'resetPassword']);

            // Also clear the nonce-related fields on successful password resets.
            // This ensures that the reset link can't be used a second time.
            $user->nonce = null;
            $user->nonce_expiry = null;

            if ($this->Users->save($user)) {
                $this->Flash->success('Your password has been successfully reset. Please login with new password. ');

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error('The password cannot be reset. Please try again.');
        }

        $this->set(compact('user'));
    }

    /**
     * Change Password method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function changePassword()
    {
        $userId = $this->Authentication->getIdentity()->get('id');
        $user = $this->Users->get($userId);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $currentPassword = $this->request->getData('current_password');
            $newPassword     = $this->request->getData('password');
            $confirmPassword = $this->request->getData('confirm_password');

            // Verify current password first
            if (!password_verify($currentPassword, $user->password)) {
                $this->Flash->error('Your current password is incorrect.');
                $this->set(compact('user'));
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $this->Flash->error('New password and confirmation do not match.');
                $this->set(compact('user'));
                return;
            }

            $user = $this->Users->patchEntity($user, ['password' => $newPassword], ['validate' => 'resetPassword']);
            if ($this->Users->save($user)) {
                $this->Flash->success('Your password has been updated successfully.');
                return $this->redirect(['controller' => 'Profile', 'action' => 'index']);
            }
            $this->Flash->error('The password could not be updated. Please try again.');
        }
        $this->set(compact('user'));
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Redirect to location before authentication
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);

        if ($this->request->is('post')) {
            $turnstileToken = $this->request->getData('cf-turnstile-response');

            if (!$turnstileToken) {
                $this->Flash->error('Please complete the CAPTCHA challenge.');
                return;
            }

            $turnstileResponse = $this->Turnstile->validateTurnstile(
                $turnstileToken,
                $this->request->clientIp()
            );

            if (!$turnstileResponse || empty($turnstileResponse['success'])) {
                $this->log('Login Turnstile Error: ' . json_encode($turnstileResponse));
                $this->Flash->error('CAPTCHA challenge failed. Please try again.');
                return;
            }
        }

        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $user = $this->request->getAttribute('identity');

            if ($user && in_array($user->role, ['admin', 'staff'])) {
                return $this->redirect('/dashboard');
            }

            $fallbackLocation = '/';
            return $this->redirect($this->Authentication->getLoginRedirect() ?? $fallbackLocation);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Email address and/or Password is incorrect. Please try again.');
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function logout()
    {
        // We only need to log out a user when they're logged in
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            $this->Flash->success('You have been logged out successfully. ');
        }

        // Otherwise just send them to the login page
        return $this->redirect(['controller' => 'Auth', 'action' => 'login']);
    }
}
