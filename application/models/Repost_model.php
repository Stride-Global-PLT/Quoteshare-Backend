<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Repost_model extends CI_Model {
    //This function is used to report against the feed
    public function  feedReport($fields){
        $this->db->insert('reports',$fields);
        // return 1;
        return $this->db->affected_rows();
    }
    //This function is used to search for you
    public function forYouSearch($match){
        if(empty($match)){
            return null;
        }
        else{
        $this->db->select('*');
        $this->db->like('users.full_name',$match);
        $this->db->or_like('users.user_name',$match);
        $query=$this->db->get_where('users',array('is_active'=>1));
        // $query = $this->db->get();
        //print_r($this->db->last_query()); exit;
        return $query->result();
        }
    }
  
    public function feed_detail($feed_id=""){
        return $this->db->get_where('feeds',array('feed_id'=>$feed_id))->row();
    }

    public function view_feeds_detail($userid,$feedid){
        $data = $this->db->query("Select feeds.*,users.full_name,users.picture,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id) as totalLikes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id) as RepostCount,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id AND feed_likes.user_id = '".$userid."') as isLiked,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id AND  resharingcount.user_id = '".$userid."' ) as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.parent_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id WHERE feeds.feed_id='".$feedid."'")->result();
        return $data;
    }

    //This function is used for insert the data into the database
    public function insert($table,$fields){
        $this->db->insert($table,$fields);
        return $this->db->insert_id();
    }
    //this function is used to check the report is already there or not
    public function is_repost($user_id,$realfeed_id){
        $repost=$this->db->get_where('feeds',array('user_id'=>$user_id, 'parent_id'=> $realfeed_id));
         return $repost->num_rows();
      
     }

     //This function is used to delete the repost data
     public function deleteRepost($user_id,$feed_id){
         $this->db->delete('feeds',array('user_id'=>$user_id,'parent_id'=>$feed_id));
        return $this->db->affected_rows();
        //return $this->db->affected_rows();
    }

    public function deleteResharingcount($user_id,$feed_id){
        $this->db->delete('resharingcount',array('user_id'=>$user_id,'feed_id'=>$feed_id));
       return $this->db->affected_rows();
       //return $this->db->affected_rows();
   }
   //This function is used to get the repost feed and its detail
   public function getMyRepostFeeds($userid="",$loginuser_id=""){
        $data=$this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_name,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id) as RepostCount,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id) as totalLikes,(select count(1) from feeds where feeds.parent_id>0 AND feeds.user_id = '".$userid."') as totalQuotes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.parent_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$userid."' AND feeds.parent_id>0 AND feeds.status=1 ORDER BY feeds.created_at desc")->result();
        return $data;
    }
    //This function is used to get the repost feed and its detail
   public function getMyRepostFeedsByPopularity($userid="",$loginuser_id=""){
    $data=$this->db->query(" Select feeds.*,users.full_name,users.picture,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id) as RepostCount,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id) as totalLikes,(select count(1) from feeds where feeds.parent_id>0 AND feeds.user_id = '".$userid."') as totalQuotes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.parent_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from resharingcount where resharingcount.feed_id = feeds.parent_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.parent_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$userid."' AND feeds.parent_id>0 AND feeds.status=1 ORDER BY totalLikes desc")->result();
    return $data;
}
    //This function is used to get the user detail
    public function getUserDetail($user_id=""){
        $this->db->select('user_id,full_name,user_name,user_type,picture');
        $this->db->from('users');
        $this->db->where(array('user_id'=>$user_id));
        return $this->db->get()->row_array();
        //return $query->row();
    }
#_____This function will  check the repost data whose userid =userid and parentid>0
    public function check_repost($user_id){
        $query=$this->db->get_where('feeds',array('user_id'=>$user_id, 'parent_id >'=>0));
        return $query->num_rows();
    
    }
    #_________

    public function isfeedthere($feed_id){
        $this->db->get_where('feeds',array('feed_id'=>$feed_id));
        return $this->db->affected_rows();
    }
    #____get user_id from feed
    public function getId($feedid=""){
        $id = $this->db
    -> select('user_id')
    -> where('feed_id', $feedid)
    -> get('feeds')
    -> row_array()['user_id'];
    return $id;
    }
}