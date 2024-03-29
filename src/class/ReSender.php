<?php
/**
 * Description of ReSender
 *
 * @author Charles
 */
class ReSender {
    
    private $limitDay = 7;

    /**
     * Method for resend approval email
     * @param Ticket $ticket
     */
    public function resendMail(Ticket $ticket, Mail $mail) {
        $tickets = $ticket->getPendingTickets($this->limitDay);
        $Log = new Log();
        $Log->setLogPath(__LOG_URI_PATH__);
        $Log->setLogDir(date('Y-m'));
        $Log->setLogFile(date('ymd') .'.log');
        
        if (count($tickets)) {
            foreach ($tickets as $key => $value) {
                $ticket->setId($value['ID']);
                $mail->getMailByTicket($ticket);
                if ($mail->resend()) {
                    $logMsg = "Re-Sending approval Email ID ". $mail->getId() ." for Ticket #". $value['TicketNo'] ." [". date('d M Y, H:i:s') ."]\n";
                }
            }
        }
        else {
            $logMsg = "Re-Sender had been executed on ". date('d M Y, H:i:s') .", but no pending tickets approval found";
        }
        
        @$Log->writeLog($logMsg, 'a+');
    }
}
?>
