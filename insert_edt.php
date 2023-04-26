<?
require_once('/var/www/html/projet_sn_bts_anthony/vendor/autoload.php');
$config = array('directory' => '/var/www/html/projet_sn_bts_anthony/ics/');
use ICal\ICal;
$ical = new ICal($config);

foreach($vcalendar->getComponent('vevent') as $event) {
    $summary = $event->getPropertyValue('summary');
    $start = $event->getPropertyValue('dtstart');
    $end = $event->getPropertyValue('dtend');
    // Do something with the event data...
}

?>