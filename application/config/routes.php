<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'adminpanel';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['sign_up'] = 'quoteshare_api/quoteshare/signup';
$route['login'] = 'quoteshare_api/quoteshare/login';
$route['forgot_password'] = 'quoteshare_api/quoteshare/forgot_pass';
$route['reset_password'] = 'quoteshare_api/quoteshare/reset_password';
$route['check'] = 'quoteshare_api/quoteshare/check';
$route['image_upload']['post'] = 'quoteshare_api/quoteshare/image_upload';
$route['user_profile']['post'] = 'quoteshare_api/quoteshare/UserProfilePictureUpload';
$route['categories']['post'] = 'quoteshare_api/quoteshare/categories';
$route['create_feed']['post'] = 'quoteshare_api/quoteshare/create';
$route['delete_feed']['post'] = 'quoteshare_api/quoteshare/delete';
$route['social_login']['post'] = 'quoteshare_api/quoteshare/social_login';
$route['checkusername']['post'] = 'quoteshare_api/quoteshare/checkUserNameValidation';
$route['logout']['post'] = 'quoteshare_api/quoteshare/logout';
$route['checkuser']['post'] = 'quoteshare_api/quoteshare/checkuser';
$route['editprofile']['post'] = 'quoteshare_api/quoteshare/editprofile';
$route['passwordChange']['post'] = 'quoteshare_api/quoteshare/passwordChange';
$route['verifyEmail']['post'] = 'quoteshare_api/quoteshare/verifyEmail';
$route['MatchEmailCode']['post'] = 'quoteshare_api/quoteshare/MatchEmailCode';
//feeds_api controller

$route['view_feeds_detail']= 'quoteshare_api/feeds_api/view_feeds_detail';
$route['LikeUnlikeFeed']= 'quoteshare_api/feeds_api/LikeUnlikeFeed';
$route['doComment']= 'quoteshare_api/feeds_api/CommentOnFeed';
$route['getlikes']= 'quoteshare_api/feeds_api/GetLikes';
$route['GetComments']= 'quoteshare_api/feeds_api/GetComments';
$route['deletecomment']= 'quoteshare_api/feeds_api/DeletefeedComment';
$route['EditFeed']= 'quoteshare_api/feeds_api/EditFeed';
$route['getFeeds']= 'quoteshare_api/feeds_api/getFeeds';
$route['ViewAllFeeds']= 'quoteshare_api/feeds_api/ViewAllFeeds';
$route['oneuserfeeds']= 'quoteshare_api/feeds_api/getOneUserFeeds';
$route['getMyFeed']= 'quoteshare_api/feeds_api/getMyFeed';
$route['GetAuthor']= 'quoteshare_api/feeds_api/getAuthor';
$route['GetBook']= 'quoteshare_api/feeds_api/getBook';
$route['GetTags']= 'quoteshare_api/feeds_api/getTags';
$route['GetAuthorName']= 'quoteshare_api/feeds_api/getAuthorsName';
$route['GetTagsName']= 'quoteshare_api/feeds_api/getTagsName';
$route['getBooksName']= 'quoteshare_api/feeds_api/getBooksName';
$route['FeedByCategory']= 'quoteshare_api/feeds_api/FeedByCategory';
$route['popularitySort']= 'quoteshare_api/feeds_api/getMyFeedByPopularity';
$route['searchTag']= 'quoteshare_api/feeds_api/SearchTagByKeyword';
$route['fetchUserFeeds']= 'quoteshare_api/feeds_api/fetchUserFeeds';
$route['addnewTagger']= 'quoteshare_api/feeds_api/addnewTagger';
$route['addNewAuthor']= 'quoteshare_api/feeds_api/addNewAuthor';
$route['addNewBook']= 'quoteshare_api/feeds_api/addNewBook';
$route['ShowBannerById']= 'quoteshare_api/feeds_api/ShowBannerById';

//followunfollow controller
$route['FollowUnfollow']= 'quoteshare_api/Followunfollow/FollowUnfollow';
$route['totalFollowers']= 'quoteshare_api/Followunfollow/totalFollowers';
$route['totalFollowings']= 'quoteshare_api/Followunfollow/totalFollowings';
$route['followerlist']= 'quoteshare_api/Followunfollow/get_followerslist';
$route['profiledetail']= 'quoteshare_api/Followunfollow/getprofiledetail';
$route['CountProfileData']= 'quoteshare_api/Followunfollow/CountProfileData';
$route['getUserProfile']= 'quoteshare_api/Followunfollow/getUserProfile';
$route['followinglist']= 'quoteshare_api/Followunfollow/getFollowingList';




$route['dashboard']= 'adminpanel/dashboard';
$route['adminlogin']= 'adminpanel/login';
$route['users']= 'adminpanel/users';
$route['adminlogout']= 'adminpanel/logout';
$route['edituser/(:any)']= 'adminpanel/edituser/$1';
$route['adduser']= 'adminpanel/addNewUser';
$route['blockuser']= 'adminpanel/blockuser';
$route['adminprofile']= 'adminpanel/admin_profile';
$route['updatepicture']= 'adminpanel/updateProfilePicture';
$route['AddTag']= 'adminpanel/addTag';
$route['tags']= 'adminpanel/tags';
$route['blocktag']= 'adminpanel/blockTag';
$route['Normalusers']= 'adminpanel/Normalusers';
$route['Authors']= 'adminpanel/Authors';
$route['books']= 'adminpanel/Books';

$route['quotes']= 'adminpanel/Quotes';
$route['blockfeed']= 'adminpanel/blockfeed';

$route['showcomments']= 'adminpanel/ShowComments';

$route['commentblock']= 'adminpanel/CommentBlock';
$route['countcomments']= 'adminpanel/countcomments';

$route['allcomment/(:any)']= 'adminpanel/allcomment/$1';
$route['reports']= 'adminpanel/reports';

$route['banners']= 'adminpanel/Banners';
$route['AddnewBanner']= 'adminpanel/AddnewBanner';
$route['blockBanner']= 'adminpanel/blockBanner';
$route['is_featured']= 'adminpanel/is_featured';
$route['edit_banner/(:any)']= 'adminpanel/edit_banner/$1';



//repost controller
$route['feedReport']= 'quoteshare_api/repost/feedReport';
$route['foryou']= 'quoteshare_api/repost/forYouSearch';
$route['repost']= 'quoteshare_api/repost/repost';
$route['getRepostFeeds']= 'quoteshare_api/repost/getRepostFeeds';
$route['repostFeedByPopularity']= 'quoteshare_api/repost/getRepostFeedsBYPopularity';
