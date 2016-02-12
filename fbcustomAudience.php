<?php

require_once __DIR__ . '/includes/header.php';

use FacebookAds\Api;
// use the namespace for Custom Audiences and Fields
use FacebookAds\Object\CustomAudience;
use FacebookAds\Object\Fields\CustomAudienceFields;
use FacebookAds\Object\Values\CustomAudienceTypes;
use FacebookAds\Object\Values\CustomAudienceSubtypes;

try {
    if (isset($_POST) && !empty($_POST) && !empty($_POST['tags'])) {

        $tblName = "audience";
        $audi_id = (isset($_POST['audi_id']) && !empty($_POST['audi_id'])) ? $_POST['audi_id'] : null;

        $update_audi = FALSE;
        if (!empty($audi_id)) {
            $tmp_db_audience = $db_util->SelectTable($tblName, "id = '" . $audi_id . "'", "fb_id");

            if (count($tmp_db_audience) && !empty($tmp_db_audience[0]['fb_id'])) {
                $audi_id = $tmp_db_audience[0]['fb_id'];
                $update_audi = TRUE;
            }
        }

        $tagArr = array();
        if (count($_POST['tags'])) {
            foreach ($_POST['tags'] as $tag) {
                $tagIds = explode('|', $tag);
                $tagArr[$tagIds[0]] = $tagIds[1];
            }
        }


//    CreateAudience
// Add to header of your file
// Initialize a new Session and instantiate an Api object
        Api::init(
                FB_APP_ID, // App ID
                FB_APP_SECRET, $_SESSION['facebook_access_token'] // Your user access token
        );

// Create a custom audience object, setting the parent to be the account id
        $audience = new CustomAudience($audi_id, 'act_' . FB_ACCOUNT_ID);

        if ($update_audi)
            $audience->read(array(CustomAudienceFields::NAME, CustomAudienceFields::DESCRIPTION, CustomAudienceFields::SUBTYPE));

        $audience->setData(array(
            CustomAudienceFields::NAME => $_POST['name'],
            CustomAudienceFields::DESCRIPTION => $_POST['description'],
            CustomAudienceFields::SUBTYPE => CustomAudienceSubtypes::CUSTOM,
        ));

        $audience->save();

//Create local audience record
        if (!empty($audience->id)) {
            if (empty($tmp_db_audience))
                $tmp_db_audience = $db_util->SelectTable($tblName, "fb_id = '" . $audience->id . "'", "fb_id");

            foreach ($tmp_db_audience as $key => $db_audience) {
                $db_audiences[] = $db_audience['fb_id'];
            }

            $data = array();

            $data['fb_id'] = $audience->id;
            $data['name'] = $_POST['name'];
            $data['description'] = $_POST['description'];
            $data['groups'] = implode(',', array_keys($tagArr));
            $data['user_id'] = $cr_user['id'];
            $data['active'] = 1;
            $data['date_modified'] = 'now()';
            if (empty($audi_id) || empty($db_audiences) || !in_array($audience->id, $db_audiences)) {
                $data['date_entered'] = $data['date_modified'];
                $_POST['audi_id'] = $id = $db_util->InsertRecords($tblName, $data);
            } else {
                $id = $db_util->UpdateRecords($tblName, "fb_id = '" . $audience->id . "'", $data);
            }
        }
//End 
// Assuming you have an array of emails:
// NOTE: The SDK will hash (SHA-2) your data before submitting
// it to Facebook servers

        if (!empty($audience->id)) {
            include_once 'flushAudience.php';
            if ($update_audi)
                $_SESSION['flash_msg']['success'] = 'Audience updated successfully.';
            else
                $_SESSION['flash_msg']['success'] = 'Audience created successfully.';

//    $_SESSION['flash_msg']['warning'] = 'Empty Audience created, no contacts found in infusionsoft for selected tags.';
        } else {
            $_SESSION['flash_msg']['danger'] = 'Something went wronge, Audience not created.';
        }
    }
} catch (Exception $e) {
    $_SESSION['flash_msg']['danger'] = (isset($update_audi) && $update_audi) ? 'Something wrong, unable to update audience. Please try again after sometime.' :'Something wrong, unable to create audience. Please try again after sometime.';
}
ob_clean();
header('Location: index.php');
