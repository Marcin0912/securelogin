<?php
// PHP OOP Login/Register System: CSRF Protection (Part 11/23)
require_once 'core/init.php';


if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        // echo 'I have been run';
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username'      => array(
                'required' => true,
                'min'      => 2,
                'max'      => 20,
                'unique'   => 'users'
                ),
            'password'      => array(
                'required' => true,
                'min'      => 6
                ),
            'password_again'      => array(
                'required' => true,
                'matches'  => 'password'
                ),
            'name'          => array(
                'required'  => true,
                'min'       => 2,
                'max'       => 50
                )
        ));

        if($validation->passed()) {
            // register user
            $user = new User();
            // echo 'you are before salt';
            $salt = Hash::salt(32);
            try {
                $user->create(array(
                    'username'  => Input::get('username'),
                    'password'  => Hash::make(Input::get('password'), $salt),
                    'salt'  => $salt,
                    'name'  => Input::get('name'),
                    'joined'  => date('Y-m-d H:i:s'),
                    'group'  => 1,
                    ));
                Session::flash('home', 'You have been registered and can now log in!');
                // Redirect::to(404);
                Redirect::to('index.php');
                // header('Location: index.php');
            } catch(Exception $e) {
                die($e->getMessage());
                // or implement redirection to a specific page
            }
            // Session::flash('Success', 'You registered successfully!');
            // header('Location: index.php');
            // exit;
        } else {
            // output errors
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }

}
?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
    </div>
    <div class="field">
        <label for="password">Coose a password</label>
        <input type="password" name="password" id="password" value="<?php echo escape(Input::get('password'));?>">
    </div>
    <div class="field">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>
    <div class="field">
        <label for="name">Enter your name</label>
        <input type="text" name="name" value="<?php echo escape(Input::get('name'));?>" id="name">
    </div>
        <input type="hidden" name="token" value="<?php echo Token::generate()?>">
        <input type="submit" value="Register">

</form>