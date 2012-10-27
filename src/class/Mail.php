<?php
/**
 * Description of Mail
 *
 * @author Charles
 */
class Mail {
    
    private $id = null;
    
    /**
     * Method to set mail ID
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Method to get mail ID
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Method to get Mail ID refer by ticket ID
     * @param Ticket $ticket
     */
    public function getMailByTicket(Ticket $ticket) {
        $query = sprintf("SELECT mail_outbox.mail_id AS ID
                          FROM mail_outbox
                          WHERE mail_outbox.mail_type=4
                          AND mail_outbox.ticket_id=%d
                          ORDER BY mail_outbox.mail_date DESC,
                          mail_outbox.mail_time DESC
                          LIMIT 1",
                          $ticket->getId());
        MySQL::setQuery($query);
        $result = MySQL::fetchRow();
        $this->setId($result['ID']);
    }
    
    public function resend() {
        $query = sprintf("UPDATE mail_outbox
                          SET mail_outbox.status=0
                          WHERE mail_outbox.mail_id=%d",
                          $this->getId());
        MySQL::setQuery($query);
        if (MySQL::execute()) {
            return true;
        }
        return false;
    }
}

?>
