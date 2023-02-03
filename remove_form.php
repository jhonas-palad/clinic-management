<?php
include './config/connection.php';

 $message = '';
if(isset($_POST['form_remove'])) {
    $form_id = $_POST['form_id'];
    $form_filename = $_POST['form_filename'];
    if($form_id !== '') {
        try{

            $del_query = "DELETE FROM `template_forms` WHERE `id`= $form_id;";
            
            $con->exec($del_query);

            $message = "Record deleted sucessfully.";
            
            if(file_exists($form_filename)){
                
                unlink($form_filename);
            }

        }catch(PDOException $ex){
            $con->rollback();
            echo $ex->getMessage();
            echo $ex->getTraceAsString();
            exit;
        }

}
header("Location:congratulation.php?goto_page=forms.php&message=$message");
exit;
}

?>


