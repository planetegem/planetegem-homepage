<?php

namespace Core;
class Logger
{
    // Fetch env variables on construction 
    private $debug;
    private $log_function;

    public function __construct()
    {
        $this->debug = App::service('ENV')->DEBUG_LOCAL;
        $this->log_function = APP::service('ENV')->LOG_FUNCTION;

        $this->message('Debug session started');
    }

    // Write message to log
    public function message(string $message): void
    {
        if (!$this->log_function || !$this->debug)
            return;

        $timestamp = date("d-m-Y H:i:s");
        $trace = $this->trace();
        $formatted = "{$timestamp} | {$trace} | {$message}";

        match ($this->log_function) {
            'TXT_LOG' => error_log($formatted . "\r\n", 3, 'logs.txt'),
            'JS_DUMP' => print ("<script>console.log(" . json_encode($formatted) . ");</script>"),
            default => null
        };
    }

    // Trace which php file & line the message or variable is coming from 
    private function trace(): string
    {
        // Skip if running PHP from CLI (shouldn't be the case)
        if (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg')
            return 'CLI WARNING';

        // Trace where the message is being called from
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $caller = $backtrace[1] ?? [];
        $file = basename($caller['file'] ?? 'Unknown');
        $line = $caller['line'] ?? 'Unknown';

        return "PHP: {$file} (line {$line})";
    }
}