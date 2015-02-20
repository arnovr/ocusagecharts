<?php
/**
 * Copyright (c) 2014 - Arno van Rossum <arno@van-rossum.com>
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

namespace OCA\ocUsageCharts\Adapters\c3js\Storage;

use OCA\ocUsageCharts\Adapters\c3js\c3jsBase;
use OCA\ocUsageCharts\Entity\ChartConfig;
use OCA\ocUsageCharts\Owncloud\L10n;
use OCA\ocUsageCharts\Owncloud\User;

/**
 * @author Arno van Rossum <arno@van-rossum.com>
 */
class StorageUsageCurrentAdapter extends c3jsBase
{
    /**
     * @var L10n
     */
    private $translator;

    /**
     * @param ChartConfig $chartConfig
     * @param User $user
     * @param L10n $translator
     */
    public function __construct(ChartConfig $chartConfig, User $user, L10n $translator)
    {
        $this->translator = $translator;
        parent::__construct($chartConfig, $user);
    }

    /**
     * Format the keys from the given data to be translated
     * (should be used and free)
     *
     * @param array $data
     * @return array mixed
     */
    public function formatData($data)
    {
        foreach($data as $key => $value)
        {
            $newKey = $this->translator->t('storage_' . $key);
            $data[$newKey] = $value;
            unset($data[$key]);
        }
        return $data;
    }
}
