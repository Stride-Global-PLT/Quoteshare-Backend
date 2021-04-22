<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Feed_model extends CI_Model {
    //this function will give the information about total like and comments
    public function view_feeds_detail($userid,$feedid){
        $data = $this->db->query("Select feeds.*,users.full_name,users.picture,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$userid."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id WHERE feeds.feed_id='".$feedid."' ORDER BY feeds.created_at desc")->row_array();
        return $data;
    }
    //This function will check the user id and feed id is exist in like table then it will give response like 1
    public function is_already_liked($user_id,$feed_id){
           $this->db->get_where('feed_likes',array('feed_id'=>$feed_id, 'user_id'=> $user_id));
            return $this->db->affected_rows();
    }
    //This function delete the like
    public function delete_LikeUnlike($table,$feed_id,$user_id){
        $this->db->where('user_id',$user_id);
        $this->db->where('feed_id',$feed_id);
        $this->db->delete($table);
        return 0;
        //return $this->db->affected_rows();
      }
      //this function will do the like on post
      public function DoLike($table,$fields){
        $this->db->insert($table,$fields);
        return 1;
    }
    //This function will post the comment
    public function CommentOnFeed($table,$fields){

        return $this->db->insert($table,$fields);
    }
    //this function fill will get the like with user name and picture
    public function get_like($feed_id){
        $this->db->select("feed_likes.feed_id,feed_likes.user_id,users.full_name,users.picture,users.created_at,users.updated_at");
        $this->db->from('feed_likes');
        $this->db->join('users', 'users.user_id = feed_likes.user_id');
        $this->db->where('feed_id', $feed_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_comments($feed_id){
        $this->db->select("feed_comments.comment_id,feed_comments.feed_id,feed_comments.user_id,feed_comments.comment,feed_comments.created_at,feed_comments.updated_at,users.full_name,users.picture");
        $this->db->from('feed_comments');
        $this->db->join('users', 'users.user_id = feed_comments.user_id');
        $this->db->where('feed_id', $feed_id);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function DeletefeedComment($table,$comment_id,$feed_id,$user_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->where('feed_id',$feed_id);
        $this->db->where('user_id',$user_id);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    public function EditFeed($feed_id,$user_id,$data){
        $where = array('feed_id' => $feed_id , 'user_id ' => $user_id);
        $this->db->where($where);
         $this->db->update('feeds',$data);
         return $this->db->affected_rows();
    }

    // public function user_like($id,$feed_id)
    // {
    //     $this->db->where('user_id',$id);
    //     $this->db->where('feed_id',$feed_id);
    //     $query = $this->db->get('feed_likes');
    //     if ($query->num_rows() > 0){
    //         return true;
    //     }
    //     else{
    //         return false;
    //     }
    // }
    
    public function IsEmail_alreadyExist($email){
        $this->db->get_where('users',array('email'=>$email));
         return $this->db->affected_rows();
    }
   
 public function ViewAllFeeds($userid=""){
       $data = $this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$userid."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$userid."')as isfollowing,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$userid."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.parent_id =0 AND users.is_active=1 ORDER BY feeds.created_at desc")->result();
        return $data;
    }
     //This function is used to get all feeds of the specific user by "user_id"
    public function getMyFeed($userid="",$user_type="",$loginuser_id=""){
    if($user_type==1){
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.user_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$userid."' And feeds.parent_id=0 ORDER BY feeds.created_at desc")->result();
        return $data;
    }elseif ($user_type==2) {
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.author_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id =feeds.user_id) as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where feeds.author_id='".$userid."' And feeds.parent_id=0 ORDER BY feeds.created_at desc")->result();
        return $data;
    }elseif ($user_type==3) {
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.booker_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id =feeds.user_id) as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where feeds.booker_id='".$userid."' And feeds.parent_id=0 ORDER BY feeds.created_at desc")->result();
           return $data;

    }else{
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where(find_in_set('".$userid."',feeds.tagger_id)) AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id =feeds.user_id) as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where (find_in_set('".$userid."',feeds.tagger_id)) And feeds.parent_id=0 ORDER BY feeds.created_at desc")->result();
           return $data;

    }

    }

    public function getFeeds(){
        $category=$this->db->get('feeds');
        return $category->result();
    }
    //this function is used to get the list of all author
    public function getAuthor(){
        $authors=$this->db->get('author');
        return $authors->result();
    }
    //This function is used to get the  list of all books
    public function getBook(){
        $book=$this->db->get('book');
        return $book->result();
    }
    //this function is used to get the tags
    public function getTags(){
        $Tags=$this->db->get_where('tags',array('is_active'=>1));
        return $Tags->result();
    }

    //this function get teh Author name
    public function getAuthorsName($keyword=""){
        if($keyword){
            $this->db->like('full_name',$keyword);
            $authors=$this->db->get_where('users',array('user_type'=>2,'is_active'=>1));
            return $authors->result();
        }
            else{
            $authors=$this->db->get_where('users',array('user_type'=>2,'is_active'=>1));
            return $authors->result();
            }
     }
    //this function get the bookname
    public function getBooksName($keyword=""){
        if($keyword){
            $this->db->like('full_name',$keyword);
            $book=$this->db->get_where('users',array('user_type'=>3,'is_active'=>1));
            return $book->result();
        }
        else{
            $book=$this->db->get_where('users',array('user_type'=>3,'is_active'=>1));
            return $book->result();
        }
    }

    //this function get the tagname
    public function getTagName($keyword=""){
        if($keyword){
            $this->db->like('full_name',$keyword);
            $Tags=$this->db->get_where('users',array('user_type'=>4,'is_active'=>1));
            return $Tags->result();
        }
            else{
            $Tags=$this->db->get_where('users',array('user_type'=>4,'is_active'=>1));
            return $Tags->result();
            }
    }

    //this is the common function to get the user
    public function FeedByCategory($category_id="",$loginuser_id="",$sort=""){
        if($sort==1){
        //print_r($category_id);exit;
        //$userid=1;
        // $query = $this->db
        // ->select('*')
        // ->from("feeds")
        // ->where('find_in_set("'.$category_id.'", category_id)')
        // ->where('parent_id',0)
        // ->get()->result();
        // return $query;
        $data = $this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where FIND_IN_SET('".$category_id."',feeds.category_id) AND feeds.parent_id =0 AND users.is_active=1 ORDER BY feeds.created_at desc")->result();
        return $data;
        }elseif($sort==2) {

         $data = $this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.parent_id =0 AND users.is_active=1 ORDER BY feeds.created_at asc")->result();
                return $data;        
        }elseif ($sort==3) {
               $data = $this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.parent_id =0 AND users.is_active=1 ORDER BY totalLikes desc,feeds.created_at desc")->result();
                return $data; 
            }else{
                 $data = $this->db->query(" Select feeds.*,users.full_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from followers where followers.user_id=feeds.user_id AND follower_id='".$loginuser_id."')as isfollowing,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.parent_id =0 AND users.is_active=1 ORDER BY feeds.created_at desc")->result();
                return $data;        
            }
    }
//this function is used to get the feed by popularity by user_id
    public function getMyFeedByPopularity($userid="",$user_type="",$loginuser_id=""){
        // $data=$this->db->query(" Select feeds.*,users.full_name,users.picture,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.user_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$userid."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$userid."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$userid."' And feeds.parent_id=0 ORDER BY totalLikes desc")->result();
        // return $data;
        if($user_type==1){
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.user_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$loginuser_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$userid."' And feeds.parent_id=0 ORDER BY totalLikes desc")->result();
        return $data;
    }elseif ($user_type==2) {
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.author_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id ='".$loginuser_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where feeds.author_id='".$userid."' And feeds.parent_id=0 ORDER BY totalLikes desc")->result();
        return $data;
    }elseif ($user_type==3) {
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.booker_id = '".$userid."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id ='".$loginuser_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where feeds.booker_id='".$userid."' And feeds.parent_id=0 ORDER BY totalLikes desc")->result();
           return $data;

    }else{
        $data=$this->db->query(" Select feeds.*,users.full_name,users.user_name,users.picture,users.user_type,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where(find_in_set('".$userid."',feeds.tagger_id)) AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id ='".$loginuser_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$loginuser_id."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments,(select full_name from users where users.user_id=feeds.author_id)as author_name,(select full_name from users where user_id=feeds.booker_id)as book_name FROM feeds Left join users on users.user_id = feeds.user_id where (find_in_set('".$userid."',feeds.tagger_id)) And feeds.parent_id=0 ORDER BY totalLikes desc")->result();
           return $data;

    }

    }

    //this function is used for search the  tag by keyword
    public function SearchTagByKeyword($keyword){
        // $this->db->like('full_name',$keyword);
        // $Tags=$this->db->get_where('users',array('user_type'=>4));
        // return $Tags->result();
        if(empty($keyword))
        {
            return null;
        }
        else{

            $this->db->like('full_name',$keyword);
            $Tags=$this->db->get_where('users',array('user_type'=>4,'is_active'=>1));
            return $Tags->result();
        }
    }

    public function updateRepost($feed_id,$data){
        $where = array('parent_id' => $feed_id);
        $this->db->where($where);
         $this->db->update('feeds',$data);
         return $this->db->affected_rows();
    }

         //This function is used to check the feed is already there or not
         public function isfeedthere($feed_id){
            $query=$this->db->get_where('feeds',array('feed_id'=>$feed_id,'status'=>1));
            return $query->num_rows();
        }


        //this function used to get the feeds of another user without reported feeds
        public function fetchUserFeeds($user_id,$follower_id){
            $data=$this->db->query("Select feeds.*,users.full_name,users.picture,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id) as totalLikes,(select count(1) from feeds where feeds.user_id = '".$user_id."' AND feeds.parent_id=0) as totalQuotes,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id) as RepostCount,(select count(1) from resharingcount where resharingcount.feed_id = feeds.feed_id AND  resharingcount.user_id = '".$follower_id."') as isRepost,(select count(1) from feed_likes where feed_likes.feed_id = feeds.feed_id AND feed_likes.user_id = '".$follower_id."') as isLiked,(select count(1) from feed_comments where feed_comments.feed_id = feeds.feed_id) as totalComments FROM feeds Left join users on users.user_id = feeds.user_id where feeds.user_id='".$user_id."' And feeds.parent_id=0 AND feeds.feed_id NOT IN (select feed_id from reports where user_id='".$follower_id."') ORDER BY feeds.created_at desc")->result();
            return $data;
        }
         //common function to get the name and id of the user
        public function UserNameId($ids=""){
        $this->db->select('users.user_name,users.full_name,users.user_id,users.user_type');
        $this->db->from('users');
        $this->db->where_in('user_id',$ids);
        return $this->db->get()->result_array();
        }
        #___________End query
        
         //check and Add new tagUser
        public function addnewTagger($keyword){
            $is_exist=$this->db->get_where('users',array('full_name' => $keyword,'user_type'=>4))->row();
            if($is_exist){
                return $is_exist->user_id;
            }else{
                $enter=array('full_name'=>$keyword,'user_type'=>4);
                $this->db->insert('users',$enter);
                return $this->db->insert_id();
            }
        }
        #________________
                #_______Check and add author
        public function addAuthor($author=""){
            $is_exist=$this->db->get_where('users',array('full_name' => $author,'user_type'=>2))->row();
            if($is_exist){
                return $is_exist->user_id;
            }else{
                $enter=array('full_name'=>$author,'user_type'=>2);
                $this->db->insert('users',$enter);
                return $this->db->insert_id();
            } 
        }
        #________

        #_______Check and add Book
        public function addBook($book=""){
            $is_exist=$this->db->get_where('users',array('full_name' => $book,'user_type'=>3))->row();
            if($is_exist){
                return $is_exist->user_id;
            }else{
                $enter=array('full_name'=>$book,'user_type'=>3);
                $this->db->insert('users',$enter);
                return $this->db->insert_id();
            } 
        }
        #________

        
}
