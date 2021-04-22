<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Adminpanel extends CI_Controller
{

    private $loggedout_arr = array('adminlogin');
    public $id;
    public $sessionkey;
    public $method;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Feed_model');
        $this->load->helper('string');
        error_reporting(0);
        $this->method = $this->uri->segment(1);
        if (!empty($this->method) && $this->method !== null) {
            if (!in_array($this->method, $this->loggedout_arr)) {

                if (!$this->session->userdata('userdata')) {
                    redirect('adminlogin');
                }
            }
        }
    }

    public function index()
    {
        redirect('adminlogin');
    }

    public function dashboard()
    {
        // if($this->session->userdata('userdata')){
        //   redirect('users');
        // }
        $this->users();
    }

    public function login()
    {
        if ($this->session->userdata('userdata')) {
            redirect('users');
        } else {
            $_error['error'] = "";
            $input = $this->input->post();
            if ($input) {
                $this->form_validation->set_rules('email', 'Email', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
                if ($this->form_validation->run() == false) {
                    $error['error'] = $this->form_validation->error_array();
                } else {
                    $login = array(
                        'email' => $this->input->post('email'),
                        'password' => md5($this->input->post('password')),
                    );
                    $login_data = $this->Admin_model->login($login);
                    unset($login_data->password);
                    if (@$login_data) {
                        $this->session->set_userdata('userdata', $login_data);
                        $this->session->set_flashdata('msg', 'Login Successfully...');
                        redirect('users');
                    } else {
                        $this->session->set_flashdata('login', 'Email or password does not match');
                    }
                }
            }
            $this->load->view('login');
        }
    }
    public function logout()
    {
        $this->session->unset_userdata('userdata');
        redirect('adminlogin');
    }

    //this function is used to show all the user in quoteshare
    public function users()
    {
        $users['usersdata'] = $this->Admin_model->users();
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('datatable', $users);
        $this->load->view('include/footer');
    }

    //this function used to get the user_id and find the record
    public function edituser($user_id)
    {
        $id = base64_decode(urldecode($user_id));
        $input = $this->input->post();
        // if form is submitted then this if condition will execute
        if ($input) {
            $user_id = $this->input->post('user_id');
            $this->form_validation->set_rules('user_type', 'User Type', 'required|callback_check_default');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required');
            $this->form_validation->set_rules('bio', 'Bio', 'required');
            if ($this->form_validation->run() == false) {
                $this->form_validation->error_array();
                $userdetail['userDetail'] = $this->Admin_model->getUserData($id);
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('edituser', $userdetail);
                $this->load->view('include/footer');
            } else {
                if (!empty($_FILES['user_image']['name'])) {
                    $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["user_image"]['name'];
                    $config['file_name'] = $picture;
                    $config['upload_path'] = './uploads/quotes';
                    $config['allowed_types'] = 'gif|jpeg|png|jpg';
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('user_image')) {
                        $error = array('error' => $this->upload->display_errors());
                    } else {
                        $datt = $this->upload->data();
                        $file_array = $this->upload->data('full_path');
                        $profile_picture = $datt['file_name'];
                    }
                } else {
                    $profile_picture = $this->input->post('user_Oldimage');
                }

                $updateUser = array(
                    'user_type' => $this->input->post('user_type'),
                    'full_name' => $this->input->post('full_name'),
                    'bio' => rtrim($this->input->post('bio')),
                    'picture' => $profile_picture,
                );
                if ($updateUser['user_type'] == 1) {
                    $user = 'Normalusers';
                } elseif ($updateUser['user_type'] == 2) {
                    $user = 'Authors';
                } elseif ($updateUser['user_type'] == 3) {
                    $user = 'books';
                } else {
                    $user = 'users';
                }
                $this->Admin_model->updateUser($user_id, $updateUser);
                $this->session->set_flashdata('update', 'User update successfully');
                redirect($user);
            }

        } else {
            $userdetail['userDetail'] = $this->Admin_model->getUserData($id);
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('edituser', $userdetail);
            $this->load->view('include/footer');

        }
    }

    //This function is used to add new user
    public function addNewUser()
    {
        $input = $this->input->post();
        if ($input) {
            //$this->form_validation->set_rules('user_type','User Type','required');
            $this->form_validation->set_rules('full_name', 'Full Name', 'required');
            $this->form_validation->set_rules('bio', 'Bio', 'required');
            $this->form_validation->set_rules('username', 'User Name', 'required|is_unique[users.user_name]|alpha_numeric');

            $this->form_validation->set_message('is_unique', 'This is used by other user, please fill unique value.');
            $this->form_validation->set_message('alpha_numeric', 'The Username field may only contain alpha-numeric characters without space.');

            $this->form_validation->set_rules('email', 'email', 'required|is_unique[users.Email]');
            $this->form_validation->set_message('email', 'This email is used by other user.');

            $this->form_validation->set_rules('password', 'password', 'required|min_length[8]');
            $this->form_validation->set_rules('user_type', 'User Type', 'required|callback_check_default');
            //$this->form_validation->set_message('check_default', 'Please Select the User Type');

            if ($this->form_validation->run() == false) {
                $this->form_validation->error_array();
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('addnewuser');
                $this->load->view('include/footer');
            } else {
                $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["user_image"]['name'];
                $config['file_name'] = $picture;
                $config['upload_path'] = './uploads/quotes';
                //$config['allowed_types']= 'gif|jpeg|png|jpg';
                $config['allowed_types'] = '*';
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('user_image')) {
                    echo $error = array('error' => $this->upload->display_errors());
                } else {
                    $imagef = $this->upload->data();
                    //$mainimage = strtotime(date('Y-m-d H:i:s')).$image;
                    $file_array = $this->upload->data('full_path');
                    $user_image = $imagef['file_name'];

                }
                //print_r($imagef);die;
                $addUser = array(
                    'user_type' => $this->input->post('user_type'),
                    'full_name' => $this->input->post('full_name'),
                    'bio' => $this->input->post('bio'),
                    'user_name' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'picture' => $user_image,
                    'password' => md5($this->input->post('password')),
                );
                $user = $this->Admin_model->adduser('users', $addUser);
                $this->session->set_flashdata('adduser', 'New user added successfully');
                redirect('users');
            }
        } else {
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('addnewuser');
            $this->load->view('include/footer');
        }
    }

    //This function check the select box validation
    public function check_default($row)
    {
        if ($row === '') {
            $this->form_validation->set_message('user_type', 'Please Select the User Type');
            return false;
        } else {
            return true;
        }
    }

    //This function is used to block the user
    public function blockuser()
    {
        $user_id = $this->input->post('userid');
        $status = $this->input->post('ustatus');
        $user_status = $this->Admin_model->blockuser($user_id, $status);
        echo $status;
    }
    //this function used to show and edit the profile
    public function admin_profile()
    {
        $input = $this->input->post();

        if ($input) {
            $this->form_validation->set_rules('fullname', 'Full Name', 'required');
            $this->form_validation->set_rules('username', 'User Name', 'required|alpha_numeric');
            $this->form_validation->set_message('alpha_numeric', 'The Username field may only contain alpha-numeric characters without space.');
            if ($this->form_validation->run() == false) {
                $this->form_validation->error_array();
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('profile', @$user);
                $this->load->view('include/footer');
            } else {
                $id = $this->session->userdata['userdata']->admin_id;
                $updatedata = array(
                    'full_name' => $this->input->post('fullname'),
                    'user_name' => $this->input->post('username'),
                );
                $data = $this->Admin_model->updateProfileDetail($id, $updatedata);
                $this->session->set_userdata('userdata', $data);
                $this->session->set_flashdata('update', 'Update Successfully...');
                redirect('adminprofile');
            }
        } else {
            $id = $this->session->userdata['userdata']->admin_id;
            $user['user'] = $this->Admin_model->admin_profile($id);
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('profile', $user);
            $this->load->view('include/footer');
        }
        // }
    }

    public function updateProfilePicture()
    {
        if (!empty($_FILES['newimage']['name'])) {
            $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["newimage"]['name'];
            $config['file_name'] = $picture;
            $config['upload_path'] = './admin';
            $config['allowed_types'] = 'gif|jpeg|png|jpg';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('newimage')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $datt = $this->upload->data();
                $file_array = $this->upload->data('full_path');
                $profile_picture = $datt['file_name'];
            }
        } else {
            $profile_picture = $this->input->post('oldimage');
        }
        $id = $this->session->userdata['userdata']->admin_id;
        $update = $this->Admin_model->updateProfilePicture($id, $profile_picture);
        $sessiondata = $this->Admin_model->admin_profile($id);
        $this->session->set_userdata('userdata', $sessiondata);
        redirect('adminprofile');

    }

    //This function used to addTag in tags table
    public function addTag()
    {
        $input = $this->input->post();
        if ($input) {
            $this->form_validation->set_rules('tag', 'Tag', 'required');
            if ($this->form_validation->run() == false) {
                $this->form_validation->error_array();
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('addtag');
                $this->load->view('include/footer');
            } else {
                $tag = array('tag' => $this->input->post('tag'));
                $tag_id = $this->Admin_model->adduser('tags', $tag);
                $this->session->set_flashdata('tag', 'Tag Added Successfully.');
                redirect('AddTag');
            }
        } else {
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('addtag');
            $this->load->view('include/footer');
        }
    }

    //This function is used to show the tag
    public function tags()
    {
        $tags['tag'] = $this->Admin_model->tags();
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('tags', $tags);
        $this->load->view('include/footer');
    }

    //This function is used to block the tag
    public function blockTag()
    {
        $tag_id = $this->input->post('tag_id');
        $status = $this->input->post('tagstatus');
        $user_status = $this->Admin_model->blockTag($tag_id, $status);
        echo $status;
    }

    #This function is used to get the Normal users
    public function Normalusers()
    {
        $users['users'] = $this->Admin_model->NormalUser(1);
        //print_r($users);
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('normaluser', $users);
        $this->load->view('include/footer');
    }
    #___________End

  #______LOAD AUTHOR DATA TO VIEW___________
    public function Authors()
    {
        $users['users'] = $this->Admin_model->NormalUser(2);
        //print_r($users);
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('authors', $users);
        $this->load->view('include/footer');
    }
