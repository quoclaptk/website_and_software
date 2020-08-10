<?php namespace Modules\Auth;

use Modules\Models\Subdomain;
use Modules\Models\TmpSubdomainUser;
use Phalcon\Mvc\User\Component;
use Modules\Models\Users;
use Modules\Models\RememberTokens;
use Modules\Models\SuccessLogins;
use Modules\Models\FailedLogins;

/**
 * Eduapps\Auth\Auth
 *
 * Manages Authentication/Identity Management in Eduapps
 */
class Auth extends Component
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolean
     * @throws Exception
     */
    public function check($credentials)
    {

        // Check if the user exist
        $user = Users::findFirst(
            array(
                "conditions" => "active = 'Y' AND subdomain_id = ".$credentials['subdomain_id']." AND username = '".$credentials['username']."'",
            )
        );
        /*if (!$user) {
            $tmpSubdomainUser = TmpSubdomainUser::findBySubdomainId($credentials['subdomain_id']);
            if (count($tmpSubdomainUser) > 0) {
                $arrayUserId = [];
                foreach ($tmpSubdomainUser as $tmp) {
                    $arrayUserId[] = $tmp->user_id;
                }
                $arrayUserId = (count($arrayUserId) > 1) ? implode(",", $arrayUserId) : $arrayUserId[0];
                $user = Users::findFirst(
                    array(
                        "columns" => "*",
                        "conditions" => "active = 'Y' AND id IN ($arrayUserId) AND username = '".$credentials['username']."'",
                    )
                );
            }
        }*/
        // echo '<pre>'; print_r($user); echo '</pre>';die;
        if ($user == false) {
            // echo 'Wrong username/password combination';
            return false;
            // $this->registerUserThrottling(0);
//             throw new Exception('Wrong username/password combination');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            // echo 'Wrong username/password combination';
            return false;
            // $this->registerUserThrottling($user->id);
            // throw new Exception('Wrong username/password combination');
        }

        // Check if the user was flagged
        $this->checkUserFlags($user);

        // Register the successful login
        // $this->saveSuccessLogin($user);

        // Check if the remember me was selected
        if (isset($credentials['remember'])) {
            // $this->createRememberEnvironment($user);
        }

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'subdomain_id' => $user->subdomain_id,
            'subdomain_name' => $user->subdomain->name,
            'role' => $user->role,
            'profilesId' => $user->profilesId,
            'username' => $user->username,
            'profile' => $user->profile->name,
            'folder' => $user->subdomain->folder,
            'not_thumb' => $user->subdomain->not_thumb,
            'isLoggedIn' => true
        ]);

        $this->session->set('subdomain-child', [
            'subdomain_id' => $user->subdomain_id,
            'subdomain_name' => $user->subdomain->name,
            'not_thumb' => $user->subdomain->not_thumb,
            'folder' => $user->subdomain->folder,
            'host' => $_SERVER['HTTP_HOST'],
            'role' => $user->role,
        ]);
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Vokuro\Models\Users $user
     * @throws Exception
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new SuccessLogins();
        $successLogin->usersId = $user->id;
        $successLogin->ipAddress = $this->request->getClientAddress();
        $successLogin->userAgent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
        }
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->usersId = $userId;
        $failedLogin->ipAddress = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->save();

        $attempts = FailedLogins::count([
            'ipAddress = ?0 AND attempted >= ?1',
            'bind' => [
                $this->request->getClientAddress(),
                time() - 3600 * 6
            ]
        ]);

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Vokuro\Models\Users $user
     */
    public function createRememberEnvironment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->usersId = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if ($user) {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {
                $remember = RememberTokens::findFirst([
                    'usersId = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'profile' => $user->profile->name,
                            'subdomain_id' => $user->subdomain_id,
                            'role' => $user->role,
                            'profilesId' => $user->profilesId,
                            'username' => $user->username,
                            'folder' => $user->subdomain->folder,
                            'not_thumb' => $user->subdomain->not_thumb,
                            'isLoggedIn' => true
                        ]);

                        $this->session->set('subdomain-child', [
                            'subdomain_id' => $user->subdomain_id,
                            'subdomain_name' => $user->subdomain->name,
                            'folder' => $user->subdomain->folder,
                            'not_thumb' => $user->subdomain->not_thumb,
                            'host' => $_SERVER['HTTP_HOST']
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('users');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param \Vokuro\Models\Users $user
     * @throws Exception
     */
    public function checkUserFlags(Users $user)
    {
        if ($user->active != 'Y') {
            // throw new Exception('The user is inactive');
        }

        if ($user->banned != 'Y') {
            // throw new Exception('The user is banned');
        }

        if ($user->suspended != 'Y') {
            // throw new Exception('The user is suspended');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    /*public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }*/


    public function getUsername()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['username'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
        $this->session->remove('subdomain-child');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            // throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);

        $this->session->set('auth-identity', [
            'id' => $user->id,
            'subdomain_id' => $user->subdomain_id,
            'role' => $user->role,
            'profilesId' => $user->profilesId,
            'username' => $user->username,
            'profile' => $user->profile->name,
            'folder' => $user->subdomain->folder,
            'isLoggedIn' => true
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Vokuro\Models\Users
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');
        if (isset($identity['id'])) {
            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                // throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getSubdomainLog()
    {
        return $this->session->get('subdomain-child');
    }
}
