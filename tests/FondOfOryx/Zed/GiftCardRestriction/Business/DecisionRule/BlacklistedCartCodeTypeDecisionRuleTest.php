<?php

namespace FondOfOryx\Zed\GiftCardRestriction\Business\DecisionRule;

use ArrayObject;
use Codeception\Test\Unit;
use FondOfOryx\Zed\GiftCardRestriction\Business\Filter\SkuFilterInterface;
use FondOfOryx\Zed\GiftCardRestriction\Dependency\Facade\GiftCardRestrictionToProductCartCodeTypeRestrictionFacadeInterface;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class BlacklistedCartCodeTypeDecisionRuleTest extends Unit
{
    /**
     * @var \FondOfOryx\Zed\GiftCardRestriction\Business\Filter\SkuFilterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $skuFilterMock;

    /**
     * @var \FondOfOryx\Zed\GiftCardRestriction\Dependency\Facade\GiftCardRestrictionToProductCartCodeTypeRestrictionFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productCartCodeTypeRestrictionFacadeMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \Generated\Shared\Transfer\GiftCardTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $giftCardTransferMock;

    /**
     * @var array<\PHPUnit\Framework\MockObject\MockObject>|array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected $itemTransferMocks;

    /**
     * @var \FondOfOryx\Zed\GiftCardRestriction\Business\DecisionRule\BlacklistedCartCodeTypeDecisionRule
     */
    protected $decisionRule;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->skuFilterMock = $this->getMockBuilder(SkuFilterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productCartCodeTypeRestrictionFacadeMock = $this->getMockBuilder(GiftCardRestrictionToProductCartCodeTypeRestrictionFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->giftCardTransferMock = $this->getMockBuilder(GiftCardTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMocks = [
            $this->getMockBuilder(ItemTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $this->getMockBuilder(ItemTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->decisionRule = new BlacklistedCartCodeTypeDecisionRule(
            $this->skuFilterMock,
            $this->productCartCodeTypeRestrictionFacadeMock,
        );
    }

    /**
     * @return void
     */
    public function testIsSatisfiedBy(): void
    {
        $itemTransferMocks = new ArrayObject($this->itemTransferMocks);
        $skus = ['FOO-123-456', 'FOO-234-567'];
        $blacklistedCartCodeTypesPerSku = [
            $skus[0] => ['gift card'],
            $skus[1] => ['gift card'],
        ];

        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn($itemTransferMocks);

        $this->skuFilterMock->expects(static::atLeastOnce())
            ->method('filterFromItems')
            ->with($itemTransferMocks)
            ->willReturn($skus);

        $this->productCartCodeTypeRestrictionFacadeMock->expects(static::atLeastOnce())
            ->method('getBlacklistedCartCodeTypesByProductConcreteSkus')
            ->with($skus)
            ->willReturn($blacklistedCartCodeTypesPerSku);

        static::assertFalse($this->decisionRule->isSatisfiedBy($this->giftCardTransferMock, $this->quoteTransferMock));
    }

    /**
     * @return void
     */
    public function testIsSatisfiedByWithoutRestrictedItems(): void
    {
        $itemTransferMocks = new ArrayObject($this->itemTransferMocks);
        $skus = ['FOO-123-456', 'FOO-234-567'];
        $blacklistedCartCodeTypesPerSku = [
            $skus[0] => [],
            $skus[1] => ['gift card'],
        ];

        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn($itemTransferMocks);

        $this->skuFilterMock->expects(static::atLeastOnce())
            ->method('filterFromItems')
            ->with($itemTransferMocks)
            ->willReturn($skus);

        $this->productCartCodeTypeRestrictionFacadeMock->expects(static::atLeastOnce())
            ->method('getBlacklistedCartCodeTypesByProductConcreteSkus')
            ->with($skus)
            ->willReturn($blacklistedCartCodeTypesPerSku);

        static::assertTrue($this->decisionRule->isSatisfiedBy($this->giftCardTransferMock, $this->quoteTransferMock));
    }

    /**
     * @return void
     */
    public function testIsSatisfiedByWithoutRestrictionDataForOneItem(): void
    {
        $itemTransferMocks = new ArrayObject($this->itemTransferMocks);
        $skus = ['FOO-123-456', 'FOO-234-567'];
        $blacklistedCartCodeTypesPerSku = [
            $skus[0] => ['gift card'],
        ];

        $this->quoteTransferMock->expects(static::atLeastOnce())
            ->method('getItems')
            ->willReturn($itemTransferMocks);

        $this->skuFilterMock->expects(static::atLeastOnce())
            ->method('filterFromItems')
            ->with($itemTransferMocks)
            ->willReturn($skus);

        $this->productCartCodeTypeRestrictionFacadeMock->expects(static::atLeastOnce())
            ->method('getBlacklistedCartCodeTypesByProductConcreteSkus')
            ->with($skus)
            ->willReturn($blacklistedCartCodeTypesPerSku);

        static::assertTrue($this->decisionRule->isSatisfiedBy($this->giftCardTransferMock, $this->quoteTransferMock));
    }
}
