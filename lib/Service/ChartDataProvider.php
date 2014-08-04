<?php
namespace OCA\ocUsageCharts\Service;

use OCA\ocUsageCharts\Entity\UsageChartRepository;
/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class ChartDataProvider
{
    /**
     * @var UsageChartRepository
     */
    private $repository;

    public function __construct(UsageChartRepository $repository)
    {
        $this->repository = $repository;
    }
}