<?php
namespace Application\Functions;
use DateTime;

class DateTimeExchange {
    private $months = array('01' => 'sty', '02' => 'lut', '03' => 'mar', '04' => 'kwi', '05' => 'maj', '06' => 'cze', '07' => 'lip', '08' => 'sie', '09' => 'wrz', '10' => 'paź', '11' => 'lis', '12' => 'gru');
    private $months_normal = array('01' => 'styczeń', '02' => 'luty', '03' => 'marzec', '04' => 'kwiecień', '05' => 'maj', '06' => 'czerwiec', '07' => 'lipiec', '08' => 'sierpień', '09' => 'wrzesień', '10' => 'październik', '11' => 'listopad', '12' => 'grudzień');
    private $months_when = array('01' => 'stycznia', '02' => 'lutego', '03' => 'marca', '04' => 'kwietnia', '05' => 'maja', '06' => 'czerwca', '07' => 'lipca', '08' => 'sierpnia', '09' => 'września', '10' => 'października', '11' => 'listopada', '12' => 'grudnia');
    
    public $year;
    public $month;
    public $month_normal;
    public $month_when;
    public $month_number;
    public $month_number_with_null;
    public $day;
    public $hour;
    public $min;
    public $sec;
    
    public function __construct($datetime = NULL) {
        if ($datetime != NULL) {
            $this->parseObject($datetime);
        }
    }
    
    public function generateDateString($datetime) {
        if ($datetime != NULL) {
            $this->parseObject($datetime);
        }
        
        return $this->day.' '.ucfirst($this->month_when).' '.$this->year;
    }
    
    public function generateDateTimeString($datetime) {
        if ($datetime != NULL) {
            $this->parseObject($datetime);
        }
        
        return $this->day.' '.ucfirst($this->month_when).' '.$this->year.', '.$this->hour.':'.$this->min;
    }
    
    public function generateAgeString($date) {
        if ($date != NULL) {
            $this->parseObject($date);
        }
        
        $wiek = date('Y') - $this->year; 

        if ((date('m') < $this->month_number) || (date('m') == $this->month_number && $this->day > date('d'))) { 
            $wiek--;
        } 

        if ($wiek > 0) {
           
            if ($wiek == 1)
                $koncowka = "rok"; 
            else if (($wiek > 21 && ($wiek % 10 == "2" || $wiek % 10 == "3" || $wiek % 10 == "4")) || $wiek < 5) { 
                $koncowka = "lata"; 
            } else { 
                $koncowka = "lat"; 
            }  

            return $wiek.' '.$koncowka;
            
        } else {
            
            if (date('Y') == $this->year) {
                $miesiac = date('m') - $this->month_number;
            } else if ($this->month_number == 12) {
                $miesiac = date('m');
            } else if ($this->month_number < 12) {
                $miesiac = (12 - $this->month_number) + date('m');
            }

            if ($this->day > date('d')) {
                $miesiac--;
            }
            
            if ($miesiac == 1) {
                $koncowka = "miesiąc"; 
            } else if ($miesiac > 4 && $miesiac < 22) {
                $koncowka = "miesięcy";
            } else { 
                $koncowka = "miesiące"; 
            }
            
            if ($miesiac > 0) {
                return $miesiac.' '.$koncowka;
            } else {
                if ($this->month_number_with_null == date('m')) {
                    $dzien = date('d') - $this->day;
                }else {
                    $date1 = new DateTime(date("Y-m-d"));
                    $date2 = new DateTime($date);

                    $dzien = $date2->diff($date1)->format("%r%a");
                }
                
                if ($dzien == 1) {
                    $koncowka = "dzień"; 
                } else {
                    $koncowka = "dni"; 
                }

                return $dzien.' '.$koncowka;
            }
        }
    }
    
    public function parseObject($datetime) {
        $tab = explode(' ', $datetime);
        $date = $tab[0];

        if (isset($tab[1]))
            $time = $tab[1];
        else 
            $time = '00:00:00';

        $tab2 = explode('-', $date);
        $this->year = $tab2[0];
        $this->month = $this->months[$tab2[1]];
        $this->month_normal = $this->months_normal[$tab2[1]];
        $this->month_when = $this->months_when[$tab2[1]];
        $this->month_number_with_null = $tab2[1];
        $this->month_number = $tab2[1];
        if ($this->month_number[0] == '0')
            $this->month_number = substr($this->month_number, 1);
        $this->day =  $tab2[2];
        if ($this->day[0] == '0')
            $this->day = substr($this->day, 1);

        if (isset($tab[1])) {
            $tab3 = explode(':', $time);
            $this->hour = $tab3[0];
            $this->min = $tab3[1];
            $this->sec = $tab3[2];
        }
        else {
            $this->hour = '00';
            $this->min = '00';
            $this->sec = '00';
        }
    }

    public function getDaysDiff($date1, $date2) {
        $dateA = new DateTime($date1);
        $dateB = new DateTime($date2);
        
        return $dateB->diff($dateA)->format("%a");
    }
    
    public function generateHumanDevelopString($days, $sex) {
        //return $days. ' ' . $sex;
        
        if ($days > 0 && $days < 29) {
            if ($sex == 'm')
                return 'noworodek - chłopiec';
            else if ($sex == 'k')
                return 'noworodek - dziewczynka';
        } else if ($days > 28 && $days < 366) {
            if ($sex == 'm')
                return 'niemowlę - chłopiec';
            else if ($sex == 'k')
                return 'niemowlę - dziewczynka';
        } else if ($days > 365 && $days < 4749) {
            if ($sex == 'm')
                return 'dziecko - chłopak';
            else if ($sex == 'k')
                return 'dziecko - dziewczyna';
        } else if ($days > 4748 && $days < 6575) {
            if ($sex == 'm')
                return 'nastolatek';
            else if ($sex == 'k')
                return 'nastolatka';
        } else if ($days > 6574 && $days < 12419) {
            if ($sex == 'm')
                return 'mężczyzna';
            else if ($sex == 'k')
                return 'kobieta';
        } else if ($days > 12418 && $days < 23376) {
            if ($sex == 'm')
                return 'dojrzały mężczyzna';
            else if ($sex == 'k')
                return 'dojrzała kobieta';
        } else if ($days > 23375 && $days < 27028) {
            if ($sex == 'm')
                return 'mężczyzna w podeszłym wieku';
            else if ($sex == 'k')
                return 'kobieta w podeszłym wieku';
        } else if ($days > 27027) {
            if ($sex == 'm')
                return 'starzec';
            else if ($sex == 'k')
                return 'staruszka';
        }
        
    }
}

?>