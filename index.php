<?php
// PHP OOP Login/Register System: Permissions (Part 22/23)

require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User(); // current
if($user->isLoggedIn()) {

?>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"><?php echo escape($user->data()->username); ?></a> ! </p>
    <ul>
        <li><a href="logout.php">Log Out</a></li>
        <li><a href="update.php">Update details</a></li>
        <li><a href="changepassword.php">Change password</a></li>

    </ul>
<?php
    if($user->hasPermission('moderator')) {
        echo '<p> You are an moderator! </p>';
    }

} else {
    echo '<p>You need to <a href="login.php">log in</a> or <a href="register.php">register</a> </p>';
}
// $anotheruser = new User(6); // another user


// echo Session::get(Config::get('session/session_name'));


// if(Session::exists('Success')) {
//     //Add additional markup if you want
//     echo Session::flash('Success');
// }
// updating tables in database
// $userUpdate = DB::getInstance()->update('users', 2,  array(
//         'password'      => 'newpassword',
//         'name'          => 'karolinka'
//     ));
// testing creating tables in DB.php
// $userInsert = DB::getInstance()->insert('users', array(
//         'username'  => 'Julianek',
//         'name'      => 'Jul Syd',
//         'password'  => 'password',
//         'salt'      => 'salt'
//     ));
// testing querying DB.php
// $user = DB::getInstance()->get('users', array('username', '=', 'karolina'));

// if(!$user->count()) {
//     echo 'No user';
// } else {
//     echo 'Ok!', '<br>';
//     echo  ' ' . $user->first()->username;
// }


