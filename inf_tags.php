<?php

ini_set('max_execution_time', 0);
require_once __DIR__ . '/includes/header.php';

if (utils::isCron())
    utils::handleCron();

include_once(dirname(__FILE__) . '/vendor/isdk/infusionData.php');

$infObject = new iNFUSION();

$contactTags = $infObject->getContactTags();

$cnt_i = $cnt_u = 0;
$tblName = "tag";
if (count($contactTags) && !empty($cr_user['id'])) {
    $utils = new utils();
    $tmp_db_tags = $db_util->SelectTable($tblName, "inf_id != '' AND user_id = '".$cr_user['id']."'", "inf_id");
    foreach ($tmp_db_tags as $key => $db_tag) {
        $db_tags[] = $db_tag['inf_id'];
    }
    foreach ($contactTags as $tag) {
        $data = array();

        $data['inf_id'] = $tag->Id;
        $data['name'] = $utils->quotestoascii($tag->GroupName);
        $data['inf_category_id'] = $tag->GroupCategoryId;
        $data['inf_description'] = $utils->quotestoascii($tag->GroupDescription);
        $data['user_id'] = $cr_user['id'];
        if (empty($db_tags) || !in_array($tag->Id, $db_tags)) {
            $id = $db_util->InsertRecords($tblName, $data);
            $cnt_i++;
        } else {
            $id = $db_util->UpdateRecords($tblName, "inf_id = '" . $tag->Id . "'", $data);
            $cnt_u++;
        }
    }
}
echo "<br><br> Created  = " . $cnt_i;
echo "<br> Updated  = " . $cnt_u;
echo "<br> Total  = " . ($cnt_i + $cnt_u);
?>