#_____________________________

#_______________LOAD BOOK DATA TO VIEW_______
    public function Books()
    {
        $users['users'] = $this->Admin_model->NormalUser(3);
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('books', $users);
        $this->load->view('include/footer');
    }
#_________________________________________END

    #_______________LOAD BOOK DATA TO VIEW_______
    public function Quotes()
    {
        $quotes = $this->Admin_model->Quotes();

        for ($i = 0; $i < count($quotes); $i++) {

            $CategoryNames = array();
            $category_id = $quotes[$i]->category_id;
            $categoryArray = explode(',', $category_id);

            for ($j = 0; $j < count($categoryArray); $j++) {
                $cate = array(
                    'category_id' => $categoryArray[$j]);
                $category_name = $this->Admin_model->NameById('quotes_category', $cate);

                array_push($CategoryNames, $category_name->category_name);

            }

            $commaCategory12 = implode(',', $CategoryNames);
            $commaCategory = rtrim($commaCategory12, ',');

            $quotes[$i]->CategoryName = $commaCategory;
        }
        // echo '<pre>';
        // print_r($quotes);die;
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        $this->load->view('quotes', compact('quotes'));
        $this->load->view('include/footer');
    }
#_________________________________________END

//This function is used to block the user
    public function blockfeed()
    {
        $param = $_POST;
        $user_status = $this->Admin_model->blockFeed('feeds', $param['feed_id'], $param['ustatus']);
        echo $param['ustatus'];
    }
