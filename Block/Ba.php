<?php
namespace ThanhVo\BaiduAnalytics\Block;

use Magento\Cookie\Helper\Cookie;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\GoogleGtag\Model\Config\GtagConfig as GtagConfiguration;
use Magento\Sales\Api\OrderRepositoryInterface;
use ThanhVo\BaiduAnalytics\Model\Config\BaConfig;

/**
 * BaiduAnalytics Page Block
 *
 * @api
 * @since 100.0.2
 */
class Ba extends \Magento\GoogleGtag\Block\Ga
{
    /**
     * @var BaConfig
     */
    private $baConfig;

    /**
     * @var Cookie
     */
    private $cookieHelper;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param Context $context
     * @param GtagConfiguration $googleGtagConfig
     * @param BaConfig $baConfig
     * @param Cookie $cookieHelper
     * @param SerializerInterface $serializer
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        GtagConfiguration $googleGtagConfig,
        BaConfig $baConfig,
        Cookie $cookieHelper,
        SerializerInterface $serializer,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->baConfig = $baConfig;
        $this->cookieHelper = $cookieHelper;
        $this->serializer = $serializer;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context, $googleGtagConfig, $cookieHelper, $serializer, $searchCriteriaBuilder, $orderRepository, $data);
    }

    /**
     * @return bool|string
     */
    public function getAnalyticsData()
    {
        $analyticData = [
            'isCookieRestrictionModeEnabled' => $this->isCookieRestrictionModeEnabled(),
            'currentWebsite' => $this->getCurrentWebsiteId(),
            'cookieName' => Cookie::IS_USER_ALLOWED_SAVE_COOKIE,
            'analyticsActive' => $this->baConfig->isAnalyticsActive(),
            'pageTrackingData' => $this->getPageTrackingData($this->baConfig->getAnalyticsAccountID()),
            'ordersTrackingData' => $this->getOrdersTrackingData(),
        ];

        return $this->serializer->serialize($analyticData);
    }

    /**
     * @param $accountId
     * @return array
     */
    public function getPageTrackingData($accountId): array
    {
        return [
            'optPageUrl' => $this->getOptPageUrl(),
            'accountID' => $accountId
        ];
    }
}
