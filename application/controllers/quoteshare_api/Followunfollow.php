<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "/libraries/REST_Controller.php";
class Followunfollow extends REST_Controller
{

    private $param = array();
    private $loggedout_arr = array('sign_up', 'social_login', 'forgot_password', 'checkAvailability', 'login', 'getUserProfile');
    private $data = array();
    public $userid;
    public $sessionid;
    public $method;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Followunfollow_model', 'follow');
        $this->load->model('Feed_model');
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
    //This function is used to do the follow/unfollow
    public function FollowUnfollow_post()
    {
        if (empty($this->input->post('user_id') && $this->input->post('follower_id') && $this->input->post('session_key'))) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the field.",
            ], REST_Controller::HTTP_NOT_FOUND);
            //here i will check feed_id with feed table if it is there the further process otherwise "NO POST IS FOUND"
        }
        $user_id = $this->input->post('user_id');
        $follower_id = $this->input->post('follower_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($follower_id, $session_key);
        if ($is_validate_user) {
            $Isfollowinguser = $this->follow->getUserProfile($user_id);

            if ($Isfollowinguser) {
                $Isfollower = $this->follow->is_already_follow($user_id, $follower_id);
                // $Isuser_exist=$this->Feed_model->user_like($user_id,$feed_id);
                //if user already follow it then it will be unfollow the
                if ($Isfollower) {
                    $unfollow = $this->follow->unfollow($user_id, $follower_id);
                    $this->response([
                        'status' => false,
                        "message" => "Unfollow.",
                        "data" => $unfollow,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $follow = array(
                        'user_id' => $user_id,
                        'follower_id' => $follower_id,
                    );

                    //Following user data
                    $followingUserData = $this->quoteshare_model->getuserdata($user_id);
                    //follower user data
                    $followUserData = $this->quoteshare_model->getuserdata($follower_id);
                    //like user data
                    $message = $followUserData->user_name . " " . "started following you.";
                    //notification function
                    $section = 1;
                    //Notification saved in notification table
                    $msg = array(
                        'receiver' => $user_id,
                        'sender' => $follower_id,
                        'message' => $message,
                    );
                    $isfollow = $this->follow->follow($follow);
                    //Android push notificaion
                    if ($followingUserData->device_type == 2) {
                        @$notification = andiPush($followingUserData->device_token, $message, $section);
                        @$result = $this->quoteshare_model->sign_up('notification', $msg);
                    }
                    //IOS push notificaion
                    else {
                        @$applenotification = applePush($followingUserData->device_token, $message, $badge = 0, $section);
                        @$result = $this->quoteshare_model->sign_up('notification', $msg);
                    }
                    //Give message of followed
                    $this->response([
                        'status' => true,
                        "message" => "Followed.",
                        "data" => $isfollow,
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    "message" => "No user found for follower.",
                    "data" => $Isfollowinguser,
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
    //This function count the total numbers of followers that follow you
    public function totalFollowers_post()
    {
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            $this->response([
                'status' => false,
                "message" => "Please fill the all detail.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $total = $this->follow->totalFollowers($user_id);
            $totalfollowers = array(
                'total_followers' => $total);
            $this->response([
                'status' => true,
                "message" => "Total followers.",
                "data" => $totalfollowers,
            ], REST_Controller::HTTP_OK);
        }
    }
    //This function shoe the following user that i follow
    public function totalFollowings_post()
    {
        $user_id = $this->input->post('user_id');
        $total = $this->follow->totalFollowings($user_id);
        $totalfollowing = array(
            'total_following' => $total,
        );
        $this->response([
            'status' => true,
            "message" => "Total followings.",
            "data" => $totalfollowing,
        ], REST_Controller::HTTP_OK);
    }
    //this function is used to get the follower list of the specific user
    public function get_followerslist_post()
    {
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            $this->response([
                'status' => false,
                "message" => "Please fill the all detail.",
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $followerData = $this->follow->get_followerslist($user_id);
            $fo = array();
            foreach ($followerData as $followersdata) {
                $isfollow = $this->follow->is_already_follow($followersdata->follower_id, $user_id);
                if ($isfollow) {
                    $abc = 1;
                } else {
                    $abc = 0;
                }
                $followersDetails = array(
                    'user_id' => $followersdata->user_id,
                    'Follower_id' => $followersdata->follower_id,
                    'Follow_date' => $followersdata->created_at,
                    'Full_name' => $followersdata->full_name,
                    'user_name' => $followersdata->user_name,
                    'picture' => $followersdata->picture,
                    'isFollow' => $abc,
                );
                array_push($fo, $followersDetails);
            }

            if ($fo) {
                $this->response([
                    'status' => true,
                    "message" => "Followers data.",
                    "data" => $fo,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    "message" => "No follower. ",
                    "data" => 0,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    //This function is used to get the following list
    public function getFollowingList_post()
    {
        $user_id = $this->input->post('user_id');
        if (empty($user_id)) {
            return false;
        } else {
            $followingData = $this->follow->get_FollowingList($user_id);
            $fo = array();
            foreach ($followingData as $followingdata) {
                $followingDetails = array(
                    'user_id' => $followingdata->follower_id,
                    'following_id' => $followingdata->user_id,
                    'Full_name' => $followingdata->full_name,
                    'user_name' => $followingdata->user_name,
                    'Following_date' => $followingdata->created_at,
                    'picture' => $followingdata->picture,
                );
                array_push($fo, $followingDetails);
            }
            if ($fo) {
                $this->response([
                    'status' => true,
                    "message" => "Following data.",
                    "data" => $fo,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    "message" => "No following. ",
                    "data" => 0,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    //This function will show the count of user
    public function CountProfileData_post()
    {
        $user_id = $this->input->post('user_id');

        //$user_type=$this->input->post('user_type');
        $followers = $this->follow->totalFollowers($user_id);
        $followings = $this->follow->totalFollowings($user_id);
        $user_type = $this->follow->GetUserType($user_id);
        $feeds = $this->follow->totalFeeds($user_id, $user_type);
        $Countdata = array(
            'total_followers' => $followers,
            'total_following' => $followings,
            'total_feeds' => $feeds,
        );
        $this->response([
            'status' => true,
            "message" => "Count profile data.",
            "data" => $Countdata,
        ], REST_Controller::HTTP_OK);
    }

    //This is the commmon function used to get the userprofile
    public function getUserProfile_post()
    {
        $user_id = $this->input->post('user_id');
        $follower_id = $this->input->post('follower_id');
        $session_key = $this->input->post('session_key');
        if (empty($user_id && $follower_id && $session_key)) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the fields.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $is_validate_user = $this->quoteshare_model->check_user_validation($follower_id, $session_key);
            if ($is_validate_user) {
                $followers = $this->follow->totalFollowers($user_id);
                $followings = $this->follow->totalFollowings($user_id);
                $user = $this->follow->getUserProfile($user_id);
                //print_r($user->user_type);die;
                $feeds = $this->follow->totalFeeds($user_id, $user->user_type);
                $is_follow = $this->follow->is_already_follow($user_id, $follower_id);
                unset($user->password);
                unset($user->email);
                unset($user->session_key);
                unset($user->device_token);
                if (@$user->is_active == 1) {
                    $Countdata = array(
                        'total_followers' => $followers,
                        'total_following' => $followings,
                        'total_feeds' => $feeds,
                        'is_follow' => $is_follow,
                        'user_data' => $user,

                    );
                    $this->response([
                        'status' => true,
                        "message" => "User profile.",
                        "data" => $Countdata,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        "message" => "User blocked.",
                        "data" => 2,
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
    }

}
