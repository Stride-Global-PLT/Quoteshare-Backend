<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Admin_model extends CI_Model {
    //This function is used for login

    public function login($fields)
    {
            $logindata=$this->db->where($fields)->get('admin');
            if($logindata->num_rows()){
            return $logindata->row();
            }
            else
            {
            return null;
        }
    }
//this function display all the users from users table
public function users(){
    $userdata=$this->db->get_where('users',array('is_active'=>1));
    return $userdata->result();
    // $this->db->select();
    // $users=$this->db->get('users');
    // return $users->result();
}
//This function will get the userdata
public function getUserData($user_id){
    $userdata=$this->db->get_where('users',array('user_id'=>$user_id));
    return $userdata->row();
}
//This function is used to update the userinformation
public function updateUser($user_id,$data){
    $this->db->where('user_id', $user_id);
    return $this->db->update('users',$data);

}
//this function is used to insert the data into the table
public function adduser($table,$fields){
    $this->db->insert($table,$fields);
    return $this->db->insert_id();
}

//this function block the user
public function blockuser($user_id,$data){

        $this->db->where('user_id', $user_id);
        $this->db->set('is_active',$data);
        $this->db->set('session_key'," ");
        $this->db->update('users');
        return $data;

}
//This function used to get the profile detail of admin
public function admin_profile($admin_id){
    $userdata=$this->db->get_where('admin',array('admin_id'=>$admin_id));
    return $userdata->row();
}
//this function is used to change the profile picture
public function updateProfilePicture($admin_id,$data){
    $this->db->where('admin_id', $admin_id);
        $this->db->set('picture',$data);
        $this->db->update('admin');

}
public function updateProfileDetail($admin_id,$data){
    $this->db->where('admin_id', $admin_id);
    $this->db->update('admin',$data);
    $userdata=$this->db->get_where('admin',array('admin_id'=>$admin_id));
    return $userdata->row();
}
public function tags(){
    $this->db->select();
    $tags=$this->db->get('tags');
    return $tags->result();
}
public function blockTag($tag_id,$data){

    $this->db->where('tag_id', $tag_id);
    $this->db->set('is_active',$data);
    $this->db->update('tags');
    return $data;

}

#__________GET NORMAL USER
public function NormalUser($user_type=""){
    $users=$this->db->query("select user_id,social_id,user_type,full_name,bio,user_name,email,login_type,device_type,is_active,picture,created_at,updated_at from users where user_type='".$user_type."'  ORDER BY users.created_at desc")->result();
    return $users;
}
#____________END 

#__________GET NORMAL USER
public function Quotes(){
    // $quotes=$this->db->query("select * from users where status=1 ORDER BY users.created_at desc")->result();
    // return $quotes;


     $data = $this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select user_name from users where user_id= feeds.author_id) as author_name,(select user_name from users where user_id= feeds.booker_id) as booker_name,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id and feed_comments.status=1 ) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.parent_id =0  ORDER BY feeds.created_at desc")->result();
     return $data;
}
#____________END 


#_________Get category 
public function NameById($table,$data){
  $userdata=$this->db->get_where($table,$data);
  
  return $userdata->row();

}



//this function block the user
public function blockFeed($table="",$feed_id="",$data=""){
        $this->db->where('feed_id', $feed_id);
        $this->db->or_where('parent_id',$feed_id);
        $this->db->set('status',$data);
        $this->db->update($table);
        return $data;
}

//this function block the user
public function commentblock($comment_id="",$data=""){
        $this->db->where('comment_id', $comment_id);
        $this->db->set('status',$data);
        $this->db->update('feed_comments');
        }
//END

 #_______COUNT COMMENT FUNCTION 
public function countcomments($feed_id=""){
    $this->db->select('comment_id');
    $this->db->from('feed_comments');
    $this->db->where('feed_id',$feed_id);
    $this->db->where('status',1);

    return $num_results = $this->db->count_all_results();
}     


//Show all comment of the user
 public function Comemnts($feed_id=""){
        $this->db->select("feed_comments.comment_id,feed_comments.feed_id,feed_comments.user_id,feed_comments.comment,feed_comments.status,feed_comments.created_at,feed_comments.updated_at,users.full_name,users.picture");
        $this->db->from('feed_comments');
        $this->db->join('users', 'users.user_id = feed_comments.user_id');
        $this->db->where('feed_id', $feed_id);
        $query = $this->db->get();
        return $query->result();
    }
    //END FUNCTION 


    //THIS FUNCTION IS USED TO SHOW THE ALL REPORT
    public function Reports(){
        //$this->db->select("r.*,f.*,u.full_name,u.picture");
        // $this->db->select("*,select full_name from users where users.user_id=f.author_id as author_name");
        // $this->db->from('reports r');
        // $this->db->join('feeds f','f.feed_id = r.feed_id');
        // $this->db->join('users u' , 'f.user_id = u.user_id');
        // $query = $this->db->get();
        //  return  $query->result();
        // //return $this->db->last_query();
        return $this->db->query("SELECT r.* ,u.*, f.* ,(SELECT full_name from users WHERE user_id=f.author_id)as author_name,(SELECT full_name from users WHERE user_id=f.booker_id)as booker_name,(SELECT full_name from users WHERE user_id=r.user_id)as report_by FROM `reports` r LEFT JOIN `feeds` f ON f.feed_id=r.feed_id LEFT JOIN `users` u ON u.user_id=f.user_id ORDER BY r.report_on desc")->result();
}
    #________________
#________get all data from the table

    public function getAllData($table=""){
    // $userdata=$this->db->get_where($table,array($condtion));
    // return $userdata->result(); 
    return $this->db->get($table)->result();

    }
   
   ///Block banner
    public function blockUnblockBanner($banner_id="",$data=""){
        $this->db->where('banner_id', $banner_id);
        $this->db->set('status',$data);
        $this->db->update('banner');
        return $this->db->affected_rows();
    }
    // 

    //This function is used to do the isfeature
    public function Is_feature($banner_id="",$data=""){
        $this->db->where('banner_id', $banner_id);
        $this->db->set('is_feature',$data);
        $this->db->update('banner');
        return  $this->db->affected_rows();
    }
//
   public  function deleteisfeature($cate_id,$banner_id=""){
        $this->db->where('banner_categoryid',$cate_id);
        $this->db->where('banner_id !=',$banner_id);
        $this->db->set('is_feature',0);
        $this->db->update('banner');
   } 

   //get the banner for edit
   public function getBanner($banner_id=""){
    $this->db->select('*');
    $this->db->from('banner');
    $this->db->where('banner_id',$banner_id);
    return $this->db->get()->row();
   }

   //update banner
   public function updateBanner($condition="",$condtionValue="",$table="",$data){
        $this->db->where($condition, $condtionValue);
        $this->db->update($table,$data);
   }
   // 

   // This function is used to delete the banner
   public function deleteBanner($condition,$condtionValue,$table){
     $this->db->where($condition, $condtionValue);
     $this->db->delete($table);
    return $this->db->affected_rows();
   }
       
}