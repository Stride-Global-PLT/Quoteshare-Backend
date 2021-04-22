<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Followunfollow_model extends CI_Model {

    public function is_already_follow($user_id,$follower_id){
       $query=$this->db->get_where('followers',array('user_id'=>$user_id, 'follower_id'=> $follower_id));
        return $query->num_rows();
     
    }
    //this function is used to unfollow the user
    public function unfollow($user_id,$follower_id){
        $this->db->where('user_id',$user_id);
        $this->db->where('follower_id',$follower_id);
        $this->db->delete('followers');
        return 0;
        //return $this->db->affected_rows();
    }
    //this function used to follow the user
    public function follow($fields){
        $this->db->insert('followers',$fields);
        // return 1;
        return $this->db->affected_rows();
    }
        //this function used to count the total followers
        public function totalFollowers($user_id){
            $this->db->where('user_id',$user_id);
            $this->db->from("followers");
            return $this->db->count_all_results();
        }
        //This function used to count the total following
        public function totalFollowings($user_id){
            $this->db->where('follower_id',$user_id);
            $this->db->from("followers");
            return $this->db->count_all_results();
        }
        //This function is used to get the follower list
        public function get_followerslist($user_id){
            $this->db->select("followers.user_id,followers.follower_id,followers.created_at,users.full_name,users.user_name,users.picture");
            $this->db->from('followers');
            $this->db->order_by("full_name", "asc");
            $this->db->join('users','users.user_id = followers.follower_id');
            $this->db->where('followers.user_id',$user_id);
            $query = $this->db->get();
            return $query->result();

            // $data = $this->db->query(" Select followers.user_id,followers.follower_id,followers.created_at,users.full_name,users.picture,(select count(1) from followers where followers.follower_id ='".$user_id."' AND followers.user_id=followers.follower_id) as isfollow FROM followers Left join users on users.user_id = followers.follower_id where followers.user_id='".$user_id."'")->result();
            // return $data;
        }

      //This functoion is used to get the total following list
    public function get_FollowingList($user_id){
        $this->db->select("followers.user_id,followers.follower_id,followers.created_at,users.full_name,users.user_name,users.picture");
        $this->db->from('followers');
        $this->db->order_by("full_name", "asc");
        $this->db->join('users','users.user_id = followers.user_id');
        $this->db->where('followers.follower_id',$user_id);
        $followinglist=$this->db->get();
        return $followinglist->result();
    }
      //this function used to get user feed count
        public function totalFeeds($user_id="",$user_type=""){
            //print_r($user_type);die;
            //return $this->db->count_all('user_id')->get_where('folllowers',array('user_id',$user_id));
            //if()
    //$this->db->where('user_id',$user_id);
            if($user_type==1){
               $column='user_id'; 
            }
            elseif ($user_type==2) {
               $column='author_id';         
            }elseif($user_type==3){
                $column='booker_id';
            }elseif($user_type==4){
                $column='tagger_id';
            }
            else{
                return 0;
            }
            //print_r($column);die;
            $this->db->where('find_in_set("'.$user_id.'", '.$column.')');
            $this->db->where('parent_id',0);
            $this->db->where('status',1);
            $this->db->from("feeds");
            return $this->db->count_all_results();
        }
        //this function used to get the user detail with user_id
        public function getUserProfile($user_id){
            return $this->db->get_where('users',array('user_id'=>$user_id))->row();
            
        }
        #_______

        #____Get user type with id
        public function GetUserType($userid=""){
            return $this->db->query("select user_type from users where user_id='".$userid."'")->row_array()['user_type'];
        }
}
