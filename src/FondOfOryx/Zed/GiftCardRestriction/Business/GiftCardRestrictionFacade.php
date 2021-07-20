<?php

namespace FondOfOryx\Zed\GiftCardRestriction\Business;

use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfOryx\Zed\GiftCardRestriction\Business\GiftCardRestrictionBusinessFactory getFactory()
 */
class GiftCardRestrictionFacade extends AbstractFacade implements GiftCardRestrictionFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isBlacklistedCountryDecisionRuleSatisfiedBy(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()->createBlacklistedCountryDecisionRule()->isSatisfiedBy($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isVoucherDiscountDecisionRuleSatisfiedBy(
        GiftCardTransfer $giftCardTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        return $this->getFactory()->createVoucherDiscountDecisionRule()->isSatisfiedBy(
            $giftCardTransfer,
            $quoteTransfer
        );
    }
}