#______________

//This function is used to show the all comments
    public function ShowComments()
    {
        $param = $_POST;
        $ab['comments'] = $this->Feed_model->get_comments($param['feed_id']);
        echo $this->load->view('comments', $ab, true);

    }

    //This function is used to hide the comment
    public function CommentBlock()
    {
        $param = $_POST;
        $this->Admin_model->commentblock($param['comment_id'], $param['ustatus']);
        echo $param['ustatus'];
        //END FUNCTION

    }
//this function is used to count the comment of the feed
    public function countcomments()
    {
        $param = $_POST;
        $ab = $this->Admin_model->countcomments($param['feed_id']);
        echo $ab;

    }

    //This  function is used to show the all comments
    public function allcomment($id)
    {
        $ab['comments'] = $this->Admin_model->Comemnts($id);
        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        //$this->load->view('quotes',compact('quotes'));
        $this->load->view('allcomments', $ab);
        $this->load->view('include/footer');
    }
#________END QUERY

    //This function is used to show the report
    public function reports()
    {
        $quotes = $this->Admin_model->Reports();
        for ($i = 0; $i < count($quotes); $i++) {

            $CategoryNames = array();
            $category_id = $quotes[$i]->category_id;
            $categoryArray = explode(',', $category_id);

            for ($j = 0; $j < count($categoryArray); $j++) {
                $cate = array(
                    'category_id' => $categoryArray[$j]);
                $category_name = $this->Admin_model->NameById('quotes_category', $cate);

                array_push($CategoryNames, $category_name->category_name);

            }

            $commaCategory12 = implode(',', $CategoryNames);
            $commaCategory = rtrim($commaCategory12, ',');

            $quotes[$i]->CategoryName = $commaCategory;
        }

        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        //$this->load->view('quotes',compact('reports'));
        $this->load->view('reports', compact('quotes'));
        $this->load->view('include/footer');
    }
    #______________Banners
    public function Banners()
    {
        $banner['banner'] = $this->Admin_model->getAllData('banner');

        $this->load->view('include/sidebar');
        $this->load->view('include/navbar');
        //$this->load->view('quotes',compact('reports'));
        $this->load->view('banners', $banner);
        $this->load->view('include/footer');
    }

    #_______Add new banners
    public function AddnewBanner()
    {
        $input = $this->input->post();
        if ($input) {
            // print_r($_FILES["user_image"]['name']);die;
            $this->form_validation->set_rules('banner_category', 'Banner Category', 'required');
            if (empty($_FILES['user_image']['name'])) {
                $this->form_validation->set_rules('user_image', 'Image', 'required');
            }
            if ($this->form_validation->run() == false) {
                $error = $this->form_validation->error_array();
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('addnewbanner');
                $this->load->view('include/footer');
            } else {
                $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["user_image"]['name'];
                $config['file_name'] = $picture;
                $config['upload_path'] = './uploads/banner';
                //$config['allowed_types']= 'gif|jpeg|png|jpg';
                $config['allowed_types'] = '*';
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('user_image')) {
                    echo $error = array('error' => $this->upload->display_errors());
                } else {
                    $imagef = $this->upload->data();
                    //$mainimage = strtotime(date('Y-m-d H:i:s')).$image;
                    $file_array = $this->upload->data('full_path');
                    $user_image = $imagef['file_name'];

                }

                $cate = bannerCategory($input['banner_category']);

                $addUser = array(
                    'banner_image' => $user_image,
                    'banner_categoryid' => $input['banner_category'],
                    'banner_categoryname' => $cate,
                    'banner_url' => $input['url'],
                );
                $user = $this->Admin_model->adduser('banner', $addUser);
                if ($user) {
                    $this->session->set_flashdata('adduser', 'New user added successfully');
                    redirect('banners');
                }

            }
        } else {
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('addnewbanner');
            $this->load->view('include/footer');
        }
    }
    #__________________

    // This function is used to block the banner
    public function blockBanner()
    {
        $param = $_POST;
        $res = $this->Admin_model->blockUnblockBanner($param['bannerid'], $param['ustatus']);
        if ($res) {
            echo $param['ustatus'];
        }

    }
    //

    // This function is used to add this to isfeature
    public function is_featured()
    {
        $param = $_POST;
        $this->Admin_model->Is_feature($param['bannerid'], $param['ustatus'], $param['cate_id']);
        $this->Admin_model->deleteisfeature($param['cate_id'], $param['bannerid']);
        $banner_detail = $this->Admin_model->getBanner($param['bannerid']);
        echo json_encode($banner_detail);
    }
    //

    //This function is used to edit the banner
    public function edit_banner($banner_id = "")
    {
        $input = $this->input->post();
        // if form is submitted then this if condition will execute
        if ($input) {
            $this->form_validation->set_rules('banner_category', 'Banner Category', 'required');
            if ($this->form_validation->run() == false) {
                $this->form_validation->error_array();
                //$userdetail['userDetail']=$this->Admin_model->getUserData($id);
                $this->load->view('include/sidebar');
                $this->load->view('include/navbar');
                $this->load->view('editbanner');
                $this->load->view('include/footer');
            } else {

                if (!empty($_FILES['banner_image']['name'])) {
                    $picture = time() . '-' . 'quoteshare' . rand() . $_FILES["banner_image"]['name'];
                    $config['file_name'] = $picture;
                    $config['upload_path'] = './uploads/banner';
                    $config['allowed_types'] = 'gif|jpeg|png|jpg';
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('banner_image')) {
                        echo $error = array('error' => $this->upload->display_errors());die;
                    } else {
                        $datt = $this->upload->data();
                        $file_array = $this->upload->data('full_path');
                        $banner_image = $datt['file_name'];
                    }
                } else {
                    $banner_image = $input['bannerOldimage'];
                }
                $cate = bannerCategory($input['banner_category']);
                $updatebanner = array(
                    'banner_categoryid' => $input['banner_category'],
                    'banner_categoryname' => $cate,
                    'banner_image' => $banner_image,
                    'banner_url' => $input['url'],
                );

                //here we will update the banner with banner id
                $res = $this->Admin_model->updateBanner('banner_id', $banner_id, 'banner', $updatebanner);
                redirect('banners');

            }
        } else {
            $banner = array('banner_id' => $banner_id);
            $banners['banners'] = $this->Admin_model->NameById('banner', $banner);
            $this->load->view('include/sidebar');
            $this->load->view('include/navbar');
            $this->load->view('editbanner', $banners);
            $this->load->view('include/footer');
        }

    }
    //Banner End

    //This function is used to delete the banner
    public function deletebanner()
    {
        $param = $_POST;
        $res = $this->Admin_model->deleteBanner('banner_id', $param['bannerid'], 'banner');
        echo $res;
    }

}
