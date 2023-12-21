<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ThanhVo\BaiduAnalytics\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class BaConfig
{
    /**
     * Config paths for using throughout the code
     */
    private const XML_PATH_ACTIVE = 'baidu/analytics/active';

    private const XML_PATH_ACCOUNT = 'baidu/analytics/account_id';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Whether BA is ready to use
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isAnalyticsActive($store = null): bool
    {
        $accountId = $this->getAnalyticsAccountID();

        return $accountId && $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $store);
    }

    /**
     * @param null|string|bool|int|Store $store
     * @return mixed
     */
    public function getAnalyticsAccountID($store = null): mixed
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_ACCOUNT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
