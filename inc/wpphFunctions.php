<?php

//#! 2001 & 2005
function wpph_newPostPublished($userID, $postTitle, $postUrl, $event)
{
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.'() : Post/Page published.', array('title'=>$postTitle));
}

// 2003 & 2007
function wpph_draftPostUpdated($userID, $postID, $postTitle, $event)
{
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.'() : Draft post/page updated.', array('title'=>$postTitle));
}

// 2000 & 2004
function wpph_newPostAsDraft($userID, $postID, $postTitle, $event)
{
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$postID));
    wpphLog(__FUNCTION__.'() : New post/page saved as draft.', array('title'=>$postTitle));
}

// 2017 & 2018
function wpph_postUrlUpdated($oldUrl, $newUrl, $userID, $postTitle, $event)
{
    if($oldUrl == $newUrl) { return false; }

    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle, $oldUrl, $newUrl));
    wpphLog(__FUNCTION__.'() : Post/Page URL updated.', array('from' => $oldUrl,'to' => $newUrl));
    return true;
}

// 2002 & 2006
function wpph_publishedPostUpdated($userID, $postTitle, $postUrl, $event)
{
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$postUrl));
    wpphLog(__FUNCTION__.'() : Published post/page updated.', array('title'=>$postTitle));
}

function wpph_postVisibilityChanged($userID, $postTitle, $fromVisibility, $toVisibility, $event)
{
    wpphLog(__FUNCTION__.'() triggered.');
    wpphLog('Post visibility changed.', array('from' => $fromVisibility, 'to' => $toVisibility));
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$fromVisibility,$toVisibility));
}

function wpph_postDateChanged($userID, $postTitle, $fromDate, $toDate, $event)
{
    wpphLog(__FUNCTION__.'() triggered.');
    wpphLog('Post date changed.', array('from' => $fromDate . ' ('.strtotime($fromDate).')', 'to' => $toDate . ' ('.strtotime($toDate).')'));
    $GLOBALS['WPPH_POST_DATE_CHANGED'] = true; // so we won't trigger the "modified post/page" event alongside the current event
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$fromDate,$toDate));
}

function wpph_postStatusChanged($postTitle, $fromStatus, $toStatus, $userID, $event)
{
    WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle, $fromStatus, $toStatus));
    wpphLog(__FUNCTION__.'() : Post status updated.', array('title'=>$postTitle, 'from' => $fromStatus, 'to' => $toStatus));
}

// 2016
function wpph_postCategoriesUpdated($userID, $postTitle, $fromCategories, $toCategories)
{
    WPPHEvent::_addLogEvent(2016, $userID, WPPHUtil::getIP(), array($postTitle, $fromCategories, $toCategories));
    wpphLog(__FUNCTION__.' : Blog post categories updated.', array('from'=>$fromCategories, 'to'=>$toCategories));
}

// 2019 & 2020
function wpph_postAuthorChanged($newAuthorID, $postID, $userID, $postTitle, $event, $quickFormEnabled = false)
{
    global $wpdb;
    $oldAuthorID = $wpdb->get_var("SELECT post_author FROM ".$wpdb->posts." WHERE ID = ".$postID);

    wpphLog(__FUNCTION__.'() ',array(
        'oldAuthorID' => $oldAuthorID,
        'newAuthorID' => $newAuthorID
    ));

    if($newAuthorID <> $oldAuthorID){
        $n = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$newAuthorID);
        $o = $wpdb->get_var("SELECT user_login FROM ".$wpdb->users." WHERE ID = ".$oldAuthorID);

        if($quickFormEnabled){
            // in quick edit form the authors get switched whereas in the default post editor they don't :/
            $t = $n;
            $n = $o;
            $o = $t;
        }

        WPPHEvent::_addLogEvent($event, $userID, WPPHUtil::getIP(), array($postTitle,$n,$o));
        wpphLog(__FUNCTION__.' : Post/Page author updated.', array('from'=>$o, 'to'=>$n));
        return true;
    }
    return false;
}

// handle author change in quick edit form
function wpph_managePostAuthorUpdateQuickEditForm($data, $postArray)
{
    if($data['post_type'] == 'post'){
        if(wpph_postAuthorChanged($GLOBALS['WPPH_POST_AUTHOR_UPDATED_ID'], $postArray['ID'], wp_get_current_user()->ID, $data['post_title'], 2019, true)){
            $GLOBALS['WPPH_POST_AUTHOR_UPDATED'] = true;
        }
    }
    elseif($data['post_type'] == 'page'){
        if(wpph_postAuthorChanged($GLOBALS['WPPH_POST_AUTHOR_UPDATED_ID'], $postArray['ID'], wp_get_current_user()->ID, $data['post_title'], 2020, true)){
            $GLOBALS['WPPH_PAGE_AUTHOR_UPDATED'] = true;
        }
    }
    return $data;
}
