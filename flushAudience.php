<?php

ini_set('max_execution_time', 0);
require_once __DIR__ . '/includes/header.php';

use FacebookAds\Api;
use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceTypes;
use FacebookAds\Object\Values\CustomAudienceSubtypes;

$tblName = "audience";
$tblRelName = "audience_contact";

if ($is_cron = utils::isCron()) {
    utils::handleCron();

    //Get audiences
    $audiences = $db_util->SelectTable($tblName, "user_id = '" . ACTIVE_USER_ID . "' AND active = '1' AND (fb_id IS NOT NULL OR fb_id != '') AND (groups IS NOT NULL OR groups != '') AND sync_date < DATE_SUB(NOW(),INTERVAL 179 MINUTE) ORDER BY sync_date", "");
} else {
    //Get audience record
    $tmp_audi_id = (isset($_POST['audi_id']) && !empty($_POST['audi_id'])) ? $_POST['audi_id'] : $_GET['audi_id'];
    $tmp_db_audience = $db_util->SelectTable($tblName, "id = '" . $tmp_audi_id . "'", "");
    $audiences[] = reset($tmp_db_audience);
}

if ($audiences) {
    foreach ($audiences as $db_audience) {
        $audi_id = $db_audience['id'];
        if (!empty($audi_id)) {

            if (!empty($db_audience) && !empty($db_audience['fb_id'])) {
                // Initialize a new Session and instantiate an Api object
                Api::init(
                        FB_APP_ID, // App ID
                        FB_APP_SECRET, $_SESSION['facebook_access_token'] // Your user access token
                );
                // use the namespace for Custom Audiences and Fields
                // Create a custom audience object, setting the parent to be the account id
                $audience = new CustomAudience($db_audience['fb_id'], 'act_' . FB_ACCOUNT_ID);

                //Remove all users from FB audience if exists
                $tmp_db_audience_contacts = $db_util->SelectTable($tblRelName, "audience_id = '" . $audi_id . "' AND deleted = 0", "id,contact_emails");
                $db_audience_contacts = reset($tmp_db_audience_contacts);
                if (!empty($db_audience_contacts)) {
                    $usersToRemove = explode(',', $db_audience_contacts['contact_emails']);
                    $usersToRemove = array_unique(array_values($usersToRemove));

                    if (!empty($usersToRemove) && count($usersToRemove)) {
                        $audience->removeUsers($usersToRemove, CustomAudienceTypes::EMAIL);
                    }
                }

                //Get fresh list from infusionsoft
                if (!empty($db_audience['groups'])) {

                    $tagArr = explode(',', $db_audience['groups']);

                    if (!isset($utils) || !($utils instanceof utils))
                        $utils = new utils();

                    $contactArr = $utils->getInfContactsEmailsByTags($tagArr);
                }

                //Add fresh list of users into FB
                $usersToAdd = array_unique(array_values($contactArr));

                $data = $audi_data = array();

                if (is_array($usersToAdd) && !empty($usersToAdd) && count($usersToAdd)) {
                    $add_success = $audience->addUsers($usersToAdd, CustomAudienceTypes::EMAIL);
                    $audi_data['sync_date'] = 'now()';
                    $db_audi_id = $db_util->UpdateRecords($tblName, "id = '" . $audi_id . "'", $audi_data);
                    $data['contact_emails'] = implode(',', $usersToAdd);
                } elseif (!$is_cron)
                    $_SESSION['flash_msg'] = array('warning' => 'Empty Audience created, no contacts found in infusionsoft for selected tags.');

                //Update or inset emails list into local db
                $data['audience_id'] = $audi_id;
                $data['date_modified'] = 'now()';

                if (empty($db_audience_contacts) || empty($db_audience_contacts['id'])) {
                    $data['date_entered'] = $data['date_modified'];
                    $id = $db_util->InsertRecords($tblRelName, $data);
                } else {
                    $data['date_modified'] = 'now()';
                    $id = $db_util->UpdateRecords($tblRelName, "audience_id = '" . $audi_id . "'", $data);
                }
            }
        }
       if ($is_cron)
        echo '<br><br>Audience purged of id = ' . $audi_id . ' And facebook id = ' . $db_audience['fb_id'];
    }
} else {
    echo "<br><br> There are no audiences to synchronize...";
}
//header('Location: index.php');
