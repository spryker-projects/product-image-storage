<?php

namespace Functional\SprykerFeature\Zed\Tax;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Tax\Business\TaxFacade;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRateQuery;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetQuery;

/**
 * @group TaxTest
 */
class WriterTest extends Test
{

    const DUMMY_TAX_SET_NAME = 'SalesTax';
    const DUMMY_TAX_RATE1_NAME = 'Local';
    const DUMMY_TAX_RATE1_PERCENTAGE = 2.5;
    const DUMMY_TAX_RATE2_NAME = 'Regional';
    const DUMMY_TAX_RATE2_PERCENTAGE = 10;

    /**
     * @var TaxFacade
     */
    private $taxFacade;

    /**
     * @var AutoCompletion $locator
     */
    protected $locator;

    public function setUp()
    {
        parent::setUp();

        $this->locator = Locator::getInstance();
        $this->taxFacade = new TaxFacade(new Factory('Tax'), $this->locator);
    }

    private function createTaxRateTransfer()
    {
        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE1_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE1_PERCENTAGE);

        return $taxRateTransfer;
    }

    private function createTaxSetTransfer()
    {
        $taxSetTransfer = new TaxSetTransfer();
        $taxSetTransfer->setName(self::DUMMY_TAX_SET_NAME);

        return $taxSetTransfer;
    }

    public function testCreateTaxRate()
    {
        $id = $this->taxFacade->createTaxRate($this->createTaxRateTransfer());

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertEquals(self::DUMMY_TAX_RATE1_PERCENTAGE, $taxRateQuery->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE1_NAME, $taxRateQuery->getName());
    }

    public function testCreateTaxSetWithNewTaxRate()
    {
        $taxSetTransfer = $this->createTaxSetTransfer();

        $taxSetTransfer->addTaxRate($this->createTaxRateTransfer());

        $id = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($id)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertEquals(self::DUMMY_TAX_SET_NAME, $taxSetQuery->getName());
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testCreateTaxSetWithExistingTaxRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $id = $this->taxFacade->createTaxRate($taxRateTransfer);
        $taxRateTransfer->setIdTaxRate($id);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $id = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($id)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertNotEmpty($taxSetQuery->getSpyTaxRates());
    }

    public function testUpdateTaxRate()
    {
        $id = $this->taxFacade->createTaxRate($this->createTaxRateTransfer());

        $taxRateTransfer = new TaxRateTransfer();
        $taxRateTransfer->setIdTaxRate($id);
        $taxRateTransfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRateTransfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->updateTaxRate($taxRateTransfer);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();

        $this->assertNotEmpty($taxRateQuery);
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxRateQuery->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE2_NAME, $taxRateQuery->getName());
    }

    public function testUpdateTaxSet()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->setIdTaxSet($taxSetId)->setName('Foobar');
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $this->taxFacade->updateTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertEquals('Foobar', $taxSetQuery->getName());
        $this->assertCount(1, $taxSetQuery->getSpyTaxRates());
        $taxRateEntity = $taxSetQuery->getSpyTaxRates()[0];
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxRateEntity->getRate());
        $this->assertEquals(self::DUMMY_TAX_RATE2_NAME, $taxRateEntity->getName());
    }

    public function testAddTaxRateToTaxSet()
    {
        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($this->createTaxRateTransfer());
        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);

        $this->taxFacade->addTaxRateToTaxSet($taxSetId, $taxRate2Transfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();

        $this->assertNotEmpty($taxSetQuery);
        $this->assertCount(2, $taxSetQuery->getSpyTaxRates());
        $this->assertEquals(self::DUMMY_TAX_RATE2_PERCENTAGE, $taxSetQuery->getSpyTaxRates()[1]->getRate());
    }

    public function testRemoveTaxRateFromTaxSet()
    {
        $taxRate1Transfer = $this->createTaxRateTransfer();
        $rate1Id = $this->taxFacade->createTaxRate($taxRate1Transfer);
        $taxRate1Transfer->setIdTaxRate($rate1Id);
        $taxRate2Transfer = new TaxRateTransfer();
        $taxRate2Transfer->setName(self::DUMMY_TAX_RATE2_NAME);
        $taxRate2Transfer->setRate(self::DUMMY_TAX_RATE2_PERCENTAGE);
        $rate2Id = $this->taxFacade->createTaxRate($taxRate2Transfer);
        $taxRate2Transfer->setIdTaxRate($rate2Id);

        $taxSetTransfer = $this->createTaxSetTransfer();

        $taxSetTransfer->addTaxRate($taxRate1Transfer);
        $taxSetTransfer->addTaxRate($taxRate2Transfer);

        $taxSetId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();
        $this->assertCount(2, $taxSetQuery->getSpyTaxRates());

        $this->taxFacade->removeTaxRateFromTaxSet($taxSetId, $rate2Id);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($taxSetId)->findOne();
        $this->assertCount(1, $taxSetQuery->getSpyTaxRates());
        $this->assertEquals($rate1Id, $taxSetQuery->getSpyTaxRates()[0]->getIdTaxRate());
    }

    public function testExceptionRaisedIfAttemptingToCreateTaxSetWithoutAnyTaxRates()
    {
        $this->setExpectedException('SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException');

        $this->taxFacade->createTaxSet($this->createTaxSetTransfer());
    }

    public function testExceptionRaisedIfAttemptingToUpdateNonExistentTaxRate()
    {
        $this->setExpectedException('SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException');

        $taxRateTransfer = $this->createTaxRateTransfer();
        $taxRateTransfer->setIdTaxRate(9999999999);

        $this->taxFacade->updateTaxRate($taxRateTransfer);
    }

    public function testExceptionRaisedIfAttemptingToRemoveTaxRateFromTaxSetWithSingleTaxRate()
    {
        $this->setExpectedException('SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException');

        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer);
        $taxRateTransfer->setIdTaxRate($rateId);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $setId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $this->taxFacade->removeTaxRateFromTaxSet($setId, $rateId);
    }

    public function testDeleteTaxRate()
    {
        $id = $this->taxFacade->createTaxRate($this->createTaxRateTransfer());

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $this->taxFacade->deleteTaxRate($id);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($id)->findOne();
        $this->assertEmpty($taxRateQuery);
    }

    public function testDeleteTaxSetShouldDeleteSetButNotTheAssociatedRate()
    {
        $taxRateTransfer = $this->createTaxRateTransfer();
        $rateId = $this->taxFacade->createTaxRate($taxRateTransfer);
        $taxRateTransfer->setIdTaxRate($rateId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $taxSetTransfer = $this->createTaxSetTransfer();
        $taxSetTransfer->addTaxRate($taxRateTransfer);
        $setId = $this->taxFacade->createTaxSet($taxSetTransfer);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId)->findOne();
        $this->assertNotEmpty($taxSetQuery);

        $this->taxFacade->deleteTaxSet($setId);

        $taxRateQuery = SpyTaxRateQuery::create()->filterByIdTaxRate($rateId)->findOne();
        $this->assertNotEmpty($taxRateQuery);

        $taxSetQuery = SpyTaxSetQuery::create()->filterByIdTaxSet($setId)->findOne();
        $this->assertEmpty($taxSetQuery);
    }
}