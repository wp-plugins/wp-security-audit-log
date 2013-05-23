<?php
/**
 * @kyos
 * Class WPPHUtil
 * Contains utility methods
 */
class WPPHUtil
{
    public static function getIP()
    {
        if ($_SERVER['HTTP_CLIENT_IP']) $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR']) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED']) $ip = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR']) $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED']) $ip = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR']) $ip = $_SERVER['REMOTE_ADDR'];
        else $ip = '0.0.0.0';
        return $ip;
    }

/*
 * Will respond to the ajax requests getting the events
 */
    public static function get_events_html()
    {
        //#! VALIDATE REQUEST
        $rm = strtoupper($_SERVER['REQUEST_METHOD']);
        if($rm != 'POST'){
            exit('<tr><td colspan="7"><span>'.__('Error: Invalid request').'</span></td></tr>');
        }

        // set defaults
        $orderBy = 'EventNumber';
        $sort = 'desc';
        $limit = array(0, 50);
        $orderDescending = true;
        $pageNumber = 0;

        if(!empty($_POST['orderBy'])) { $orderBy = $_POST['orderBy']; }
        if(!empty($_POST['sort'])) {
            if(0 == strcasecmp($_POST['sort'],'asc')){
                $sort = 'asc';
                $orderDescending = false;
            }
        }
        if(isset($_POST['offset'])) { $limit[0] = intval($_POST['offset']); }
        if(isset($_POST['count'])) { $limit[1] = intval($_POST['count']); }
        if(isset($_POST['pageNumber'])) { $pageNumber = intval($_POST['pageNumber']); }

        function __formatJsonOutput(array $sourceData=array(), $error=''){
            return json_encode(array(
                'dataSource' => $sourceData,
                'error' => $error
            ));
        };

        // get events
        $events = WPPHEvent::getEvents($orderBy, $sort, $limit);
        $eventsNum = count($events);
        $allEventsCount = WPPHDB::getEventsCount();

        if($eventsNum == 0){
            exit(__formatJsonOutput(array(),__('There are no events to display.')));
        }

        $out = array();
        $out['events'] = array();

        //#! prepare output
        foreach($events as $entry)
        {
            $entry = (object)$entry;
            $eventNumber = $entry->EventNumber;
            $EventID = $entry->EventID;
            $EventDate = $entry->EventDate;
            $userIP = $entry->UserIP;
            $UserID = $entry->UserID;
            $eventData = unserialize($entry->EventData); //<< values to use for event description

            // get User Info
            if($UserID == 0){ $username = 'System'; }
            else {
                $user_info = get_userdata($UserID);
                $username = $user_info->user_login;
                $first_name = $user_info-> user_firstname;
                $last_name = $user_info-> user_lastname;
                $username = "$username ($first_name $last_name)";
            }

            // get event details
            $eventDetails = WPPHEvent::getEventDetailsData($EventID);

            // format event description message
            if(empty($eventData)) { $evm = $eventDetails->EventDescription; }
            else { $evm = vsprintf($eventDetails->EventDescription, $eventData); }

            $e = array(
                'eventNumber' => $eventNumber,
                'eventId' => $EventID,
                'EventType' => $eventDetails->EventType,
                'eventDate' => $EventDate,
                'ip' => $userIP,
                'user' => $username,
                'description' => $evm
            );
            array_push($out['events'], $e);
        }
        $out['eventsCount'] = $allEventsCount;

        exit(__formatJsonOutput($out,''));
    }

}