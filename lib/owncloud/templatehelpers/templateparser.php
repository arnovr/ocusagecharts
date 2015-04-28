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

class TemplateParser
{
    private $helper;
    private $templateDto;
    private $translator;

    /**
     * @param ChartViewHelper $helper
     * @param TemplateDto $templateDto
     * @param $translator
     */
    public function __construct(ChartViewHelper $helper, TemplateDto $templateDto, $translator)
    {
        $this->helper = $helper;
        $this->templateDto = $templateDto;
        $this->translator = $translator;
    }

    /**
     * @param string $template
     * @return string
     */
    private function renderTemplate($template)
    {
        $label = $this->helper->getLabel($this->translator);
        $shortLabel = $this->helper->getShortLabel();
        $url = $this->helper->getUrl($this->templateDto->requestToken);
        $title = $this->helper->getTitle($this->translator);
        $template = str_replace('[label]', $label, $template);
        $template = str_replace('[shortlabel]', $shortLabel, $template);
        $template = str_replace('[url]', $url, $template);
        $template = str_replace('[title]', $title, $template);
        $template = str_replace('[charttype]', $this->templateDto->chartType, $template);
        $template = str_replace('[datatype]', $this->templateDto->dataType, $template);
        $template = str_replace('[dateformat]', $this->templateDto->dateFormat, $template);
        return $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->renderTemplate($this->templateDto->template);
    }
}