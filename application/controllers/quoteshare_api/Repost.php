<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "/libraries/REST_Controller.php";
class Repost extends REST_Controller
{

    private $param = array();
    private $loggedout_arr = array('sign_up', 'social_login', 'forgot_password', 'checkAvailability', 'login', 'foryou', 'getRepostFeeds');
    private $data = array();
    public $userid;
    public $sessionid;
    public $method;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Repost_model', 'repost');
        $this->load->model('quoteshare_model');
        $this->load->model('Feed_model');

        // $this->load->model('Repost_model','repost');
        $this->load->helper('string');
        $this->load->model('Followunfollow_model', 'follow');

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

    //this function is used to report the feed
    public function feedReport_post()
    {
        $feed_id = $this->input->post('feed_id');
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $report_text = $this->input->post('report_text');
        $title = $this->input->post('title');
        if (empty($feed_id && $user_id && $report_text)) {
            $this->response([
                'status' => false,
                'message' => 'Please fill all the detail',
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
            if ($is_validate_user == 1) {
                $is_AlreadyExist = $this->repost->isfeedthere($feed_id);
                if ($is_AlreadyExist) {
                    $report = array(
                        'feed_id' => $feed_id,
                        'user_id' => $user_id,
                        'report_text' => $report_text,
                        'title' => $title,
                    );
                    $feed_report = $this->repost->feedReport($report);
                    if ($feed_report) {
                        $this->response([
                            'status' => true,
                            'message' => 'Report successfullly.',
                            'data' => $feed_report,
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Report unsuccessfullly.',
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No feed found for report.',
                        'data' => $is_AlreadyExist,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status' => false,
                    "message" => "Unauthorized user.",
                    'data' => $is_validate_user,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    //This function is used to foryou search
    public function forYouSearch_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user == 1) {
            $foryou = $this->input->post('keyword');
            $match = $this->repost->ForYouSearch($foryou);
            if ($match) {
                $search = array();
                foreach ($match as $foryousearch) {
                    $totalFollowers = $this->follow->totalFollowers($foryousearch->user_id);
                    $searchresult = array('user_id' => $foryousearch->user_id,
                        'user_type' => $foryousearch->user_type,
                        'full_name' => $foryousearch->full_name,
                        'user_name' => $foryousearch->user_name,
                        'user_picture' => $foryousearch->picture,
                        'total_followers' => $totalFollowers,
                    );
                    array_push($search, $searchresult);
                }
                if ($search) {
                    $this->response([
                        'status' => true,
                        'message' => 'For you search.',
                        'data' => $search,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No result found for' . " " . $foryou . ".",
                        'data' => $search,
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No result found.',
                    'data' => 0,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //This function is used for repost
    public function repost_post()
    {
        $feed_id = $this->input->post('feed_id');
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        if (empty($feed_id && $user_id && $session_key)) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the fields.",
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user == 1) {

            //It will check feed is available or  not
            $is_AlreadyExist = $this->repost->isfeedthere($feed_id);

            if ($is_AlreadyExist) {
                //check is already repost or not by same user
                $is_repost = $this->repost->is_repost($user_id, $feed_id);
                //if repost is there then it will delete the repost
                if ($is_repost) {
                    $delete_repost = $this->repost->deleteRepost($user_id, $feed_id);
                    $delete_reshareCount = $this->repost->deleteResharingcount($user_id, $feed_id);
                    if ($delete_repost && $delete_reshareCount) {
                        $this->response([
                            'status' => true,
                            'message' => 'Unshare repost.',
                        ], REST_Controller::HTTP_OK);
                    }
                } else {
                    //this will get the feed data with feed_id
                    $match = $this->repost->feed_detail($feed_id);
                    $olduser_id = $match->user_id;
                    //here we are getting the data single single column data
                    $repost = array(
                        'category_id' => $match->category_id,
                        'user_id' => $user_id,
                        'image' => $match->image,
                        'quote' => $match->quote,
                        'caption' => $match->caption,
                        'author_id' => $match->author_id,
                        'booker_id' => $match->booker_id,
                        'tagger_id' => $match->tagger_id,
                        'tagger_id' => $match->tagger_id,
                        'parent_id' => $match->feed_id,
                    );
                    //This array will store in sharing count table for counting
                    $resharingCount = array('user_id' => $user_id,
                        'feed_id' => $feed_id);
                    //this will insert the old feed data into new feed
                    $result = $this->repost->insert('feeds', $repost);
                    $resharingTable = $this->repost->insert('resharingcount', $resharingCount);
                    //it will get the feed detail of respost feed
                    $newRepost = $this->repost->feed_detail($result);
                    $newUid = $newRepost->user_id;
                    $newFeedId = $newRepost->feed_id;

                    //this function will be get the original user data(original feed)

                    $RealFeedUser = $this->quoteshare_model->getuserdata($olduser_id);
                    //Here we get the original user data
                    // $originaluid=$RealFeedUser->user_id;
                    // $originalFname=$RealFeedUser->full_name;
                    // $originalUser_name=$RealFeedUser->user_name;
                    // $originalUtype=$RealFeedUser->user_type;
                    // $originalUicture=$RealFeedUser->picture;

                    $originalpost['realuser'] = array('Realuserid' => $RealFeedUser->user_id,
                        'realfullName' => $RealFeedUser->full_name,
                        'realUsername' => $RealFeedUser->user_name,
                        'realusertype' => $RealFeedUser->user_type,
                        'realuserpicture' => $RealFeedUser->picture,
                    );
                    //this function will show the user detail and feed detail
                    $final = array();

                    $Repostdata = $this->repost->view_feeds_detail($newUid, $newFeedId);
                    foreach ($Repostdata as $value) {

                        @$author = explode(',', $value->author_id);
                        @$booker = explode(',', $value->booker_id);
                        @$tagger = explode(',', $value->tagger_id);
                        $value->author = $this->Feed_model->UserNameId(@$author);
                        $value->booker = $this->Feed_model->UserNameId(@$booker);
                        $value->Tagger = $this->Feed_model->UserNameId(@$tagger);
                        $final[] = $value;
                    }

                    //print_r($final);die;

                    $final[] = $originalpost;
                    //$merged_arr=array_push($final,$originalpost );
                    //$merged_arr = array_merge($final,$originalpost);
                    $this->response([
                        'status' => true,
                        'message' => 'Quote reposted successfullly.',
                        'data' => $final,
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'post is not found for repost.',
                    'data' => $is_AlreadyExist,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //This function is used to get the repost feed of the user
    public function getRepostFeeds_post()
    {
        $user_id = $this->input->post('user_id');
        $loginuser_id = $this->input->post('loginuser_id');
        if (empty($user_id && $loginuser_id)) {
            $this->response([
                'status' => false,
                "message" => "Please send all the data.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            //Check repost data is there or not
            $validation = $this->repost->check_repost($user_id);
            if ($validation == 0) {
                $this->response([
                    'status' => false,
                    'message' => 'No repost data found.',
                    'data' => $validation,
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $repostAllData = array();

                $match = $this->repost->getMyRepostFeeds($user_id, $loginuser_id); //it will get the repost feeds
                // print_r($match);exit;
                foreach ($match as $repostFeeds) {

                    $author = explode(',', $repostFeeds->author_id);
                    $booker = explode(',', $repostFeeds->booker_id);
                    $tagger = explode(',', $repostFeeds->tagger_id);
                    $author = $this->Feed_model->UserNameId($author);
                    $booker = $this->Feed_model->UserNameId($booker);
                    $Tagger = $this->Feed_model->UserNameId($tagger);
                    //it will get the detail of parent feed
                    $real = $this->repost->getId($repostFeeds->parent_id);
                    //print_r($real);
                    $userdetail = $this->repost->getUserDetail($real);

                    $Isfollower = $this->follow->is_already_follow($userdetail['user_id'], $loginuser_id);
                    if ($Isfollower) {
                        $follow = 1;
                    } else {
                        $follow = 0;
                    }
                    //print_r($userdetail);
                    $mergedata = array(
                        'feed_id' => $repostFeeds->feed_id,
                        'category_id' => $repostFeeds->category_id,
                        'user_id' => $repostFeeds->user_id,
                        'quote_image' => $repostFeeds->image,
                        'quote' => $repostFeeds->quote,
                        'caption' => $repostFeeds->caption,
                        'author_id' => $repostFeeds->author_id,
                        'booker_id' => $repostFeeds->booker_id,
                        'tagger_id' => $repostFeeds->tagger_id,
                        'parent_id' => $repostFeeds->parent_id,
                        'created_at' => $repostFeeds->created_at,
                        'userFull_Name' => $repostFeeds->full_name,
                        'user_picture' => $repostFeeds->picture,
                        'repostCount' => $repostFeeds->RepostCount,
                        'TotalLikes' => $repostFeeds->totalLikes,
                        'TotalRepost' => $repostFeeds->totalQuotes,
                        'IsLiked' => $repostFeeds->isLiked,
                        'isfollowing' => $follow,
                        'IsRepost' => $repostFeeds->isRepost,
                        'TotalComments' => $repostFeeds->totalComments,
                        'originalUser_id' => $userdetail['user_id'],
                        'originalFull_name' => $userdetail['full_name'],
                        'originalUser_name' => $userdetail['user_name'],
                        'originaluser_type' => $userdetail['user_type'],
                        'originaluser_picture' => $userdetail['picture'],
                        'author' => $author,
                        'book' => $booker,
                        'tagger' => $Tagger,
                    );
                    array_push($repostAllData, $mergedata);
                }
                //print_r($repostAllData);die;
                $this->response([
                    'status' => true,
                    'message' => 'Repost feed data.',
                    'data' => $repostAllData,
                ], REST_Controller::HTTP_OK);
            }}
    }
    public function getRepostFeedsBYPopularity_post()
    {
        $user_id = $this->input->post('user_id');
        $loginuser_id = $this->input->post('loginuser_id');
        if (empty($user_id && $loginuser_id)) {
            $this->response([
                'status' => false,
                "message" => "Please send all the data.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $validation = $this->repost->check_repost($user_id);
            if ($validation == 0) {
                $this->response([
                    'status' => false,
                    'message' => 'No repost data found.',
                    'data' => $validation,
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $match = $this->repost->getMyRepostFeedsByPopularity($user_id, $loginuser_id); //it will get the repost feeds
                $repostAllData = array();
                foreach ($match as $repostFeeds) {
                    $author = explode(',', $repostFeeds->author_id);
                    $booker = explode(',', $repostFeeds->booker_id);
                    $tagger = explode(',', $repostFeeds->tagger_id);
                    $author = $this->Feed_model->UserNameId($author);
                    $booker = $this->Feed_model->UserNameId($booker);
                    $Tagger = $this->Feed_model->UserNameId($tagger);
                    //it will get the detail of parent feed
                    $real = $this->repost->getId($repostFeeds->parent_id);
                    //print_r($real);
                    $userdetail = $this->repost->getUserDetail($real);
                    $Isfollower = $this->follow->is_already_follow($userdetail['user_id'], $user_id);
                    if ($Isfollower) {
                        $follow = 1;
                    } else {
                        $follow = 0;
                    }
                    //print_r($userdetail);
                    $mergedata = array(
                        'feed_id' => $repostFeeds->feed_id,
                        'category_id' => $repostFeeds->category_id,
                        'user_id' => $repostFeeds->user_id,
                        'quote_image' => $repostFeeds->image,
                        'quote' => $repostFeeds->quote,
                        'caption' => $repostFeeds->caption,
                        'author_id' => $repostFeeds->author_id,
                        'booker_id' => $repostFeeds->booker_id,
                        'tagger_id' => $repostFeeds->tagger_id,
                        'parent_id' => $repostFeeds->parent_id,
                        'created_at' => $repostFeeds->created_at,
                        'userFull_Name' => $repostFeeds->full_name,
                        'user_picture' => $repostFeeds->picture,
                        'repostCount' => $repostFeeds->RepostCount,
                        'TotalLikes' => $repostFeeds->totalLikes,
                        'TotalRepost' => $repostFeeds->totalQuotes,
                        'IsLiked' => $repostFeeds->isLiked,
                        'isfollowing' => $follow,
                        'IsRepost' => $repostFeeds->isRepost,
                        'TotalComments' => $repostFeeds->totalComments,
                        'originalUser_id' => $userdetail['user_id'],
                        'originalFull_name' => $userdetail['full_name'],
                        'originalUser_name' => $userdetail['user_name'],
                        'originaluser_type' => $userdetail['user_type'],
                        'originaluser_picture' => $userdetail['picture'],
                        'author' => $author,
                        'book' => $booker,
                        'tagger' => $Tagger,
                    );
                    array_push($repostAllData, $mergedata);
                }
                $this->response([
                    'status' => true,
                    'message' => 'Repost feed data.',
                    'data' => $repostAllData,
                ], REST_Controller::HTTP_OK);
            }
        }
    }
    #__________________
}
