<?php

class CliLogger
{
    use Creatable;

    /**
     * The complete messages to log
     *
     * @var string
     */
    protected $log = '';

    /**
     * End of line
     *
     * @var string
     */
    protected $eol = "\n";

    /**
     * CLI request
     *
     * @var bool
     */
    protected $cli;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->cli = Request::isCli();
    }

    /**
     * Log a message
     *
     * @param string $sMessage [optional]
     * @param mixed  $m1       [optional] parameter 1 to use in sprintf
     * @param mixed  $mN       [optional] parameter N to use in sprintf
     */
    public function log(...$mMessage)
    {
        $this->log .= (count($mMessage) ? (count($mMessage) < 2 ? $mMessage[0] : sprintf(...$mMessage)) : '') . $this->eol;
    }

    /**
     * Output the messages
     *
     */
    public function output()
    {
        if ($this->log) {
            echo $this->cli ? $this->log : sprintf("<pre>----------\n%1\$s\n----------</pre>", $this->log);
        }
    }
}