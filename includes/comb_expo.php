<?php

// Check whether users come to this page via clicking export button
// if(!isset($_POST['export_submit'])){
//     header('Location: ../payout_matcher.php');
//     exit();
// }

// Some details from uploaded files are needed to be stored as variables
$userFile = $_FILES['user_file'];
$userFilename = $userFile['name'];
$userTmpFilename = $userFile['tmp_name'];
$userFileExt = explode('.', $userFilename);
$userFileActualExt = strtolower(end($userFileExt));

$payoutFile = $_FILES['payout_file'];
$payoutFilename = $payoutFile['name'];
$payoutTmpFilename = $payoutFile['tmp_name'];
$payoutFileExt = explode('.', $payoutFilename);
$payoutFileActualExt = strtolower(end($payoutFileExt));

$affFile = $_FILES['aff_file'];
$affFilename = $affFile['name'];
$affTmpFilename = $affFile['tmp_name'];
$affFileExt = explode('.', $affFilename);
$affFileActualExt = strtolower(end($affFileExt));

$allowedExt = 'csv';

// Check whether both user, payout and aff files are .csv file extension
if ($userFileActualExt === $allowedExt || $payoutFileActualExt === $allowedExt || $affFileActualExt === $allowedExt){

    // For user file
    $userCSV = fopen($userTmpFilename, 'r');
    if($userCSV){
        
        $row_count = 0;
        $email_bankacc_table = array();

        while (($row = fgetcsv($userCSV, 0, ',')) !== false){
            
            if ($row_count === 0){
                // Extract column 'Email' and 'Bank Account' from user file
                $row_count += 1;
                $col_header = array_flip($row);
                $email_col_idx = $col_header['User Email'];
                $bank_acc_owner_name_col_idx = $col_header['Bank Account Owner Name'];
                $bank_acc_col_idx = $col_header['Bank Account No.'];
                $bank_acc_type_col_idx = $col_header['Bank Account Type'];
            }
            else{
                // Put data (email => [bank_acc_owner_name, bank acc type, bank acc no]) into a table
                $email = $row[$email_col_idx];
                $bank_acc_owner_name = $row[$bank_acc_owner_name_col_idx];
                $bank_acc_no = $row[$bank_acc_col_idx];
                $bank_acc_type = $row[$bank_acc_type_col_idx];
                $email_bankacc_table[$email] = array($bank_acc_owner_name, $bank_acc_type, $bank_acc_no);
                
                // print_r($email_bankacc_table[$email]);
                // echo '<br>';

            }   
        }

        // close the opened csv file
        fclose($userCSV);
    }
    else{
        echo 'Error: Having problems with opening .csv file (user)!';
    }

    // For aff file
    $affCSV = fopen($affTmpFilename, 'r');
    if($affCSV){
        
        $row_count = 0;
        $email_affid_table = array();

        while (($row = fgetcsv($affCSV, 0, ',')) !== false){
            
            if ($row_count === 0){
                // Extract column 'Email' and 'Affiliate ID' from user file
                $row_count += 1;
                $col_header = array_flip($row);
                $email_col_idx = $col_header['Email'];
                $aff_id_col_idx = $col_header['Affiliate ID'];
            }
            else{
                // Put data (email => [aff_id]) into a table
                $email = $row[$email_col_idx];
                $aff_id = $row[$aff_id_col_idx];
                $email_affid_table[$email] = array($aff_id);
                
                // print_r($email_bankacc_table[$email]);
                // echo '<br>';

            }   
        }

        // close the opened csv file
        fclose($affCSV);
    }
    else{
        echo 'Error: Having problems with opening .csv file (user)!';
    }

    // For payout file
    $payoutCSV = fopen($payoutTmpFilename, 'r');
    if($payoutCSV){
        
        $row_count = 0;
        $output_table = array();
        $output_filename = 'affiliate-wp-export-payouts-combined '.date("(d M Y H:i:s)").'.csv';
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$output_filename.'";');
        $output_file = fopen('php://output', 'w');

        while (($row = fgetcsv($payoutCSV, 0, ',')) !== false){
            
            if ($row_count === 0){
                $new_row = array_flip($row);
                $email_col_idx = $new_row['Email'];
                array_push($row, 'Affiliate ID', 'Bank Account Owner Name', 'Bank Type', 'Account No');
                fputcsv($output_file, $row);
                $row_count += 1;
            }
            else{
                $email = $row[$email_col_idx];
                $new_row = array_merge($row, $email_affid_table[$email], $email_bankacc_table[$email]);
                fputcsv($output_file, $new_row);
            }
        }
        fclose($payoutCSV);
        fclose($output_file);
    }
    else{
        echo 'Error: Having problems with opening .csv file (payout)!';
    }
}
else{
    echo 'Error: Only .csv file extension is allowed!';
}