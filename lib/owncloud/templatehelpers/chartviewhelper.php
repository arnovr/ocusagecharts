<?php
/**
 * Copyright (c) 2015 - Arno van Rossum <arno@van-rossum.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace OCA\ocUsageCharts\Owncloud\TemplateHelpers;

use OCA\ocUsageCharts\Entity\ChartConfig;


class ChartViewHelper
{
    /**
     * @var ChartConfig
     */
    private $chart;

    /**
     * @param ChartConfig $chart
     */
    public function __construct(ChartConfig $chart)
    {
        $this->chart = $chart;
    }

    /**
     * @param $requestToken
     * @return string
     */
    public function getUrl($requestToken)
    {
        if ( !$requestToken )
        {
            throw new \RuntimeException('No request token given');
        }
        return \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $this->chart->getId(), 'requesttoken' => $requestToken));
    }

    /**
     * @param $language
     * @return string
     */
    public function getTitle($language)
    {
        return $language->t($this->chart->getChartType());
    }

    /**
     * @return string
     */
    public function getShortLabel()
    {
        if ( strpos($this->chart->getChartType(), 'Storage') !== false ) {
            $meta = json_decode($this->chart->getMetaData());
            if (!empty($meta) && $meta->size) {
                return $meta->size;
            }
            return 'gb';
        }
        return '';
    }

    /**
     * @param $language
     * @return string
     */
    public function getLabel($language)
    {
        if ( strpos($this->chart->getChartType(), 'Storage') !== false ) {
            $meta = json_decode($this->chart->getMetaData());
            if (!empty($meta) && $meta->size) {
                return $language->t('sizes_' . $meta->size);
            }
            return $language->t('sizes_gb');
        }
        return $language->t('activities');
    }

}
