<?php
/**
 * Description of Log
 *
 * @author Charles
 */
class Log {
    
    private $logPath = null;
    private $logFile = null;
    private $logDir = null;
    
    /**
     * Method to set Log Path
     * @param string $path
     */
    public function setLogPath($path) {
        $this->logPath = $path;
    }
    
    /**
     * Method to get Log Path
     * @return type string Log Path
     */
    public function getLogPath() {
        return $this->logPath;
    }
    
    /**
     * Method to set Log Filename
     * @param string $filename
     */
    public function setLogFile($filename) {
        $this->logFile = $filename;
    }
    
    /**
     * Method to get Log Filename
     * @return string Log Filename
     */
    public function getLogFile() {
        return $this->logFile;
    }
    
    /**
     * Method to set Log Directory, if not exist attempt to create it
     * @param string $dirName Directory Name under log path
     */
    public function setLogDir($dirName) {
        $this->logDir = $dirName;
        if (!is_dir($this->getLogPath() .'/'. $this->getLogDir())) {
            mkdir($this->getLogPath() .'/'. $this->getLogDir());
        }
    }
    
    /**
     * Method to get Log Directory
     * @return string Directory Name
     */
    public function getLogDir() {
        return $this->logDir;
    }

    /**
     * Method to write log
     * @param string $message
     * @param string $mode (r, r+, w, w+, a, a+, x, x+, c, c+) more info see php manual fopen() mode
     * @return boolean
     */
    public function writeLog($message, $mode) {
        if ($message !== '') {
            $log_path = ($this->getLogDir()) ? $this->getLogPath() .'/'. $this->getLogDir() : $this->getLogPath();
            $fopen = fopen($log_path .'/'. $this->getLogFile(), $mode);
            if (fwrite($fopen, $message)) {
                return true;
            }
        }
        return false;
    }
}

?>
