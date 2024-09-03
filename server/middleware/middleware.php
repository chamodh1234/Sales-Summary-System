<?php
// Check for user activity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 24 * 60 * 60) {
    session_unset();
    session_destroy();
} else {
    // User is active, update last activity timestamp
    $_SESSION['last_activity'] = time();
}


class Middleware
{
    private static $log = false;
    private static $user;

    private static $loged;

    public static function randString($n)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public static function run()
    {

        if (!isset($_SESSION['un'])) {
            header("Location: loging.php");
        } else {

            if (Middleware::$log == false) {
                Middleware::$user = db::crud("SELECT user_name,loged,session_id FROM user WHERE user_name='" . $_SESSION['un'] . "' AND session_id='" . $_COOKIE['PHPSESSID'] . "' ");
            }

            if (Middleware::$user->num_rows > 0) {
                Middleware::$log = true;
                $_SESSION['l'] = 1;

                $token = '';
                Middleware::$loged = Middleware::$user->fetch_assoc();
                db::crud("UPDATE  user SET token='" . $token . "' WHERE  user_name='" . $_SESSION['un'] . "'");
                if (Middleware::$loged['loged'] == 1 && Middleware::$loged['session_id'] != $_COOKIE['PHPSESSID']) {
                    //already loged
                    header('Location: ../../client/pages/unauthorizedaccess.php?e=7602');
                }
            } else {
                header('Location: ../../client/pages/unauthorizedaccess.php?aaa');
            }
        }
    }

    public static function checklog()
    {
        if (isset($_SESSION['l'])) {
            header("Location: ../../client/pages/admin.php");
        }
    }

    public static function setToken()
    {

        $simple_string = $_COOKIE['PHPSESSID'] . '-' . self::randString(10);

        $ciphering = "AES-128-CTR";

        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;

        $encryption_iv = '6787658909812435';

        $encryption_key = "seecus";
        $encryption = openssl_encrypt(
            $simple_string,
            $ciphering,
            $encryption_key,
            $options,
            $encryption_iv
        );

        db::crud("UPDATE user SET token='" . $encryption . "' WHERE user_name='" . self::$loged['user_name'] . "'");
    }

    public static function checkTokenValidity()
    {
        if (isset($_SESSION['un'])) {
            $decryption_iv = '6787658909812435';
            $ciphering = "AES-128-CTR";
            $token = db::crud("SELECT token FROM user WHERE user_name='" . $_SESSION['un'] . "'");
            $token_data = $token->fetch_assoc();
            $decryption_key = "seecus";
            $options = 0;

            $decryption = openssl_decrypt(
                $token_data['token'],
                $ciphering,
                $decryption_key,
                $options,
                $decryption_iv
            );

            $sessionid = explode('-', $decryption, 3);

            if ($sessionid[0] == $_COOKIE['PHPSESSID']) {
                return true;
            } else {
                return false;
            }
        }else{
            echo "Not a valid user ->";
        }
    }
 

    public static function validatePostData($data) {
        $errors = [];
      
        foreach ($data as $key => $value) {
          // Basic validation (adjust as needed)
          if (empty($value)) {
            $errors[$key] = "$key is required";
          } else {
            // Perform specific validation based on field type, length, etc.
            // Example:
            if ($key === 'email') {
              if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = "$key is invalid";
              }
            }
            if (strpos($value, "'") !== false || strpos($value, "--") !== false) {
                $errors[$key] = "$key contains invalid characters";
              }

          }
        }
      
        return [];
      }
      
      public static function userBasedAction(){
        if (isset($_GET['m']) && isset($_GET['lr']) && isset($_GET['y']) && isset($_GET['rid'])) {
            if (isset($_SESSION['r'])) {
                if ($_SESSION['r'] == '1') {
                } else {
                    header("Location: admin.php");
                }
            } else {
                header("Location: admin.php");
            }
        }
        
        
      }

}
