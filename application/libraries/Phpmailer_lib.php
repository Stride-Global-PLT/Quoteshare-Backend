<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PHPMailer Class
 *
 * This class enables SMTP email with PHPMailer
 *
 * @category    Libraries
 * @author      CodexWorld
 * @link        https://www.codexworld.com
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class PHPMailer_Lib
{
    public function __construct(){
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    public function load(){
        // Include PHPMailer library files
        // require_once APPPATH.'third_party/phpmailer/Exception.php';
        // require_once APPPATH.'third_party/phpmailer/PHPMailer.php';
        // require_once APPPATH.'third_party/phpmailer/SMTP.php';
        
        
        require_once APPPATH.'third_party/PHPMailer/Exception.php';
        require_once APPPATH.'third_party/PHPMailer/PHPMailer.php';
        require_once APPPATH.'third_party/PHPMailer/SMTP.php';
        $mail = new PHPMailer;
        return $mail;
    }
    public function test(){
        echo "hi this is the test function for checking";
    }
}
?>