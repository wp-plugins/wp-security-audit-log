<?php
// #!-- BEGIN PAGE
//======================================================================================================================

// 2018
function wpph_pageUrlUpdated($oldUrl, $newUrl, $userID, $postTitle)
{
    if($oldUrl == $newUrl) { return false; }

    WPPHEvent::_addLogEvent(2018, $userID, WPPHUtil::getIP(), array($postTitle, $oldUrl, $newUrl));
    wpphLog('Page URL updated.', array('from' => $oldUrl,'to' => $newUrl));
    return true;
}

// 2020
function wpph_pageAuthorUpdated($newAuthorID, $postID, $userID, $postTitle, $quickFormEnabled = false)
{
    global $wpdb;
    $oldAuthorID = $wpdb->get_var("SELECT post_author FROM ".$wpdb->posts." WHERE ID = ".$postID);
    if($newAuthorID <> $oldAuthorID){
        $n = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$newAuthorID);
        $o = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$oldAuthorID);

        if($quickFormEnabled){
            // in quick edit form the authors get switched whereas in the default post editor they don't :/
            $t = $n;
            $n = $o;
            $o = $t;
        }

        WPPHEvent::_addLogEvent(2020, $userID, WPPHUtil::getIP(), array($postTitle,$n,$o));
        wpphLog(__FUNCTION__.' : Page author updated.', array('from'=>$o, 'to'=>$n));
        return true;
    }
    return false;
}

// 2004
function wpph_newPageAsDraft($userID, $postID, $postTitle)
{
    WPPHEvent::_addLogEvent(2004, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.' : New page saved as draft.', array('title'=>$postTitle));
}

// 2005
function wpph_newPagePublished($userID, $postTitle, $postUrl)
{
    WPPHEvent::_addLogEvent(2005, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.' : Page published.', array('title'=>$postTitle));
}

// 2007
function wpph_draftPageUpdated($userID, $postID, $postTitle)
{
    WPPHEvent::_addLogEvent(2007, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.' : Draft page updated.', array('title'=>$postTitle));
}

// 2006
function wpph_publishedPageUpdated($userID, $postTitle, $postUrl)
{
    WPPHEvent::_addLogEvent(2006, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.' : Published page updated.', array('title'=>$postTitle));
}

// 2022
function wpph_pageStatusUpdated($fromStatus, $toStatus, $userID, $postTitle)
{
    WPPHEvent::_addLogEvent(2022, $userID, WPPHUtil::getIP(), array($postTitle, $fromStatus, $toStatus));
    wpphLog(__FUNCTION__.' : Page status updated.', array('title'=>$postTitle, 'from' => $fromStatus, 'to' => $toStatus));
}

// #!-- END PAGE
//======================================================================================================================


// #!-- BEGIN POSTS
//======================================================================================================================

// 2017
function wpph_postUrlUpdated($oldUrl, $newUrl, $userID, $postTitle)
{
    if($oldUrl == $newUrl) { return false; }

    WPPHEvent::_addLogEvent(2017, $userID, WPPHUtil::getIP(), array($postTitle, $oldUrl, $newUrl));
    wpphLog(__FUNCTION__.' : Blog post URL updated.', array('from' => $oldUrl,'to' => $newUrl));
    return true;
}

// 2019
function wpph_postAuthorUpdated($newAuthorID, $postID, $userID, $postTitle, $quickFormEnabled = false)
{
    global $wpdb;
    $oldAuthorID = $wpdb->get_var("SELECT post_author FROM ".$wpdb->posts." WHERE ID = ".$postID);
    if($newAuthorID <> $oldAuthorID){
        $n = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$newAuthorID);
        $o = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$oldAuthorID);

        if($quickFormEnabled){
            // in quick edit form the authors get switched whereas in the default post editor they don't :/
            $t = $n;
            $n = $o;
            $o = $t;
        }

        WPPHEvent::_addLogEvent(2019, $userID, WPPHUtil::getIP(), array($postTitle,$n,$o));
        wpphLog(__FUNCTION__.' : Blog post author updated.', array('from'=>$o, 'to'=>$n));
        return true;
    }
    return false;
}

// 2016
function wpph_postCategoriesUpdated($userID, $postTitle, $fromCategories, $toCategories)
{
    WPPHEvent::_addLogEvent(2016, $userID, WPPHUtil::getIP(), array($postTitle, $fromCategories, $toCategories));
    wpphLog(__FUNCTION__.' : Blog post categories updated.', array('from'=>$fromCategories, 'to'=>$toCategories));
}

// 2000
function wpph_newPostAsDraft($userID, $postID, $postTitle)
{
    WPPHEvent::_addLogEvent(2000, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.' : New blog post saved as draft.', array('title'=>$postTitle));
}

// 2003
function wpph_draftPostUpdated($userID, $postID, $postTitle)
{
    WPPHEvent::_addLogEvent(2003, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.' : Draft blog post updated.', array('title'=>$postTitle));
}

// 2001
function wpph_newPostPublished($userID, $postTitle, $postUrl)
{
    WPPHEvent::_addLogEvent(2001, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.' : Blog post published.', array('title'=>$postTitle));
}

// 2002
function wpph_publishedPostUpdated($userID, $postTitle, $postUrl)
{
    WPPHEvent::_addLogEvent(2002, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.' : Published blog post updated.', array('title'=>$postTitle));
}

// 2021
function wpph_postStatusUpdated($fromStatus, $toStatus, $userID, $postTitle)
{
    WPPHEvent::_addLogEvent(2021, $userID, WPPHUtil::getIP(), array($postTitle, $fromStatus, $toStatus));
    wpphLog(__FUNCTION__.' : Post status updated.', array('title'=>$postTitle, 'from' => $fromStatus, 'to' => $toStatus));
}

// #!-- END POSTS
//======================================================================================================================

// handle author change in quick edit form
function wpph_managePostAuthorUpdateQuickEditForm($data, $postArray)
{
    if($data['post_type'] == 'post'){
        wpph_postAuthorUpdated($GLOBALS['WPPH_POST_AUTHOR_UPDATED'], $postArray['ID'], wp_get_current_user()->ID, $data['post_title'], true);
    }
    elseif($data['post_type'] == 'page'){
        wpph_pageAuthorUpdated($GLOBALS['WPPH_POST_AUTHOR_UPDATED'], $postArray['ID'], wp_get_current_user()->ID, $data['post_title'], true);
    }
    return $data;
}