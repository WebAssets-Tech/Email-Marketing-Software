<?php

namespace App\Utils;

class JsonExtractor {
    protected $matchCase = '~\{(?:[^{}]|(?R))*\}~';

    protected $text = null;

    protected $extracted = null;

    public function __construct(?string $text = null) {
        $this->text = $text;
    }

    /**
     * Extract Json string from a substring and decodes it into object
     *
     * @return mixed
     */
    public function extract(?string $text = null) {
        $text = $this->text ?? $text;
        $matched = preg_match($this->matchCase, $text, $extracted);
        $this->extracted = $matched ? json_decode($extracted[0]) : $text;

        return $this;
    }

    public function getMessage() {
        return is_object($this->extracted) ? $this->extracted->message : $this->extracted;
    }
}
