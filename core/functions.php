<?php  

    class myFunc{
        public static function myQuery($query, $datatype, $variables, $type){
            include("db.php");
            $stmt = $conn->prepare($query);
            $param = array();
            $count = count($variables);
            for($i = 0; $i < $count; ++$i){
                $param[] = &$variables[$i];
            }
            array_unshift($param, $datatype);
            call_user_func_array(array($stmt, 'bind_param'), $param);
            $execute = $stmt->execute();
            if ($type == 'result') {
                $result = $stmt->get_result();
                $stmt->close();
                $conn->close();
                return $result;
            }elseif ($type == 'fetch') {
                $result = $stmt->get_result();
                $stmt->close();
                $conn->close();
                return $result->fetch_assoc();
            }elseif ($type == 'action') {
                if ($execute == false) {
                    echo $stmt->error;
                    $stmt->close();
                    $conn->close();
                    return false;
                }else{
                    $stmt->close();
                    $conn->close();
                    return true;
                }
            }
        }

        // my email function
        public static function sendmail($to,$from,$subject,$message){
        
            // headers
            $headers = "From: " . $from . "\r\n";
            $headers .= "MIME-Version: 1.0 \r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1 \r\n";
              
            //send email
            if ( mail($to, $subject, $message, $headers) )
                return true;
            else
                return error_get_last();
        }

        // my salt function.
        public static function cryptPass($input, $rounds = 9){
            $salt = "";

            $saltChars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));

            for($i = 0; $i < 22; $i++){
                $salt .= $saltChars[array_rand($saltChars)];
            }

            return crypt($input, sprintf('$2y$%02d$', $rounds) . $salt);
        }

        public static function imgUpload($path,$imgName,$imgTemp,$imgSize){ 
            $target_dir = $path;
            $target_file = $target_dir . basename($imgName);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // Check if image file is a actual image or fake image
                $check = getimagesize($imgTemp);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                    return false;
                }
            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
                return true;
            }
            // Check file size
            /*if ($imgSize > 20000000) {
                $uploadOk = 0;
                return false;
            }*/
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $uploadOk = 0;
                return false;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            // if everything is ok, try to upload file
                return false;
            } else {
                if (move_uploaded_file($imgTemp, $target_file)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public static function docUpload($path,$docName,$docType,$docTemp,$docSize){ 
            $target_dir = $path;
            $target_file = $target_dir . basename($docName);
            $uploadOk = 1;
            $docFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            if($docType !== 'application/pdf' && $docType !== 'application/msword')
                die("Unknown / Not permitted file type"); 

            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
                return true;
            }
            // Check file size
            if ($docSize > 7000000) {
                $uploadOk = 0;
                return false;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
            // if everything is ok, try to upload file
                return false;
            } else {
                if (move_uploaded_file($docTemp, $target_file)) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public static function reArrayFiles(&$file_post) {

            $file_ary = array();
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);

            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $file_post[$key][$i];
                }
            }

            return $file_ary;
        }

        public static function timeAgo($timestamp){
            $datetime1=new DateTime("now");
            $datetime2=date_create($timestamp);
            $diff=date_diff($datetime1, $datetime2);
            $timemsg='';
            if($diff->y > 0){
                $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

            }
            else if($diff->m > 0){
             $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
            }
            else if($diff->d > 0){
             $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
            }
            else if($diff->h > 0){
             $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
            }
            else if($diff->i > 0){
             $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
            }
            else if($diff->s > 0){
             $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
            }

            $timemsg = $timemsg.' ago';
            return $timemsg;
        }

        public static function api_get($url){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $json = json_decode($response, true);
            curl_close($ch);
            return $json;
        }

        public static function auto_link($text) {
          $pattern = '/(((http[s]?:\/\/(.+(:.+)?@)?)|(www\.))[a-z0-9](([-a-z0-9]+\.)*\.[a-z]{2,})?\/?[a-z0-9.,_\/~#&=:;%+!?-]+)/is';
          $text = preg_replace($pattern, ' <a href="$1" target="_blank" rel="nofollow">$1</a>', $text);
          // fix URLs without protocols
          $text = preg_replace('/href="www/', 'href="http://www', $text);
          return $text;
        }
    }
?>