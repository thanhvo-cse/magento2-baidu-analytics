<?php
namespace ThanhVo\BaiduAnalytics\Helper;

use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;

/**
 * BaiduAnalytics data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_ACTIVE = 'baidu/analytics/active';

    const XML_PATH_ACCOUNT = 'baidu/analytics/account_id';

    /**
     * Whether BA is ready to use
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isAnalyticsAvailable($store = null): bool
    {
        $accountId = $this->getAnalyticsAccountID();
        return $accountId && $this->scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * @param bool|int|Store|null|string $store
     * @return string|null
     */
    public function getAnalyticsAccountID($store = null): mixed
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ACCOUNT, ScopeInterface::SCOPE_STORE, $store);
    }
}
