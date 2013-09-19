<?php
class WPPHUtil
{
    public static function loadPluggable(){
        if(! function_exists('user_can')){
            @include_once(ABSPATH.'wp-includes/pluggable.php');
        }
    }

    public static function getIP() { return(!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'); }

    /**
     * Check to see whether or not the current user is an administrator
     * @return bool
     */
    public static function isAdministrator(){ return user_can(wp_get_current_user(),'update_core'); }

/*
 * Will respond to the ajax requests getting the events
 */
    public static function get_events_html()
    {
        //#! VALIDATE REQUEST
        $rm = strtoupper($_SERVER['REQUEST_METHOD']);
        if($rm != 'POST'){ exit('<tr><td colspan="7"><span>'.__('Error: Invalid request').'</span></td></tr>'); }

        // set defaults
        $orderBy = 'EventNumber';
        $sort = 'desc';
        $limit = array(0, 50);

        if(!empty($_POST['orderBy'])) { $orderBy = $_POST['orderBy']; }
        if(!empty($_POST['sort'])) {
            if(0 == strcasecmp($_POST['sort'],'asc')){ $sort = 'asc'; }
        }
        if(isset($_POST['offset'])) { $limit[0] = intval($_POST['offset']); }
        if(isset($_POST['count'])) { $limit[1] = intval($_POST['count']); }

        function __formatJsonOutput(array $sourceData=array(), $error=''){
            return json_encode(array(
                'dataSource' => $sourceData,
                'error' => $error
            ));
        };

        // get events
        $eventsCount = WPPHDB::getEventsCount();
        $events = WPPHEvent::getEvents($orderBy, $sort, $limit);

        $eventsNum = count($events);

        if($eventsNum == 0){
            exit( __formatJsonOutput(array(),__('There are no events to display.')) );
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
            $eventData = ((!empty($entry->EventData)) ? unserialize(base64_decode($entry->EventData)) : ''); //<< values to use for event description

            $eventCount = intval($entry->EventCount);
            // get User Info
            if($UserID == 0){ $username = 'System'; }
            else {
                $user_info = get_userdata($UserID);
                $username = $user_info->user_login;
                $first_name = $user_info->user_firstname;
                $last_name = $user_info->user_lastname;
                $username = "$username ($first_name $last_name)";
            }

            // get event details
            $eventDetails = WPPHEvent::getEventDetailsData($EventID);

            // format event description message
            if($eventCount >=2 && $EventID == 1002){
                $evm = sprintf(__('<strong>%d</strong> failed login attempts from <strong>%s</strong> using <strong>%s</strong> as username.'), $eventCount, $userIP, base64_decode($entry->UserName));
            }
            else {
                if(empty($eventData)) { $evm = $eventDetails->EventDescription; }
                else { $evm = vsprintf($eventDetails->EventDescription, $eventData); }
            }

            $e = array(
                'eventNumber' => $eventNumber,
                'eventId' => $EventID,
                'EventType' => $eventDetails->EventType,
                'eventDate' => $EventDate,
                'ip' => $userIP,
                'user' => $username,
                'description' => stripslashes($evm)
            );
            array_push($out['events'], $e);
        }
        $out['eventsCount'] = $eventsCount;

        exit(__formatJsonOutput($out));
    }

}