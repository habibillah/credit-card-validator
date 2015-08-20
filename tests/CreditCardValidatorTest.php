<?php
namespace Kalicode\Test;

use Kalicode\CreditCardValidator;

class CreditCardValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CreditCardValidator $validator
     */
    private $validator;

    public function setUp()
    {
        $this->validator = new CreditCardValidator();
    }

    public function tearDown()
    {
        $this->validator = null;
    }

    public function testGetCardType()
    {
        $this->assertEquals($this->validator->getCardType('378282246310005'), CreditCardValidator::AMERICAN_EXPRESS);
        $this->assertEquals($this->validator->getCardType('371449635398431'), CreditCardValidator::AMERICAN_EXPRESS);
        $this->assertEquals($this->validator->getCardType('6011111111111117'), CreditCardValidator::DISCOVER);
        $this->assertEquals($this->validator->getCardType('3530111333300000'), CreditCardValidator::JCB);
        $this->assertEquals($this->validator->getCardType('6304000000000000'), CreditCardValidator::MAESTRO);
        $this->assertEquals($this->validator->getCardType('5555555555554444'), CreditCardValidator::MASTERCARD);
        $this->assertEquals($this->validator->getCardType('4005519200000004'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4111111111111111'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4009348888881881'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4012000033330026'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4012000077777777'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4012888888881881'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4217651111111119'), CreditCardValidator::VISA);
        $this->assertEquals($this->validator->getCardType('4500600000000061'), CreditCardValidator::VISA);
    }

    public function testIsValid()
    {
        $this->assertTrue($this->validator->isValid('378282246310005'));
        $this->assertTrue($this->validator->isValid('371449635398431'));
        $this->assertTrue($this->validator->isValid('6011111111111117'));
        $this->assertTrue($this->validator->isValid('3530111333300000'));
        $this->assertTrue($this->validator->isValid('6304000000000000'));
        $this->assertTrue($this->validator->isValid('5555555555554444'));
        $this->assertTrue($this->validator->isValid('4005519200000004'));
        $this->assertTrue($this->validator->isValid('4111111111111111'));
        $this->assertTrue($this->validator->isValid('4009348888881881'));
        $this->assertTrue($this->validator->isValid('4012000033330026'));
        $this->assertTrue($this->validator->isValid('4012000077777777'));
        $this->assertTrue($this->validator->isValid('4012888888881881'));
        $this->assertTrue($this->validator->isValid('4217651111111119'));
        $this->assertTrue($this->validator->isValid('4500600000000061'));
    }
}