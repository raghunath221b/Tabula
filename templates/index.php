<?php
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(-1);
require_once('includes/db_conn.php');

ini_set('display_errors', 'On');

if(!isset($_SESSION)){
    session_start();
}

$userid = $_POST['userid'];
$sql_find_fullname = "SELECT * FROM User_Park WHERE ID=$userid";
$result_find_fullname = $dbc->query($sql_find_fullname);
$row_fullname = $result_find_fullname->fetch_assoc();
$fullname = $row_fullname['FullName'];
$roleid = $_POST['roleid'];
//Check for empty fields
//Create short variables
$track_name = addslashes($_POST['track_name']);	// update addslashes on march 2
$description = addslashes($_POST['description']);
$intro = addslashes($_POST['introduction']);
$track_show_on_mobile = $_POST['track_show_on_mobile'];
$tracktag = $_POST['tracktag'];
$start_time = $_POST['time_start'];
$end_time = $_POST['time_end'];
if($roleid == 1){
	$available = $_POST['available'];
}else{
	$available = 0;
}
$tracklevel = $_POST['tracklevel'];
$tracklength = $_POST['tracklength'];
$address = $_POST['address'];
$attributes = $_POST['attributes'];
//$point_order = $_POST['point_order'];

//================================== badge pic start
if(!empty($_FILES["file_badge"]["type"])){
$uploaddir_badge = '../../demo/badges/images/';
$type=array("jpg","gif","bmp","jpeg","png");
//echo $_FILES["file"]["type"]; 

if(!file_exists($uploaddir_badge))
{
  mkdir("$uploaddir", 0777);
}
$tp = array("image/gif","image/pjpeg","image/png","image/jpeg"); 
$uploadOk_badge = 1;
$filetype_badge = $_FILES["file_badge"]["type"];
if($filetype_badge == 'image/jpeg'){ 
  $type_badge = '.jpg'; 
} 
if ($filetype_badge == 'image/jpg') { 
  $type_badge = '.jpg'; 
} 
if ($filetype_badge == 'image/pjpeg') { 
  $type_badge = '.jpg'; 
} 
if($filetype_badge == 'image/gif'){ 
  $type_badge = '.gif'; 
} 
if($filetype_badge == 'image/png'){ 
  $type_badge = '.png'; 
} 
//$date_time=strtotime(date('Y-m-d', time()));
//$rand_str=rand(); 
$file_name_badge = md5(uniqid(rand()))."$type_badge";
$file_size_badge = $_FILES["file_badge"]["size"];
if($file_size_badge > 8000000)
{
   $uploadOk_badge = 0;
   //var_dump($uploadOk);
}
if(!in_array($_FILES["file_badge"]["type"],$tp))  
{  
  $uploadOk_badge = 0;
}
  
$image_badge = $uploaddir_badge . $file_name_badge;

if($uploadOk_badge != 0)
{
  $result1=move_uploaded_file($_FILES["file_badge"]["tmp_name"],$image_badge);
}
//$image1 = "http://parkapps.kent.edu/interpoSystem/AdventureTracks/".$image;

if($result1){ 
	//$sql_check = "SELECT * FROM AdventureTracks order by ID DESC limit 0,1";
	//$res_check = $dbc->query($sql_check);
	//$row_check = $res_check->fetch_assoc();
	//$new_adv_id = $row_check['ID']+1;
	$sql_badge = "INSERT INTO Badges(Badge_Name,Badge_Pic_Name,Badge_Pic_Path) VALUES('".$track_name."','".$file_name_badge."','images/')";
	$res_badge = $dbc->query($sql_badge);
  if($res_badge){
    //echo "<script language=JavaScript> alert(\"Badges successfully!\");</script>";
  }else{
    echo '<h1>Badges Error</h1>';
  }
}

}else{
	$sql_badge = "INSERT INTO Badges(Badge_Name,Badge_Pic_Name,Badge_Pic_Path) VALUES('".$track_name."','default_badge.png','images/')";
	$res_badge = $dbc->query($sql_badge);
  if($res_badge){
    //echo "<script language=JavaScript> alert(\"Badges successfully!\");</script>";
  }else{
    echo '<h1>Badges Error</h1>';
  }
}
//========================================Badge end
if(isset($_POST['image_choose']) && $_POST['image_choose']== "0"){
    $image1 = $_POST['image-url1'];
    if (!isset($image1)) {//image not exist, change to default image
        $image1 = "http://parkapps.kent.edu/interpoSystem/AdventureTracks/images/default.jpg";
    }
    if($roleid == 1){
$query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";
    } else if($roleid == 2){
        $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid ,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    }else if($roleid == 3){
        $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid ,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    }


    $result = $dbc->query($query);
    if($result){
        echo "<script language=JavaScript> alert(\"Insert successfully!\"); window.location='list.php?userID=".$userid."&roleID=".$roleid."'</script>";
    } else {
        echo '<h1>System Error</h1>';
    }
    $dbc->close();
}else if (isset($_POST['image_choose']) && $_POST['image_choose']== "1") {
        $image1 = $_FILES["file"]["tmp_name"];
        //var_dump($image1);
        //exit;
        if(isset($image1)){
            $uploaddir = 'images/';
            $type=array("jpg","gif","bmp","jpeg","png");
            //echo $_FILES["file"]["type"]; 

            if(!file_exists($uploaddir))
            {
                mkdir("$uploaddir", 0777);
            }
            $tp = array("image/gif","image/pjpeg","image/png","image/jpeg"); 
            $uploadOk = 1;
            $filetype = $_FILES["file"]["type"];
            if($filetype == 'image/jpeg'){ 
                $type = '.jpg'; 
            } 
            if ($filetype == 'image/jpg') { 
                $type = '.jpg'; 
            } 
            if ($filetype == 'image/pjpeg') { 
                $type = '.jpg'; 
            } 
			
			
            if($filetype == 'image/gif'){ 
                $type = '.gif'; 
            } 
            if($filetype == 'image/png'){ 
                $type = '.png'; 
            } 
            //$date_time=strtotime(date('Y-m-d', time()));
            //$rand_str=rand(); 
            $file_name = md5(uniqid(rand()))."$type";
            $file_size = $_FILES["file"]["size"];
            if($file_size > 5000000)
            {
                $uploadOk = 0;
                //var_dump($uploadOk);
            }
            if(!in_array($_FILES["file"]["type"],$tp))  
            {  
                $uploadOk = 0;
            }
  
            $image = $uploaddir . $file_name;
  
            if($uploadOk != 0)
            {
                $result1=move_uploaded_file($_FILES["file"]["tmp_name"],$image);
            }
	        $image1 = "http://parkapps.kent.edu/interpoSystem/AdventureTracks/".$image;
            //var_dump($result);
            if($result1){ 
                if($roleid == 1){
                    $sql = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

                } else if($roleid == 2){
                    $sql = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

                } else if($roleid == 3){
        		$sql = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid ,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    		}
   
                    //$sql = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."')";
                $result = $dbc->query($sql);
                if($result){
                    echo "<script language=JavaScript> alert(\"Insert successfully!\"); window.location='list.php?userID=".$userid."&roleID=".$roleid."'</script>";
                } else {
                    echo '<h1>System Error</h1>';
                }
                $dbc->close();
            }
        }else{
            $image1 = "http://parkapps.kent.edu/interpoSystem/AdventureTracks/images/default.jpg";
            if($roleid == 1){
                $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

            } else if($roleid == 2){
                $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."',$userid,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

            } else if($roleid == 3){
        	$query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid ,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    	}
   
            $result = $dbc->query($query);
            if($result){
                echo "<script language=JavaScript> alert(\"Insert successfully!\"); window.location='list.php?userID=".$userid."&roleID=".$roleid."'</script>";
            } else {
                echo '<h1>System Error</h1>';
            }
            $dbc->close();
        }
}else{
    $image1 = "http://parkapps.kent.edu/interpoSystem/AdventureTracks/images/default.jpg";

    if($roleid == 1){
        $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    } else if($roleid == 2){
        $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."',$userid,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    } else if($roleid == 3){
        $query = "INSERT INTO AdventureTracks(ID,Track_Name,Introduction,Description,available,trackLevel,trackLength,Address, Image_url,attributes,Create_user_id,Create_by,TrackType,trackTag,start_time,end_time) VALUES (NULL, '".$track_name."','".$intro."','".$description."', '".$available."','".$tracklevel."',$tracklength,'".$address."','".$image1."','".$attributes."',$userid ,'".$fullname."',$track_show_on_mobile,'".$tracktag."','".$start_time."','".$end_time."')";

    }
     
  $result = $dbc->query($query);
  if($result){
	if($roleid == 1){
    		echo "<script languag
			e=JavaScript> alert(\"Insert successfully!\"); window.location='list.php?userID=".$userid."&roleID=".$roleid."'</script>";
	}else if($roleid == 2){
		echo "<script language=JavaScript> alert(\"Insert successfully!\"); window.location='sciencelist.php?userID=".$userid."&roleID=".$roleid."'</script>";
	}
  } else {
    echo '<h1>System Error</h1>';
  }
  $dbc->close();
}


?>



