<?php
namespace OCA\ocUsageCharts\Controller;
use OCA\ocUsageCharts\Service\ChartService;
use OCA\ocUsageCharts\Service\ChartType\C3JS;
use \OCP\AppFramework\Controller;

/**
 * @author    Arno van Rossum <arno@van-rossum.com>
 * @copyright 2014 Arno van Rossum
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 */
class ChartController extends Controller
{
    private $chartService;

    public function __construct()
    {
        $this->chartService = $chartService;
    }

    /**
     * @return array
     */
    public function show()
    {

        return 'x';
        $chartData = array();
        $chartData[] = $this->chartService->loadChart(new C3JS());
        return $chartData;
    }

    public function index()
    {
        return 'x';
        exit;
    }
}