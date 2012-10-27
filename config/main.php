<?php
$is_production = FALSE;
$var_uri = '__BASE_URI__';
$var_url = '__BASE_URL__';

if ($is_production) {
    define($var_uri, '/home/pfizer.jsmservice.co.id/public_html/crontab/ResendApproval');
    define($var_url, 'http://pfizer.jsmservice.co.id/public_html/crontab/ResendApproval');
}
else {
    if (PHP_OS === 'Linux') {
        define($var_uri, '/media/data/Project/webapps/cli/ResendApproval');
    }
    else {
        # Office PC Path
        # define($var_uri, 'D:/Project/webapps/cli/ResendApproval');
        # Home PC Path
        define($var_uri, 'D:/root/My Work/webapps/cli/ResendApproval');
    }
    define($var_url, 'http://localhost/cli/ResendApproval');
}
define('__SRC_URI_PATH__', $var_uri .'/src');
define('__CLASS_PATH__', __SRC_URI_PATH__ .'/class');
define('__LOG_URI_PATH__', $var_uri .'/logs');

date_default_timezone_set('Asia/Jakarta');

$show_error = ($is_production) ? 0 : 1;
ini_set('display_errors', $show_error);

// create error handler
function myErrorHandler($e_num, $e_msg, $e_file, $e_line, $e_vars) {
    if (($e_num != E_NOTICE) && ($e_num < 2048)) {
        global $is_production;
        
        // create error message
        $message = "Error found in file $e_file at line $e_line {NL}";
        $message .= "DateTime : ". date('d M Y, H:i:s') ."{NL}";
        $message .= "Messages :{NL}$e_msg{NL}";
        $message .= "Query :{NL}". MySQL::getQuery(true) ."{NL}";
        
        // show errors
        if ($is_production) {
            $fopen = fopen(__LOG_URI_PATH__ .'/errors.log', 'a+');
            $message .= '{NL}***************************************************{NL}{NL}';
            fwrite($fopen, str_replace("{NL}", "\n", $message));
            echo '<p class="error">An error in the system has occurred. We apologize for this error.</p>';
            
            // Set Developer email
            $dev_email = array('Charles' => 'charles@jsm.co.id',
                               'Charles' => 'charles@w3magz.com');
            foreach($dev_email as $name => $email) {
                $recepients .= $name .'<'. $email .'>, ';
                $mail_to .= $email .', ';
            }
            $mail_from = 'noreply@appbugs.org';
            $mail_subject = "App Bug Notification [Don't Need To Reply]";
            $mail_body = "
Dear Developer(s)
\n\n
Some error just found for your application, please read this message below:\n\n".
str_replace("{NL}", "\n", $message) ."
\n\n
Details :
". var_export($e_vars, TRUE) ."
\n\n
#AppBugs#
";
            $mail_header = 'To: '. $recepients;
            $mail_header .= 'From: App Bug Notifier <'. $mail_from .'>';
            @mail($mail_to, $mail_subject, $mail_body, $mail_header);
        }
        else {
            echo str_replace("{NL}", "\n", $message);
        }
    }
}
set_error_handler('myErrorHandler');

function __autoload($class) {
    require_once (__CLASS_PATH__ .'/'. $class .'.php');
}
MySQL::setConnection('dev');
?>