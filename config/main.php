<?php
$is_production = FALSE;

if ($is_production) {
    define('__BASE_URI__', '/home/pfizer.jsmservice.co.id/public_html/crontab/ResendTicketApproval');
    define('__BASE_URL__', 'http://pfizer.jsmservice.co.id/public_html/crontab/ResendTicketApproval');
}
else {
    if (PHP_OS === 'Linux') {
        # Office PC Path
        define('__BASE_URI__', '/media/data/Project/webapps/cli/ResendTicketApproval');
    }
    else {
        # Office PC Path
        define('__BASE_URI__', 'D:/Project/webapps/cli/ResendTicketApproval');
        # Home PC Path
        # define('__BASE_URI__', 'D:/root/My Work/webapps/cli/ResendTicketApproval');
    }
    define('__BASE_URL__', 'http://localhost/cli/ResendTicketApproval');
}
define('__SRC_URI_PATH__', __BASE_URI__ .'/src');
define('__CLASS_PATH__', __SRC_URI_PATH__ .'/class');
define('__LOG_URI_PATH__', __BASE_URI__ .'/logs');

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
        $message .= "Query :{NL}". MySQL::getQuery(FALSE) ."{NL}";
        
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