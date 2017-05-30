<?
class SessionMagicData
{

    private $sessionNamespace = 'SessionMagicDataImportant';
    private $sessionControldata = 'SessionControldataImportant';
    private $sessionControlelements = 'SessionControldataElements';
    private static $instance;
    private function __construct()
    {} // Blokujemy domyślny konstruktor publiczny
    private function __clone()
    {} //Uniemożliwia utworzenie kopii obiektu

    public static function create()
    {
        return (self::$instance === null) ? self::$instance = new SessionMagicData() : self::$instance;
    }

    /**
     * [set description]
     * @param [type]  $variable [description]
     * @param string  $value    [description]
     * @param integer $life     qty sec
     * @param integer $maxread  qty read left
     * @param boolean $marge    [description]
     */
    public function set($variable, $value = '', $life = 0, $maxread = 0, $marge = false)
    {
        if ($life > 0) {
            $life = time() + $life;
        }

        if (!isset($_SESSION[$this->sessionNamespace][$variable]) || !$marge) {
            $_SESSION[$this->sessionNamespace][$variable] = $value;
            $_SESSION[$this->sessionControldata][$variable] = array($life, $maxread, 0);
            $_SESSION[$this->sessionControlelements][$variable] = 1;
        } else {
            $this->marge($variable, $value);
        }
    }

    public function marge($variable, $value = '')
    {
        if (isset($_SESSION[$this->sessionNamespace][$variable])) {
            $valueold = $_SESSION[$this->sessionNamespace][$variable];
            $qty = $_SESSION[$this->sessionControlelements][$variable];

            if ($qty == 1) {
                $vartmp = $valueold;
                $valueold = array();
                $valueold[] = $vartmp;
            }

            $valueold[] = $value;
            $_SESSION[$this->sessionNamespace][$variable] = $valueold;
            $_SESSION[$this->sessionControlelements][$variable]++;
        }
    }

    public function qty($variable)
    {
        if (isset($_SESSION[$this->sessionNamespace][$variable])) {
            return $_SESSION[$this->sessionControlelements][$variable];
        }
        return 0;
    }

    public function get($variable)
    {
        if (isset($_SESSION[$this->sessionNamespace][$variable])) {
            $control = $_SESSION[$this->sessionControldata][$variable];
            $canread = true;
            $last = false;

            if ($control[0] > 0) {
                if ($control[0] <= time()) {
                    $canread = false;
                }

            }

            if ($control[1] > 0) {
                if ($control[1] <= $control[2]) {
                    $canread = false;
                } else {
                    if ($control[1] <= $control[2] + 1) {
                        $last = true;
                    }

                    $control[2]++;
                    $_SESSION[$this->sessionControldata][$variable] = $control;
                }
            }

            if ($canread) {
                $wynik = $_SESSION[$this->sessionNamespace][$variable];
                if ($last) {
                    $this->drop($variable);
                }

                return $wynik;
            } else {
                $this->drop($variable);
            }
        }
        return null;
    }

    public function drop($variable)
    {
        unset($_SESSION[$this->sessionNamespace][$variable]);
        unset($_SESSION[$this->sessionControldata][$variable]);
        unset($_SESSION[$this->sessionControlelements][$variable]);
    }

    public function debug()
    {

    }

}
