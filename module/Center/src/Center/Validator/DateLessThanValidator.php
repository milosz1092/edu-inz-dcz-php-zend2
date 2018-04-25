<?php

namespace Center\Validator;
use Zend\Validator\AbstractValidator;

class DateLessThanValidator extends AbstractValidator
{
    const MSG_NOT_LESS = 'msgNotLess';
    
    protected $maximum = 0;
    
    protected $messageVariables = array(
        'max' => 'maximum'
    );
    
    protected $messageTemplates = array(
        self::MSG_NOT_LESS => "Dzień '%value%' nie jest mniejszy od '%max%'",
    );

    public function isValid($value) {
        $this->maximum = $this->getOption('max');
        $this->setValue($value);

        if ($value >= $this->getOption('max')) {
            $this->error(self::MSG_NOT_LESS);
            return false;
        }
        
        return true;
    }
}

?>