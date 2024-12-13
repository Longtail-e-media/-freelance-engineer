<?php
// Load the header files first
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("cache-control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

// Load necessary files then...
require_once('../initialize.php');

$action = $_REQUEST['action'];

switch($action) {

    case "jobToggleStatus":
        $id     = $_REQUEST['id'];
        $record = jobs::find_by_id($id);
        $record->status = ($record->status == 1) ? 0 : 1 ;
        $db->begin();
        $res   =  $record->save();
        if ($res): $db->commit(); else: $db->rollback(); endif;
        echo "";
    break;

    case "addRating":
        $record                 = jobs::find_by_id($_REQUEST['idValue']);
        $record->admin_rating   = $_REQUEST['admin_rating'];
        $record->reviewed_admin = 1;

        $db->begin();
        if ($record->save()):$db->commit();
            // update client rating
            $bids = bids::find_by_sql("SELECT * FROM tbl_bids WHERE job_id='$record->id' AND client_id='$record->client_id' AND project_status='5' ");
            if (!empty($bids)) {
                foreach ($bids as $bid) {
                    calculate_rating_for_client($bid->id);
                }
            }
            $message = "Admin rating for Job '" . $record->title . "' for Client added successfully!";
            echo json_encode(array("action" => "success", "message" => $message));
            log_action($message, 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
    break;

    case "addRatingFreelancer":
        $record                 = bids::find_by_id($_REQUEST['idValue']);
        $record->admin_rating   = $_REQUEST['admin_rating'];
        $record->reviewed_admin = 1;
        $jobTitle               = jobs::field_by_id($record->job_id,'title');

        $db->begin();
        if ($record->save()):$db->commit();
            // update freelance rating
            calculate_rating_for_freelancer($record->id);
            $message = "Admin rating for Job '" . $jobTitle . "' for Freelancer added successfully!";
            echo json_encode(array("action" => "success", "message" => $message));
            log_action($message, 1, 4);
        else: $db->rollback();
            echo json_encode(array("action" => "notice", "message" => $GLOBALS['basic']['noChanges']));
        endif;
    break;
}