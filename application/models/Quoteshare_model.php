<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

require APPPATH. '/third_party/vendor/phpmailer/phpmailer/src/Exception.php';
require APPPATH.'/third_party/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require APPPATH.'/third_party/vendor/phpmailer/phpmailer/src/SMTP.php';

use phpmailer\phpmailer\PHPMailer;
use phpmailer\phpmailer\SMTP;
use phpmailer\phpmailer\Exception;

// Load Composer's autoloader
require APPPATH.'/third_party/vendor/autoload.php';

Class Quoteshare_model extends CI_Model {

    // Insert into $table(fields)values(fields)
    //This function used for sign_up new user
    public function sign_up($table,$fields){
        $this->db->insert($table,$fields);
        return $this->db->insert_id();
    }
    //This function is used for login user can login with email or user_name
    public function login($email,$password){
         $this->db->select('*')
        ->from('users')
        ->where("(email = '$email' OR user_name = '$email')")
        ->where('password', $password);
         $q=$this->db->get();
        if($q->num_rows()){
            return $q->row();
         }
        else{
             return false;
         }
     }

#_____ Check Auth

   public function checkAuth(){
      $check    = array('userid'=>$this->input->get_request_header('user_id', TRUE),'sessionid'=>$this->input->get_request_header('session_key', TRUE));
      if(checkRequired($check)){
         return array('status' => 400,'message'=>"Header Request : ".checkRequired($check),'method'=>$this->method);
      }else{
         $userid        =   $this->input->get_request_header('user_id', TRUE);
         $sessionid     =   $this->input->get_request_header('session_key', TRUE);
         //$res=$this->checkSession(array('userid'=>$userid,'sessionid'=>$sessionid));
         if($this->checkSession(array('userid'=>$userid,'sessionid'=>$sessionid)) != 200){
            return array('status' => 401,'message'=>http_code_message(401));
         }else{
            $usertype   =   getAnything('users',array('user_id'=>$userid),'user_type')[0]['user_type'];
            return array('status'=>200,'data'=>array('userid'=>$userid,'sessionid'=>$sessionid,'usertype'=>$usertype));
         }
      }
   }

#_____
   #_____ Check Session
      
      public function checkSession($data) {
         $check =   getAnything('users',array('user_id'=>$data['userid'],'session_key'=>$data['sessionid'],'is_active'=>1),'user_id');
         if(count($check)){
            return 200;
         }else{
            return 401;
         }
      }
      
#_____

      //This function is used to user login 

    public function user_login($dd="",$email="",$password=""){
        $checkemail=$this->db->query("select * from users where email='".$email."' OR user_name='".$email."'")->row();
        if($checkemail){
            if($checkemail->is_active==1){
                $check=$this->db->query("select * from users where(email='".$email."' OR user_name='".$email."') and password='".$password."'")->row();
                if($check){
                    $this->update_SessionTokenType($dd,$check->user_id,'users');
                     $user_data     =   $this->db->query("select user_id,full_name,full_name,bio,user_name,email,login_type,device_type,device_token,session_key,verification_code,is_verified,is_active,picture,created_at,updated_at from users where user_id = '".$check->user_id."'")->row();
                    return array('status' => 200,'message' =>'Login successfully.','data'=>$user_data);
                }else{
                    return array('status' => 401,'message'=>'Incorrect password.');
                }
            }
            else{
               return array('status' => 403,'message'=>'Your account is not active.');
            }
        }else{
            return array('status' => 404,'message'=>'We could not find an account with that email address. Please check again or sign up using this email.');
        }
    }
    // User LOGIN FUNCTION END#########

    //this function update the session key when  user login again
    public function update_SessionTokenType($data,$user_id='',$table){
            $this->db->set($data);
            $this->db->where('user_id', $user_id);
            $this->db->update($table); 
    }
    public function exist_email($table,$email){
        $query = $this->db->get_where($table, array('email' => $email));
        return $query->row();
    }
    public function rest_password($email,$table,$data){
        $this->db->where('email',$email);
        return  $this->db->update($table, $data);
    }
    //this function fetch all the records whose status=1
    public function categories(){
        $category=$this->db->get_where('quotes_category',array('status'=>1));
        return $category->result();
    }

    //This function will delete the single record whose feed_id=$feed_it and user_id=$user_id.
    public function delete_quote($table,$feed_id,$user_id){
        $this->db->delete($table, array('feed_id' => $feed_id, 'user_id'=>$user_id));
        return $this->db->affected_rows();
    }

     //This function will delete the single record whose parent_id=$feed_it
     public function delete_repost($feed_id=""){
        // $this->db->from("feeds");
        // $this->db->join('resharingcount', 'feeds.parent_id = resharingcount.feed_id');
        // $this->db->where('feeds.parent_id=resharingcount.feed_id');
        // $this->db->where('feeds.parent_id', $feed_id);
        // $this->db->delete(array('feeds','resharingcount'));
        $this->db->delete('feeds', array('parent_id' => $feed_id)); 
        $this->db->delete('resharingcount', array('feed_id' => $feed_id));
        $this->db->delete('feed_likes', array('feed_id' => $feed_id));
        $this->db->delete('feed_comments', array('feed_id' => $feed_id));
        return $this->db->affected_rows();
    }

    //this will check the validation of the user
    public function check_user_validation($user_id="",$session_key="") {
        $query=$this->db->get_where('users',array('user_id'=>$user_id,'session_key'=> $session_key,'is_active'=>1));
        return $query->num_rows();
    }

    //this function is used to upload the user profile picture
    public function UserProfilePictureUpload($user_id,$data){
        $this->db->where('user_id', $user_id);
        return $this->db->update('users',$data);
    }
    //this function is used to update the new password
    public function newpassword($email="",$data=""){
        $this->db->where('email',$email);
        $this->db->update('users',$data);
        return $this->db->affected_rows();
    }

    //This function is used to get the userdetail 
    public function getuserdata($user_id=""){
        return $this->db->get_where('users',array('user_id'=> $user_id,'is_active'=>1))->row();
        // return $this->db->row();
    }
    //This function is used to login with social sites
    public function social_login(){

    }

    //this email check the social key is already there or not
        public function exist_socialuser($social_id){
        return $this->db->get_where('users',array('social_id'=> $social_id, 'login_type'=> 2))->row(); 
        }

        //this function check the validation of the username if it exist it will give the 1 otherwise 0
        public function checkUserNameValidation($table="",$username="",$user_id=""){
            if(!empty($user_id)){
                 $query=$this->db->get_where($table, array('user_name'=>$username,'user_id !='=>$user_id));
                //return $query->row();
                return $query->num_rows(); 
            }else{
                 $query=$this->db->get_where($table, array('user_name'=>$username));
                 //return $query->row();
                return $query->num_rows(); 
            }

          
        }
        //this function is used to logout
        public function logout($user_id,$data){
            $this->db->where('user_id', $user_id);
            $this->db->update('users',$data);
            return $this->db->affected_rows();
         }

            public function editprofile($fields,$table){
                $login_data=$this->db->where($fields)->get($table);
                if($login_data->num_rows()){
                    return $login_data->row();
                }
                else{
                    return null;
                }
            }

            //This function is used to check the feed is already there or not
        public function isfeedthere($feed_id,$user_id){
            $query=$this->db->get_where('feeds',array('feed_id'=>$feed_id, 'user_id'=> $user_id));
            return $query->num_rows();
        }
     //This is the commmon function used to update the data
        public function update($table,$user_id,$data){
            $this->db->where('user_id', $user_id);
            $this->db->update($table,$data);
            return $this->db->affected_rows();

        }
        //this function is used to check the exist password
        public function oldpasswordcheck($user_id="",$password=""){
              $query=$this->db->get_where('users',array('user_id'=>$user_id, 'password'=> $password));
            return $query->num_rows();
        }

        #Check repost feed 
        public function checkRepost($feed_id=""){
            $query=$this->db->get_where('feeds',array('parent_id'=>$feed_id,'status'=>1));
            return $query->num_rows();
        }

        ##############
 #send function
        function Emailsend($to="",$subject="",$message=""){
        $this->load->config('email');
        $this->load->library('email');
        $from = $this->config->item('smtp_user');
        $this->email->set_newline("\r\n");
        $this->email->from('quoteshare@info.in');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send()) {
            return $data    =   array('status' => 200,'message'=>"Mail send successfully");
            //echo 'Your Email has successfully been sent.';
        } else {
            return $data    =   array('status' => 501,'message'=>"Unsucessfull");
            
            //show_error($this->email->print_debugger());
        }
    }
        #______

        #______

    Public function mailer($to="",$subject="",$message=""){
        // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;

            $mail->isSMTP();
            $mail->Host = "mail.techwinlabs.in";
            $mail->SMTPAuth = false;
            $mail->Username = "quoteshareinfo2021@techwinlabs.in";
            $mail->Password = "Admin@<>";
            $mail->SMTPSecure = "None";
            $mail->Port = 25;

            $mail->setFrom("quoteshare@info.in", "Quoteshare");
            $mail->addAddress($to, "Name");

            $mail->isHTML = true;

            $mail->Subject = $subject;
            $mail->Body = $message;
            // $mail->AltBody = $alt_msg;


            // $mail->From = 'neharani.techwinlabs@gmail.com';
            // $mail->FromName = 'Quoteshare';
            // $mail->addAddress($to, 'User');     // Add a recipient
            // $mail->isHTML(true);                                  // Set email format to HTML
            // $mail->Subject = $subject;
            // $mail->Body    = $message;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            
            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;exit;
            } else {
                return $data    =   array('status' => 200,'message'=>"Mail send successfully.");
            }
        } catch (Exception $e) {
            //echo "not send";
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    ##########

    //This is the html mail function
    public function htmlmailer($to="",$subject="",$message=""){
    $from='quoteshareinfo2021@techwinlabs.in';
    $to      = $to;
    $subject = $subject;
    $message = $message;
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    // Create email headers
    $headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();

    if(mail($to, $subject, $message, $headers)){
      return $data    =   array('status' => 200,'message'=>"Mail send successfully");
    } else{
        echo 'Unable to send email. Please try again.';
    }
    }
// end mail function

    // This function is used to inser the code in verifiy code table
    public function verifyCode($table="",$data=""){
    $check_em   =   getAnything('verfication_code',array('email'=>$data['email']),'verification_id');
    if(count($check_em)==0){
          //insert the data
             $this->db->insert($table,$data);
              $insert_id = $this->db->insert_id();
              if(!empty($insert_id)){
                return array('status' => 200,'message' =>'code insert.');
              }
             //return $this->db->insert_id();
        }else{
            //update the data
            $this->db->where(array('email'=>$data['email']))->update($table,$data);
            $this->db->affected_rows();
            return array('status' => 200,'message' =>'code insert.');
        }
    }
    #_END query

    //This function is used to check the verification code
    public function CodeMatch($data){
        $check_em   =   getAnything('users',array('email'=>$data['email'],'code'=>$data['code']),'user_id');
        if(count($check_em)==0){
            return 0;
        }else{
           return $check_em;
        }
    }
    // End CodeMatch

    //This function is used to delete the verification code
    public function DeleteCode($email=""){
         $this->db->delete('verfication_code', array('email' => $email));
        return $this->db->affected_rows();
    }

}

?>