<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "/libraries/REST_Controller.php";
class Feeds_api extends REST_Controller
{

    private $param = array();
    private $loggedout_arr = array('sign_up', 'social_login', 'forgot_password', 'checkAvailability', 'login', 'ViewAllFeeds', 'getMyFeed', 'FeedByCategory', 'popularitySort', 'GetAuthorName', 'getBooksName', 'GetComments', 'GetTagsName', 'GetAuthor', 'ShowBannerById');
    private $data = array();
    public $userid;
    public $sessionid;
    public $method;

    public function __construct()
    {
        parent::__construct();
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
    //This function is used to view the feed with user_id,session_key,feed_id
    public function view_feeds_detail_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $feed_id = $this->input->post('feed_id');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user == 1) {
            $user_id = $this->input->post('user_id');
            $session_key = $this->input->post('session_key');
            $feed_id = $this->input->post('feed_id');
            $result = $this->Feed_model->view_feeds_detail($user_id, $feed_id);
            $this->response([
                'status' => true,
                "data" => $result,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                'data' => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    // Function end

    //This function used to like or unlike the post
    public function LikeUnlikeFeed_post()
    {
        $session_key = $this->input->post('session_key');
        $user_id = $this->input->post('user_id');
        $feed_id = $this->input->post('feed_id');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user) {
            $IsFeedExist = $this->Feed_model->isfeedthere($feed_id);
            if ($IsFeedExist) {
                $Isuser_exist = $this->Feed_model->is_already_liked($user_id, $feed_id);
                // $Isuser_exist=$this->Feed_model->user_like($user_id,$feed_id);
                if ($Isuser_exist) {
                    $likeDelete = $this->Feed_model->delete_LikeUnlike('feed_likes', $feed_id, $user_id);
                    $this->response([
                        'status' => false,
                        "message" => "Unliked.",
                        "data" => $likeDelete,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $like = array(
                        'feed_id' => $feed_id,
                        'user_id' => $user_id);
                    $quote_liked = $this->Feed_model->DoLike('feed_likes', $like);
                    $this->response([
                        'status' => true,
                        "message" => "Liked.",
                        "data" => $quote_liked,
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    "message" => "No feed found.",
                    "data" => $IsFeedExist,
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                "message" => "Unauthorized user.",
                "data" => $is_validate_user,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //This function is used to post the comment on post
    public function CommentOnFeed_post()
    {
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $feed_id = $this->input->post('feed_id');
        $comment = $this->input->post('comment');
        //here i will mention the form_validation
        if (!$user_id || !$session_key || !$feed_id || !$comment) {
            //return null;
            $this->response([
                'status' => false,
                "message" => "All feeds are required.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
            if ($is_validate_user) {
                $IsFeedExist = $this->Feed_model->isfeedthere($feed_id);
                if ($IsFeedExist) {
                    $DoComment = array(
                        'user_id' => $user_id,
                        'feed_id' => $feed_id,
                        'comment' => $comment,
                    );
                    $comment = $this->Feed_model->CommentOnFeed('feed_comments', $DoComment);
                    $this->response([
                        'status' => true,
                        "message" => "Comment posted successfully.",
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => "No feed found for comment.",
                        'data' => $IsFeedExist,
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
    //this function is used to get the all like on post
    public function GetLikes_post()
    {
        $feed_id = $this->input->post('feed_id');
        $GetLikes = $this->Feed_model->get_like($feed_id);
        $this->response([
            'status' => true,
            'data' => $GetLikes,
        ], REST_Controller::HTTP_OK);
    }
    //this function is used for get the total comments on single post with username and picture
    public function GetComments_post()
    {
        $feed_id = $this->input->post('feed_id');
        $GetMessages = $this->Feed_model->get_comments($feed_id);
        // foreach($GetMessages as $message){
        //     $name=$message->full_name;
        //     $picture=base_url().'uploads/quotes/'.$message->picture;
        // }
        // print_r($picture);
        // exit;
        $this->response([
            'status' => true,
            'data' => $GetMessages,
        ], REST_Controller::HTTP_OK);
    }
    //This functio is used for delete the posted comment
    public function DeletefeedComment_post()
    {
        $comment_id = $this->input->post('comment_id');
        $feed_id = $this->input->post('feed_id');
        $user_id = $this->input->post('user_id');
        $deleteComment = $this->Feed_model->DeletefeedComment('feed_comments', $comment_id, $feed_id, $user_id);
        $this->response([
            'status' => true,
            'Message' => 'Comment delete successfully.',
            'data' => $deleteComment,
        ], REST_Controller::HTTP_OK);
    }
    //This fuction is used to edit the feed in feeds table
    public function EditFeed_post()
    {
        $feed_id = $this->input->post('feed_id');
        $user_id = $this->input->post('user_id');
        $session_key = $this->input->post('session_key');
        $is_validate_user = $this->quoteshare_model->check_user_validation($user_id, $session_key);
        if ($is_validate_user) {
            $is_FeedAlreadyExist = $this->quoteshare_model->isfeedthere($feed_id, $user_id);
            if ($is_FeedAlreadyExist) {
                $EditFeedData = array(
                    'image' => $this->input->post('image'),
                    'category_id' => $this->input->post('category_id'),
                    'quote' => $this->input->post('quote'),
                    'caption' => $this->input->post('caption'),
                    'author_id' => $this->input->post('author_id'),
                    'booker_id' => $this->input->post('booker_id'),
                    'tagger_id' => $this->input->post('tag_id'));
                $UpdateFeed = array_filter($EditFeedData);
                $editfeed = $this->Feed_model->EditFeed($feed_id, $user_id, $UpdateFeed);
                if ($editfeed) {
                    $updateRepost = $this->Feed_model->updateRepost($feed_id, $UpdateFeed);
                    $this->response([
                        'status' => true,
                        'Message' => 'Update successfully.',
                        'data' => $editfeed,
                    ], REST_Controller::HTTP_OK);

                }
            } else {
                $this->response([
                    'status' => false,
                    "message" => "No feed found.",
                    "data" => $is_FeedAlreadyExist,
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

    public function getFeeds_post()
    {
        $result = $this->Feed_model->getFeeds();
        foreach ($result as $data) {
            $likes = $this->Feed_model->get_like($data->feed_id);
        }
    }
    public function ViewAllFeeds_post()
    {

        $user_id = $this->input->post('user_id');
        //check follower list
        $con = "where follower_id= '" . $user_id . "'";
        $check = $this->Feed_model->getCountAnything('followers', 'follow_id', $con);
        if ($check == 0) {

            $this->response([
                'status' => false,
                'Message' => 'Opps you are not following anyone yet.',
            ], REST_Controller::HTTP_ACCEPTED);
        } else {
            $result = $this->Feed_model->ViewAllFeeds($user_id);
            $final = array();
            foreach ($result as $value) {
                $author = explode(',', $value->author_id);
                $booker = explode(',', $value->booker_id);
                $tagger = explode(',', $value->tagger_id);
                $value->author = $this->Feed_model->UserNameId($author);
                $value->booker = $this->Feed_model->UserNameId($booker);
                $value->Tagger = $this->Feed_model->UserNameId($tagger);
                $final[] = $value;
            }
            if ($final) {
                $this->response([
                    'status' => true,
                    'message' => 'All quotes.',
                    'data' => $final,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No quote is found.',
                    'data' => $final,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function getOneUserFeeds_post()
    {
        $user_id = $this->input->post('user_id');
        $result = $this->Feed_model->ViewAllFeeds($user_id);
        if ($result) {
            foreach ($result as $alldetail) {
                $ds = $this->Feed_model->get_like($alldetail->feed_id);
                $GetMessages = $this->Feed_model->get_comments($alldetail->feed_id);
                $er[] = $alldetail;
                $er[] = $ds;
                $er[] = $GetMessages;
            }
            $this->response([
                'status' => true,
                'Message' => 'Fetched data.',
                'data' => $er,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No quote found.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //this function will show the list of posted quotes by particular user and also total count of quotes that you have posted
    public function getMyFeed_post()
    {
        $user_id = $this->input->post('user_id');
        $user_type = $this->input->post('user_type');
        $loginuser_id = $this->input->post('loginuser_id');
        if (empty($user_id && $user_type)) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the fields.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $getmyfeed = $this->Feed_model->getMyFeed($user_id, $user_type, $loginuser_id);
            $final = array();
            foreach ($getmyfeed as $value) {
                $author = explode(',', $value->author_id);
                $booker = explode(',', $value->booker_id);
                $tagger = explode(',', $value->tagger_id);
                $value->author = $this->Feed_model->UserNameId($author);
                $value->booker = $this->Feed_model->UserNameId($booker);
                $value->Tagger = $this->Feed_model->UserNameId($tagger);
                $final[] = $value;
            }
            if ($final) {
                $this->response([
                    'status' => true,
                    'Message' => 'My quotes.',
                    'data' => $final,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'Message' => 'No quotes.',
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    //This function is used to get the list of all author
    public function getAuthor_post()
    {
        $authors = $this->Feed_model->getAuthor();
        $this->response([
            'status' => true,
            'Message' => 'Author names.',
            'data' => $authors,
        ], REST_Controller::HTTP_OK);
    }

    //This function is used to get the list of all books
    public function getBook_post()
    {
        $books = $this->Feed_model->getBook();
        $this->response([
            'status' => true,
            'Message' => 'Book names.',
            'data' => $books,
        ], REST_Controller::HTTP_OK);
    }

    //this function will get the tags name
    public function getTags_post()
    {
        $tags = $this->Feed_model->getTags();
        if ($tags) {
            $this->response([
                'status' => true,
                'Message' => 'Tags.',
                'data' => $tags,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No tag found.',
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //this function get the names of the auhors
    public function getAuthorsName_post()
    {
        $keyword = $this->input->post('keyword');
        $authors = $this->Feed_model->getAuthorsName($keyword);
        $usertype = array();
        foreach ($authors as $author) {
            $author = array('Author_id' => $author->user_id,
                'Author_name' => $author->full_name,
                'Author_picture' => $author->picture);
            array_push($usertype, $author);
        }

        if ($usertype) {
            $this->response([
                'status' => true,
                'Message' => 'Authors name.',
                'data' => $usertype,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No author found.',
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //this function get the names of book
    public function getBooksName_post()
    {
        $keyword = $this->input->post('keyword');
        $book = $this->Feed_model->getBooksName($keyword);
        $usertype = array();
        foreach ($book as $booksname) {
            $booker = array('booker_id' => $booksname->user_id,
                'Book_name' => $booksname->full_name,
                'Book_picture' => $booksname->picture,
            );
            array_push($usertype, $booker);
        }

        if ($usertype) {
            $this->response([
                'status' => true,
                'Message' => 'Books name.',
                'data' => $usertype,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No book found.',
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    //this function get the names of the tag
    public function getTagsName_post()
    {
        $keyword = $this->input->post('keyword');
        $tag = $this->Feed_model->getTagName($keyword);
        $usertype = array();
        foreach ($tag as $tagsname) {
            $taguser = array('tagger_id' => $tagsname->user_id,
                'tag_name' => $tagsname->full_name,
                'tag_picture' => $tagsname->picture,
            );
            array_push($usertype, $taguser);
        }

        if ($usertype) {
            $this->response([
                'status' => true,
                'Message' => 'Tags name.',
                'data' => $usertype,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No tag found.',
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //This function is used to get the feed by category_id
    public function FeedByCategory_post()
    {
        $category_id = $this->input->post('category_id');
        $loginuser_id = $this->input->post('loginuser_id');
        $sort = $this->input->post('sort');
        if (empty($category_id && $sort)) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the fields.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $category = $this->Feed_model->FeedByCategory($category_id, $loginuser_id, $sort);
            $final = array();
            foreach ($category as $value) {
                $author = explode(',', $value->author_id);
                $booker = explode(',', $value->booker_id);
                $tagger = explode(',', $value->tagger_id);
                $value->author = $this->Feed_model->UserNameId($author);
                $value->booker = $this->Feed_model->UserNameId($booker);
                $value->Tagger = $this->Feed_model->UserNameId($tagger);
                $final[] = $value;
            }
            if ($final) {
                $this->response([
                    'status' => true,
                    'Message' => 'Feeds.',
                    'data' => $category,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'Message' => 'No search found.',
                    'data' => 0,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }
    //this function used to sort according to the popularity of the feed
    public function getMyFeedByPopularity_post()
    {
        $user_id = $this->input->post('user_id');
        $user_type = $this->input->post('user_type');
        $loginuser_id = $this->input->post('loginuser_id');
        if (empty($user_id && $user_type && $loginuser_id)) {
            $this->response([
                'status' => false,
                "message" => "Please fill all the fields.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $sort = $this->Feed_model->getMyFeedByPopularity($user_id, $user_type, $loginuser_id);
            $final = array();
            foreach ($sort as $value) {
                $author = explode(',', $value->author_id);
                $booker = explode(',', $value->booker_id);
                $tagger = explode(',', $value->tagger_id);
                $value->author = $this->Feed_model->UserNameId($author);
                $value->booker = $this->Feed_model->UserNameId($booker);
                $value->Tagger = $this->Feed_model->UserNameId($tagger);
                $final[] = $value;
            }
            if ($final) {
                $this->response([
                    'status' => true,
                    'Message' => 'Feed by popularity.',
                    'data' => $final,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'Message' => 'No feed found.',
                    'data' => $final,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    //This function is used to search tag by keyword
    public function SearchTagByKeyword_post()
    {
        $keyword = $this->input->post('keyword');
        $searchTag = $this->Feed_model->SearchTagByKeyword($keyword);
        if ($searchTag) {
            $searchingTag = array();
            foreach ($searchTag as $tag) {
                $taguser = array('tagger_id' => $tag->user_id,
                    'tag_name' => $tag->full_name,
                    'tag_picture' => $tag->picture,
                );
                array_push($searchingTag, $taguser);
            }
            if ($searchingTag) {
                $this->response([
                    'status' => true,
                    'message' => 'Tag by search.',
                    'data' => $searchingTag,
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'Message' => 'No tag found.',
                'data' => 0,
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    //This function is used to get user feeds except report feeds
    public function fetchUserFeeds_post()
    {
        $user_id = $this->input->post('user_id');
        $loginUser_id = $this->input->post('loginuser_id');
        if (!$user_id || !$loginUser_id) {
            //return null;
            $this->response([
                'status' => false,
                "message" => "All feeds are required.",
            ], REST_Controller::HTTP_NOT_FOUND);
        } else {
            $result = $this->Feed_model->fetchUserFeeds($user_id, $loginUser_id);
            if ($result) {
                $this->response([
                    'status' => true,
                    'message' => 'User feeds.',
                    'data' => $result,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'Message' => 'No feed is found.',
                    'data' => $result,
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    #______________

    #_______add tag
    public function addnewTagger_post()
    {
        $param = $_POST;
        $is_validate_user = $this->quoteshare_model->check_user_validation(@$param['user_id'], @$param['session_key']);
        if ($is_validate_user) {

            $totaltags = array();
            $name = explode(",", $param['keyword']);
            //$count=count($name);
            foreach ($name as $key) {
                $tag = $this->Feed_model->addnewTagger($key);
                array_push($totaltags, $tag);
            }
            $fintag = implode($totaltags, ",");
            $data = array('status' => 200, 'data' => $fintag);
            $this->response($data, $data['status']);
        } else {
            $data = array('status' => 401, 'message' => 'Unauthorized user.');
            $this->response($data, $data['status']);
        }
    }
    #_________ end add tag

    #___________Add new author
    public function addNewAuthor_post()
    {
        $param = $_POST;
        $author = intval($this->Feed_model->addAuthor($param['author_name']));
        $this->response([
            'status' => true,
            "data" => $author,
        ], REST_Controller::HTTP_OK);
    }
    #_______End author

#___________Add new author
    public function addNewBook_post()
    {
        $param = $_POST;
        $book = $this->Feed_model->addBook($param['book_name']);
        $data = array('status' => 200, 'data' => $book);
        $this->response($data, $data['status']);
    }
    #_______End author

    public function ShowBannerById_post()
    {
        $param = $_POST;
        $check = array('banner_category' => @$param['banner_category']);
        if (checkRequired($check)) {
            $array = array('status' => 400, 'message' => "Required Parameters : " . checkRequired($check));
            $this->response($array, $array['status']);
        } else {
            $banner = $this->Feed_model->ShowBannerById($param['banner_category']);
            $this->response($banner, $banner['status']);
        }
    }
//End Query

}
