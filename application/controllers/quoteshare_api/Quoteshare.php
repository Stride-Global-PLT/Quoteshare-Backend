<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "/libraries/REST_Controller.php";
class Quoteshare extends REST_Controller
{

    private $param = array();
    private $loggedout_arr = array('sign_up', 'social_login', 'forgot_password', 'checkAvailability', 'login', 'categories', 'checkusername', 'verifyEmail', 'MatchEmailCode');
    private $data = array();
    public $userid;
    public $sessionid;
    public $method;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('quoteshare_model');
        $this->load->helper('string');
        $this->method = $this->uri->segment(1);
        if (in_array($this->uri->segment(1), $this->loggedout_arr) == false) {
            $data = $this->quoteshare_model->checkAuth();
            if ($data['status'] != 200) {
                $this->response($data, $data['status']);
            } else {
                $this->user_id = $data['data']['userid'];
                $this->session_id = $data['data']['sessionid'];
                $this->user_type = $data['data']['usertype'];
            }
        }
        $this->user__Id = intval(@$this->input->get_request_header('userid', true));
    }
// It is sign_up API the user hit the url for accessing it
    //  http://localhost/quoteshare/sign_up
    public function signup_post()
    {
        $this->form_validation->set_rules('full_name', 'Full Name', 'required');
        $this->form_validation->set_rules('user_name', 'User Name', 'required|is_unique[users.user_name]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('login_type', 'login type', 'required');
        $this->form_validation->set_rules('device_type', 'Device type', 'required');
        $this->form_validation->set_rules('device_token', 'Device Token', 'required');
        if ($this->form_validation->run() == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'data' => "",
                'message' => $errors,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            if ($this->form_validation->run()) {
                $verify_code = mt_rand(100000, 999999);
                $session_id = random_string('sha1', 30);
                $signup_data = array(
                    'full_name' => $this->post('full_name'),
                    'user_name' => $this->post('user_name'),
                    'email' => $this->post('email'),
                    'password' => md5($this->post('password')),
                    'login_type' => $this->post('login_type'),
                    'device_type' => $this->post('device_type'),
                    'device_token' => $this->post('device_token'),
                    'session_key' => $session_id,
                    'verification_code' => $verify_code,
                );
                $is_existUser = $this->quoteshare_model->exist_email('users', $signup_data['email']);
                if ($is_existUser) {
                    $this->response([
                        'status' => true,
                        'message' => "Email is already exist.",
                    ], REST_Controller::HTTP_CONFLICT);
                } else {
                    $user_data = $this->quoteshare_model->sign_up('users', $signup_data);
                    $getUserData = $this->quoteshare_model->getuserdata($user_data);
                    unset($getUserData->password);
                    if ($getUserData) {
                        $this->response([
                            'status' => true,
                            'message' => 'User registered successfully.',
                            'data' => $getUserData,
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'messsage' => "User not register successfully, please try again.",
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                }
            }
        }
    }
// this function is used for login. This is the api url given below
    //  http://localhost/quoteshare/login
    public function login_post()
    {
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('device_token', 'Token', 'required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'required');
        if ($this->form_validation->run() == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'message' => $errors,
                'data' => "",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $session_id = random_string('sha1', 30);
            $email = $this->input->post('email');
            $password = md5($this->input->post('password'));
            $token = $this->input->post('device_token');
            $device_type = $this->input->post('device_type');
            $dd = array(
                'session_key' => $session_id,
                'device_token' => $token,
                'device_type' => $device_type,
            );
            $login_data = $this->quoteshare_model->user_login($dd, $email, $password);
            if ($login_data['status'] == 200) {
                $this->response([
                    'status' => true,
                    'message' => 'Login successfully.',
                    'data' => $login_data['data'],
                ], REST_Controller::HTTP_OK);
            } elseif ($login_data['status'] == 401) {
                $this->response([
                    'status' => false,
                    'message' => 'Incorrect password.',
                    'data' => "",
                ], REST_Controller::HTTP_UNAUTHORIZED);
            } elseif ($login_data['status'] == 403) {
                $this->response([
                    'status' => false,
                    'message' => 'Your account is not active.',
                ], REST_Controller::HTTP_FORBIDDEN);
            } elseif ($login_data['status'] == 404) {
                $this->response([
                    'status' => false,
                    'message' => 'We could not find an account with that email address. Please check again or sign up using this email.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

//Forgot password with php mailer
    public function forgot_pass_post()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'message' => $errors,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $exist_email = $this->quoteshare_model->exist_email('users', $email);
                if ($exist_email) {
                    $password = random_string('alnum', 8);
                    $to = $exist_email->email;
                    $subject = 'Your new password';
                    $message = 'Your new password is:-' . $password;
                    ##################################
                    // Instantiation and passing `true` enables exceptions
                    //$mailer=$this->quoteshare_model->mailer($to, $subject, $message);
                    //$mailer=$this->quoteshare_model->Emailsend($to, $subject, $message);
                    $mailer = $this->quoteshare_model->htmlmailer($to, $subject, $message);
                    ##########################
                    $newpassword = array(
                        'password' => md5($password),
                    );
                    if ($mailer['status'] == 200) {
                        $passset = $this->quoteshare_model->newpassword($exist_email->email, $newpassword);
                        $this->response([
                            'status' => true,
                            "message" => "Your new password has been sent to your email. Please check your inbox.",
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            "message" => "Failed to send the password on email,Please try again.",
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        "message" => "We could not find an account with that email address. Please check again or sign up using this email.",
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }

        }
    }

// This function fetch the new password from user with matching email
    // http://localhost/quoteshare/forgot_password
    public function reset_password_post()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]|min_length[8]');
        if ($this->form_validation->run() == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'message' => $errors,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            if ($this->form_validation->run()) {
                $email = $this->input->post('email');
                $reset_password = array(
                    'password' => md5($this->input->post('password')));
                $reset_response = $this->quoteshare_model->rest_password($email, 'users', $reset_password);
                if ($reset_response) {
                    $this->response([
                        'status' => true,
                        "message" => "Your new password successfully reset, Please login again.",
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        "message" => "Your password not reset successfully, Please try it again.",
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
    }
//This function is used to move the image in the upload folder and return the image name
    public function image_upload_post()
    {
        if (empty($_FILES['picture']['name'])) {
            $this->response([
                'status' => false,
                "message" => "Please select the image.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["picture"]['name'];
            $config['file_name'] = $picture;
            $config['upload_path'] = './uploads/quotes';
            //$config['allowed_types']= 'gif|jpeg|png|jpg';
            $config['allowed_types'] = '*';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('picture')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $imagef = $this->upload->data();
                //$mainimage = strtotime(date('Y-m-d H:i:s')).$image;
                $file_array = $this->upload->data('full_path');
                $quote_picture = $imagef['file_name'];
            }
            $this->response([
                'status' => true,
                "message" => "Image successfully Uploaded.",
                'data' => $quote_picture,
            ], REST_Controller::HTTP_OK);
        }
    }
    //this function used to get the all category of the quoteshare
    public function categories_post()
    {
        $category = $this->quoteshare_model->categories();
        if ($category) {
            $this->response([
                'status' => true,
                "message" => "Quoteshare categories.",
                'data' => $category,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                "message" => "No category is found.",
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //This function is used to post the quotes
    public function create_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user) {
            // $this->form_validation->set_rules('category_id','Category Id','required');
            $this->form_validation->set_rules('image', 'image', 'required');
            $this->form_validation->set_rules('quote', 'quote', 'required');
            $this->form_validation->set_rules('caption', 'Caption', 'required');
            $this->form_validation->set_rules('author_id', 'Author Id', 'required');
            $this->form_validation->set_rules('booker_id', 'Book Id', 'required');
            //$this->form_validation->set_rules('tagger_id','Tags Id','required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                $this->response([
                    'status' => false,
                    'data' => $errors,
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                if ($this->form_validation->run()) {
                    $save_quotes = array(
                        'category_id' => $this->input->post('category_id'),
                        'user_id' => $user_id,
                        'image' => $this->input->post('image'),
                        'quote' => $this->input->post('quote'),
                        'caption' => $this->input->post('caption'),
                        'author_id' => $this->input->post('author_id'),
                        'booker_id' => $this->input->post('booker_id'),
                        'tagger_id' => $this->input->post('tagger_id'));
                    $user_data = $this->quoteshare_model->sign_up('feeds', $save_quotes);
                    $feed_id = array(
                        'feed_id' => $user_data,
                    );
                    if ($feed_id) {
                        $this->response([
                            'status' => true,
                            'message' => 'Post successfully.',
                            'data' => $feed_id,
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'messsage' => "Internal error, Please try again.",
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                }
            }
        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
//This function will delete the quotes from feed table
    public function delete_post()
    {
        $feed_id = $this->input->post('feed_id');
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user) {
            $is_AlreadyExist = $this->quoteshare_model->isfeedthere($feed_id, $user_id);
            if ($is_AlreadyExist == 1) {
                $deleteQuote = $this->quoteshare_model->delete_quote('feeds', $feed_id, $user_id);
                //check repost feed
                $repost = $this->quoteshare_model->checkRepost($feed_id);
                if ($repost) {
                    $deleteRepost = $this->quoteshare_model->delete_repost($feed_id);
                }
                //print_r($deleteRepost);
                if (!empty($deleteQuote || $deleteRepost)) {
                    $this->response([
                        'status' => true,
                        "message" => "Delete successfully.",
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => "No post found.",
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //this function used to add the new category
    public function UserProfilePictureUpload_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if (!$is_validate_user) {
            //return null;
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $this->form_validation->set_rules('user_profile', 'profile picture', 'required');
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->error_array();
                $this->response([
                    'status' => false,
                    'data' => $errors,
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $profile_picture = array(
                    'picture' => $this->input->post('user_profile'));
                $ProfileUpdate = $this->quoteshare_model->UserProfilePictureUpload($user_id, $profile_picture);
                if ($ProfileUpdate) {
                    $this->response([
                        'status' => true,
                        "message" => "Profile picture Upload successfully.",
                        'data' => $ProfileUpdate,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => true,
                        "message" => "Profile picture not Upload successfully,Please try again.",
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
    }

    //this function is used to login with social sites
    public function social_login_post()
    {
        $social_id = $this->input->post('social_id');
        $device_token = $this->input->post('device_token');
        $session_id = random_string('sha1', 30);
        $update_Session = array(
            'session_key' => $session_id,
            'device_token' => $device_token,
        );

        $social_login = $this->quoteshare_model->exist_socialuser($social_id);

        if ($social_login) {
            if ($social_login->is_active == 1) {
                $updated = $this->quoteshare_model->update_SessionTokenType($update_Session, $social_login->user_id, 'users');
                $user_data = $this->quoteshare_model->exist_socialuser($social_id);
                $this->response([
                    'status' => true,
                    'message' => "Successfully login.",
                    'data' => $user_data,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => "Your account is not active.",
                    'data' => 2,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            if (!empty($this->input->post('email'))) {
                $res = $this->quoteshare_model->exist_email('users', $this->input->post('email'));
                if ($res) {
                    $this->response([
                        'status' => true,
                        'message' => "Email is already exist.",
                    ], REST_Controller::HTTP_OK);
                }
            }
            $user_name = $this->input->post('username');
            $social_email = $this->input->post('email');
            $device_type = $this->input->post('device_type');
            $login_type = $this->input->post('login_type');
            $random_num = substr($social_id, 5, 5);
            $s = ucfirst($user_name . $random_num);
            $bar = strtolower($s);
            $data = preg_replace('/\s+/', '', $bar);

            $social_login_data = array(
                'social_id' => $social_id,
                'full_name' => $user_name,
                'user_name' => $data,
                'email' => $social_email,
                'device_type' => $device_type,
                'login_type' => $login_type,
                'session_key' => $session_id,
                'device_token' => $device_token,
            );
            $login_data = $this->quoteshare_model->sign_up('users', $social_login_data);
            $getUserData = $this->quoteshare_model->getuserdata($login_data);
            if ($getUserData) {
                $this->response([
                    'status' => true,
                    'message' => 'User successfully registered.',
                    'data' => $getUserData,
                ], REST_Controller::HTTP_OK);
            }
        }
    }
//this function will check the user validation
    public function checkUserNameValidation_post()
    {
        @$user_name = $this->input->post('user_name');
        @$user_id = $this->input->post('user_id');
        $is_existUsername = $this->quoteshare_model->checkUserNameValidation('users', $user_name, $user_id);
        if ($is_existUsername) {
            $this->response([
                'status' => true,
                'message' => "This user name is already exist, Please use unique user name.",
                'data' => 1,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => true,
                'message' => "Valid user name.",
                'data' => 0,
            ], REST_Controller::HTTP_OK);
        }
    }
    //this function used for logout
    public function logout_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = array(
            'session_key' => "",
            'device_token' => "",
        );
        $logoutdata = $this->quoteshare_model->logout($user_id, $session_key);
        if ($logoutdata) {
            $this->response([
                'status' => true,
                'message' => "Logout successfully.",
                'data' => $logoutdata,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => true,
                'message' => "Already logout.",
            ], REST_Controller::HTTP_OK);
        }
    }
    //logout function

    //This function is used to edit the user profile
    public function editprofile_post()
    {
        if (empty($this->post('user_id') && $this->post('session_key'))) {
            return false;
        }
        $user_id = $this->post('user_id');
        $session_key = $this->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user) {
            $update = array(
                'full_name' => $this->input->post('full_name'),
                'user_name' => $this->input->post('user_name'),
                'bio' => $this->input->post('bio'),
                'email' => $this->input->post('email'),
            );
            $profile = array_filter($update);
            $updatedprofile = $this->quoteshare_model->update('users', $user_id, $profile);
            $user = $this->quoteshare_model->getuserdata($user_id);
            unset($user->password);
            $this->response([
                'status' => true,
                "message" => "Update profile successfully.",
                "data" => $user,
            ], REST_Controller::HTTP_OK);

        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                "data" => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //This function is used to change the password
    public function passwordChange_post()
    {
        $this->form_validation->set_rules('oldpassword', 'Old Password', 'required');
        $this->form_validation->set_rules('newpassword', 'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('retypenewpassword', 'Confirm Password', 'required|matches[newpassword]');
        if ($this->form_validation->run() == false) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'status' => false,
                'data' => $errors,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {

            $user_id = $this->input->post('user_id');
            $session_key = $this->input->post('session_key');
            $oldPass = md5($this->input->post('oldpassword'));
            $newPass = md5($this->input->post('newpassword'));
            $retypenewPass = md5($this->input->post('retypenewpassword'));
            $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
            if ($is_validate_user) {
                $matching = $this->quoteshare_model->oldpasswordcheck($user_id, $oldPass);
                if ($matching) {
                    $updatepass = array(
                        'password' => $newPass);
                    $updated = $this->quoteshare_model->update('users', $user_id, $updatepass);
                    $this->response([
                        'status' => true,
                        "message" => "Password change successfully.",
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        "message" => " your old password is wrong, Please enter the correct password.",
                        "data" => $matching,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status' => false,
                    "message" => "Unauthorized user.",
                    "data" => $is_validate_user,
                ], REST_Controller::HTTP_NOT_FOUND);
            }

        }
    }
    // End Query

    // This function is used to verify the email
    public function verifyEmail_post()
    {
        $param = $_POST;
        if (@$param['email'] && @$param['code'] == 0) {
            $this->response([
                'status' => false,
                'message' => 'Please enter email or code.',
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {

            $to = $param['email'];
            $subject = 'Quoteshare email verification code.';
            $message = 'Your quoteshare email verify code is' . " " . $param['code'] . ".";
            ##################################
            $mailer = $this->quoteshare_model->htmlmailer($to, $subject, $message);
            if ($mailer['status'] == 200) {
                $this->response([
                    'status' => true,
                    'message' => 'Verfication code sent to your email successfully, Please check your email.',
                ], REST_Controller::HTTP_OK);

            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Verfication code sent to your email is failed, Please try it again.',
                ], REST_Controller::HTTP_CONFLICT);
            }
        }
    }
    //End verify email query

//This function is used to check the verify code
    public function MatchEmailCode_post()
    {
        $param = $_POST;
        if (empty($param['email'])) {
            $this->response([
                'status' => false,
                'message' => 'Please enter email.',
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            //$response=$this->quoteshare_model->CodeMatch($param);
            $verified_status = array('verification_code' => " ",
                'is_verified' => 1);
            $response = $this->quoteshare_model->newpassword($param['email'], $verified_status);
            if ($response) {
                $this->response([
                    'status' => true,
                    'message' => 'Email verified.',
                ], REST_Controller::HTTP_OK);
            }
        }
    }
// Email match code end
}
