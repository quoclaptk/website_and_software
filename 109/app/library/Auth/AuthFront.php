<?php namespace Modules\Auth;

use Phalcon\Mvc\User\Component;
use Modules\Models\Member;
use Modules\Models\RememberTokens;
use Modules\Models\SuccessLogins;
use Modules\Models\FailedLogins;

/**
 * Modules\Auth
 *
 * Manages Authentication/Identity Management in Modules
 */
class AuthFront extends Component
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
        $member = Member::findFirst([
            'conditions' => 'subdomain_id = '. $credentials['subdomain_id'].' AND (username = "'. $credentials['username'] .'" OR email = "'. $credentials['username'] .'") AND active = "Y" AND deleted = "N"'
        ]);

        if ($member == false) {
            return false;
            // $this->registerUserThrottling(0);
            // throw new Exception('Wrong email/password combination');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $member->password)) {
            return false;
            // $this->registerUserThrottling($member->id);
            // throw new Exception('Wrong email/password combination');
        }

        // Check if the user was flagged
        if (!$this->checkUserFlags($member)) {
            return false;
        }

        // Register the successful login
        // $this->saveSuccessLogin($member);

        // Check if the remember me was selected
        if (isset($credentials['remember']) && $credentials['remember'] == 'yes') {
            $this->createRememberEnvironment($member);
        }
       
        $guesInfo = $member->toArray();
        $guesInfo['isLogin'] = true;
        unset($guesInfo['password']);
        
        $this->session->set('auth-guest', $guesInfo);

        return true;
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Modules\Models\Member $member
     * @throws Exception
     */
    public function saveSuccessLogin($member)
    {
        $successLogin = new SuccessLogins();
        $successLogin->usersId = $member->id;
        $successLogin->ipAddress = $this->request->getClientAddress();
        $successLogin->userAgent = $this->request->getUserAgent();
        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();
            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the effectiveness of brute force attacks
     *
     * @param int $memberId
     */
    public function registerUserThrottling($memberId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->usersId = $memberId;
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
     * @param \Modules\Models\Member $member
     */
    public function createRememberEnvironment(Member $member)
    {
        $memberAgent = $this->request->getUserAgent();
        $token = md5($member->email . $member->password . $memberAgent);

        $expire = time() + 86400 * 8;
        $this->cookies->set('RMU', $member->id, $expire);
        $this->cookies->set('RMT', $token, $expire);
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
        $memberId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $member = Member::findFirstById($memberId);
        if ($member) {
            $memberAgent = $this->request->getUserAgent();
            $token = md5($member->email . $member->password . $memberAgent);

            if ($cookieToken == $token) {
                $remember = RememberTokens::findFirst([
                    'usersId = ?0 AND token = ?1',
                    'bind' => [
                        $member->id,
                        $token
                    ]
                ]);
                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($member);

                        // Register identity
                        $this->session->set('auth-guest', [
                            'id' => $member->id,
                            'name' => $member->name,
                            'profile' => $member->profile->name
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($member);

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
     * @param \Modules\Models\Member $member
     * @throws Exception
     */
    public function checkUserFlags(Member $member)
    {
        if ($member->active != 'Y') {
            return false;
            // throw new Exception('The user is inactive');
        }

        if ($member->banned != 'N') {
            return false;
            // throw new Exception('The user is banned');
        }

        if ($member->suspended != 'N') {
            return false;
            // throw new Exception('The user is suspended');
        }

        return true;
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-guest');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-guest');
        return $identity['name'];
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
            $token = $this->cookies->get('RMT')->getValue();
            UserRememberTokens::findFirstByToken($token)->delete();
            
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-guest');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $member = Member::findFirstById($id);
        if ($member == false) {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($member);

        $this->session->set('auth-guest', [
            'id' => $member->id,
            'name' => $member->name,
            'profile' => $member->profile->name
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Modules\Models\Member
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-guest');
        if (isset($identity['id'])) {
            $member = Member::findFirstById($identity['id']);
            if ($member == false) {
                throw new Exception('The user does not exist');
            }

            return $member;
        }

        return false;
    }

    public function getMemberById($id)
    {
        $member = Member::findFirstById($id);
        if ($member == false) {
            // throw new Exception('The user does not exist');
        }

        return $member;
    }
}
