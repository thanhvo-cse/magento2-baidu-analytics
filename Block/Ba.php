<?php
namespace ThanhVo\BaiduAnalytics\Block;

use Magento\Cookie\Helper\Cookie;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template\Context;
use ThanhVo\BaiduAnalytics\Model\Config\BaConfig;
use Magento\Sales\Api\OrderRepositoryInterface;

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
     * @var \Magento\Cookie\Helper\Cookie
     */
    protected $cookieHelper;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

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

    public function getAnalyticsData()
    {
        $analyticData = [
            'isCookieRestrictionModeEnabled' => $this->isCookieRestrictionModeEnabled(),
            'currentWebsite' => $this->getCurrentWebsiteId(),
            'cookieName' => Cookie::IS_USER_ALLOWED_SAVE_COOKIE,
            'analyticsActive' => $this->baConfig->isAnalyticsActive(),
            'pageTrackingData' => $this->getPageTrackingData(),
            'ordersTrackingData' => $this->getOrdersTrackingData(),
        ];

        return $this->serializer->serialize($analyticData);
    }

    /**
     * @return array
     */
    protected function getPageTrackingData(): array
    {
        return [
            'optPageUrl' => $this->getOptPageUrl(),
            'accountID' => $this->baConfig->getAnalyticsAccountID()
        ];
    }
}
