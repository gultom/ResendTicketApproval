<?php
/**
 * Description of Ticket
 *
 * @author Charles
 */
class Ticket {
    
    private $id = null;
    private $pendingTickets = array();
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }

    /**
     * Method to get list of tickets where still pending after n day(s)
     * @param integer $limitDay Limit day
     * @return Array(ID)
     */
    public function getPendingTickets($limitDay) {
        $query = sprintf("SELECT DISTINCT tickets.ticket_id AS ID,
                          tickets.ticket_no AS TicketNo
                          FROM tickets
                          JOIN mail_outbox ON mail_outbox.ticket_id=tickets.ticket_id
                          WHERE tickets._status=-1
                          AND mail_outbox.mail_type=4
                          AND mail_outbox.mail_date=DATE_SUB(CURRENT_DATE(), INTERVAL %d DAY)",
                          $limitDay);
        MySQL::setQuery($query);
        return $this->pendingTickets = MySQL::fetchRows();
    }
}

?>
