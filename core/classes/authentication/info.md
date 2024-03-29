# Authentication System

read/ref: https://dev.to/fadymr/php-create-your-own-php-authentication-4e20

also for session: https://dev.to/fadymr/php-create-a-simple-session-wrapper-class-dpk



## How to use ?


### Registration


    // use DevCoder\Authentication\Core\UserManager;
    // use DevCoder\Authentication\User;

    // register
    $userManager = new UserManager();
    $req = Registry::get('REQUEST);

    $password = $userManager->cryptPassword($req->POST['password']);
    
    $user = (new User())
        ->setUserName($req->POST['username'])
        ->setPassword($password)
        ->setRoles(['ROLE_USER']);

    $userManager->createUserToken($user);

    // check Token in Session
    var_dump($userManager->getUserToken());
    // object(DevCoder\Authentication\Token\UserToken)[4]
    //  private 'user' => 
    //    object(DevCoder\Authentication\User)[5]
    //      private 'userName' => string 'username' (length=8)
    //      private 'password' => string '$2y$10$iWdcmebmikUFlgKMqW7/rOmUp1DjFAuWKqdUHBhL08FZ7LL6bwRey' (length=60)
    //      private 'roles' => 
    //        array (size=1)
    //          0 => string 'ROLE_USER' (length=9)
    //      private 'enabled' => boolean true


## Connected or not

    <?php

    // use DevCoder\Authentication\Core\UserManager;
    // use DevCoder\Authentication\User;

    $userManager = new UserManager();
    if ($userManager->hasUserToken()) {
        // connected

        $token = $userManager->getUserToken();
        $user = $token->getUser();
        var_dump($user);
    // object(DevCoder\Authentication\User)[5]
    //  private 'userName' => string 'username' (length=8)
    //  private 'password' => string '$2y$10$OBobeLhdvdiftuedlv1a6e4.qF6sCG/usq5WEV4E3uB.UiS1egv/m' (length=60)
    //  private 'roles' => 
    //    array (size=1)
    //      0 => string 'ROLE_USER' (length=9)
    //  private 'enabled' => boolean true

    } else {
        // not connected
    }


## Access management

    $userManager = new UserManager();
    if ($userManager->isGranted(['ROLE_ADMIN'])) {
        //is admin
        // return Response 200
    }else {
        //is not admin
        // return Response 403
    }

    Logout

    $userManager = new UserManager();
    $userManager->logout();


## Login

    <?php

    use DevCoder\Authentication\Core\UserManager;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $userFromDataBase = $stmt->fetch();
    /**
     * Hydration
     */
    $user = (new \Test\DevCoder\Authentication\User())
        ->setUserName($userFromDataBase['username'])
        ->setPassword($userFromDataBase['password'])
        ->setRoles(json_decode($userFromDataBase['roles']))
        ->setEnabled($userFromDataBase['active']);

    $userManager = new UserManager();
    if ($userManager->isPasswordValid($user, $_POST['password'])) {

        // login OK, set Token in session
        $userManager->createUserToken($user);

    } else {
     // login failed , return error
    }
