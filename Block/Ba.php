<?php
namespace ThanhVo\BaiduAnalytics\Block;

use Magento\Framework\App\ObjectManager;

/**
 * BaiduAnalytics Page Block
 *
 * @api
 * @since 100.0.2
 */
class Ba extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \ThanhVo\BaiduAnalytics\Helper\Data
     */
    protected $baiduAnalyticsData = null;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $salesOrderCollection;

    /**
     * @var \Magento\Cookie\Helper\Cookie
     */
    private $cookieHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection
     * @param \ThanhVo\BaiduAnalytics\Helper\Data $baiduAnalyticsData
     * @param array $data
     * @param \Magento\Cookie\Helper\Cookie|null $cookieHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \ThanhVo\BaiduAnalytics\Helper\Data $baiduAnalyticsData,
        array $data = [],
        \Magento\Cookie\Helper\Cookie $cookieHelper = null
    ) {
        $this->baiduAnalyticsData = $baiduAnalyticsData;
        $this->salesOrderCollection = $salesOrderCollection;
        $this->cookieHelper = $cookieHelper ?: ObjectManager::getInstance()->get(\Magento\Cookie\Helper\Cookie::class);
        parent::__construct($context, $data);
    }

    /**
     * @return \ThanhVo\BaiduAnalytics\Helper\Data
     */
    public function getHelper(): \ThanhVo\BaiduAnalytics\Helper\Data
    {
        return $this->baiduAnalyticsData;
    }

    /**
     * Get config
     *
     * @param string $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get a specific page name (may be customized via layout)
     *
     * @return string|null
     */
    public function getPageName()
    {
        return $this->_getData('page_name');
    }

    /**
     * Return cookie restriction mode value.
     *
     * @return bool
     */
    public function isCookieRestrictionModeEnabled(): bool
    {
        return $this->cookieHelper->isCookieRestrictionModeEnabled();
    }

    /**
     * Return current website id.
     *
     * @return int
     */
    public function getCurrentWebsiteId(): int
    {
        return $this->_storeManager->getWebsite()->getId();
    }

    /**
     * @return array
     */
    public function getPageTrackingData(): array
    {
        return [
            'accountID' => $this->escapeHtmlAttr($this->baiduAnalyticsData->getAnalyticsAccountID(), false)
        ];
    }

    /**
     * Return page url for tracking.
     *
     * @return string
     */
    private function getOptPageUrl()
    {
        $optPageURL = '';
        $pageName = $this->getPageName() !== null ? trim($this->getPageName()) : '';
        if ($pageName && substr($pageName, 0, 1) === '/' && strlen($pageName) > 1) {
            $optPageURL = ", '" . $this->escapeHtmlAttr($pageName, false) . "'";
        }
        return $optPageURL;
    }
}
