<?php
namespace Kalicode;

class CreditCardValidator
{
    /**
     * Detected CCI list
     *
     * @var string
     */
    const ALL              = 'All';
    const AMERICAN_EXPRESS = 'American_Express';
    const UNIONPAY         = 'Unionpay';
    const DINERS_CLUB      = 'Diners_Club';
    const DINERS_CLUB_US   = 'Diners_Club_US';
    const DISCOVER         = 'Discover';
    const JCB              = 'JCB';
    const LASER            = 'Laser';
    const MAESTRO          = 'Maestro';
    const MASTERCARD       = 'Mastercard';
    const SOLO             = 'Solo';
    const VISA             = 'Visa';

    /**
     * List of CCV names
     *
     * @var array
     */
    protected $cardName = [
        0  => self::AMERICAN_EXPRESS,
        1  => self::DINERS_CLUB,
        2  => self::DINERS_CLUB_US,
        3  => self::DISCOVER,
        4  => self::JCB,
        5  => self::LASER,
        6  => self::MAESTRO,
        7  => self::MASTERCARD,
        8  => self::SOLO,
        9  => self::UNIONPAY,
        10 => self::VISA,
    ];

    /**
     * List of allowed CCV lengths
     *
     * @var array
     */
    protected $cardLength = [
        self::AMERICAN_EXPRESS => [15],
        self::DINERS_CLUB      => [14],
        self::DINERS_CLUB_US   => [16],
        self::DISCOVER         => [16],
        self::JCB              => [15, 16],
        self::LASER            => [16, 17, 18, 19],
        self::MAESTRO          => [12, 13, 14, 15, 16, 17, 18, 19],
        self::MASTERCARD       => [16],
        self::SOLO             => [16, 18, 19],
        self::UNIONPAY         => [16, 17, 18, 19],
        self::VISA             => [16],
    ];

    /**
     * List of accepted CCV provider tags
     *
     * @var array
     */
    protected $cardType = [
        self::AMERICAN_EXPRESS => ['34', '37'],
        self::DINERS_CLUB      => ['300', '301', '302', '303', '304', '305', '36'],
        self::DINERS_CLUB_US   => ['54', '55'],
        self::DISCOVER         => ['6011', '622126', '622127', '622128', '622129', '62213',
            '62214', '62215', '62216', '62217', '62218', '62219',
            '6222', '6223', '6224', '6225', '6226', '6227', '6228',
            '62290', '62291', '622920', '622921', '622922', '622923',
            '622924', '622925', '644', '645', '646', '647', '648',
            '649', '65'],
        self::JCB              => ['1800', '2131', '3528', '3529', '353', '354', '355', '356', '357', '358'],
        self::LASER            => ['6304', '6706', '6771', '6709'],
        self::MAESTRO          => ['5018', '5020', '5038', '6304', '6759', '6761', '6762', '6763',
            '6764', '6765', '6766', '6772'],
        self::MASTERCARD       => ['51', '52', '53', '54', '55'],
        self::SOLO             => ['6334', '6767'],
        self::UNIONPAY         => ['622126', '622127', '622128', '622129', '62213', '62214',
            '62215', '62216', '62217', '62218', '62219', '6222', '6223',
            '6224', '6225', '6226', '6227', '6228', '62290', '62291',
            '622920', '622921', '622922', '622923', '622924', '622925'],
        self::VISA             => ['4'],
    ];

    /**
     * Options for this validator
     *
     * @var array
     */
    protected $options = [
        'type'    => [],  // CCIs which are accepted by validation
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setType(self::ALL);
    }

    /**
     * Returns a list of accepted CCIs
     *
     * @return array
     */
    public function getType()
    {
        return $this->options['type'];
    }

    /**
     * Sets CCIs which are accepted by validation
     *
     * @param  string|array $type Type to allow for validation
     * @return CreditCardValidator Provides a fluid interface
     */
    public function setType($type)
    {
        $this->options['type'] = [];
        return $this->addType($type);
    }

    /**
     * Adds a CCI to be accepted by validation
     *
     * @param  string|array $type Type to allow for validation
     * @return CreditCardValidator Provides a fluid interface
     */
    public function addType($type)
    {
        if (is_string($type)) {
            $type = [$type];
        }

        foreach ($type as $typ) {
            if (defined('self::' . strtoupper($typ)) && !in_array($typ, $this->options['type'])) {
                $this->options['type'][] = $typ;
            }

            if (($typ == self::ALL)) {
                $this->options['type'] = array_keys($this->cardLength);
            }
        }

        return $this;
    }

    /**
     * Returns true if and only if $value follows the Luhn algorithm (mod-10 checksum)
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            return false;
        }

        if (!ctype_digit($value)) {
            return false;
        }

        $length = strlen($value);
        $types  = $this->getType();
        $foundp = false;
        $foundl = false;
        foreach ($types as $type) {
            foreach ($this->cardType[$type] as $prefix) {
                if (substr($value, 0, strlen($prefix)) == $prefix) {
                    $foundp = true;
                    if (in_array($length, $this->cardLength[$type])) {
                        $foundl = true;
                        break 2;
                    }
                }
            }
        }

        if ($foundp == false) {
            return false;
        }

        if ($foundl == false) {
            return false;
        }

        $sum    = 0;
        $weight = 2;

        for ($i = $length - 2; $i >= 0; $i--) {
            $digit = $weight * $value[$i];
            $sum += floor($digit / 10) + $digit % 10;
            $weight = $weight % 2 + 1;
        }

        if ((10 - $sum % 10) % 10 != $value[$length - 1]) {
            return false;
        }

        return true;
    }

    /**
     * Returns string of card type
     *
     * @param  string $value
     * @return string | false
     */
    public function getCardType($value)
    {
        if (!is_string($value)) {
            return false;
        }

        if (!ctype_digit($value)) {
            return false;
        }

        $types  = $this->getType();
        $foundp = false;
        foreach ($types as $type) {
            foreach ($this->cardType[$type] as $prefix) {
                if (substr($value, 0, strlen($prefix)) == $prefix) {
                    $foundp = $type;
                    break;
                }
            }
        }

        return $foundp;
    }
}
