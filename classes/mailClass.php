<?php

class Mail {
    private $mailFrom;
    private $mailTo;
    private $monitorAddress;
    private $mailSubject;
    private $mailBody;
    private $mailHeader;
    private $mailParams;
    
    public function __construct($mailFrom, $mailTo, $monitorAddress, $mailSubject, $mailBody) {
        $this->mailFrom=$mailFrom;
        $this->mailTo=$mailTo;
        $this->monitorAddress=$monitorAddress;
        $this->mailSubject=$mailSubject;
        $this->mailBody=$mailBody;
        $mailHeader  = 'MIME-Version: 1.0'."\r\n";
        $mailHeader .= 'Content-Type: text/html; charset="UTF-8"'."\r\n"; 
        $mailHeader .= 'From:'.$mailFrom."\r\n";
        $mailHeader .= 'Reply-To:'.$mailTo."\r\n";
        $mailHeader .= 'Bcc: '.$monitorAddress."\r\n";
        $this->mailHeader=$mailHeader;
        $this->mailParams = '-f'.$mailFrom;
        
    }
    
    public function enviaMail(){
        $mailResult = mail($this->mailTo,$this->mailSubject,$this->mailBody,$this->mailHeader,$this->mailParams);
        return $mailResult;
    }
}

?>
