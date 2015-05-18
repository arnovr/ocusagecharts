<?php


namespace OCA\ocUsageCharts\Storage\DataConverters;


use DateTime;
use JsonSerializable;
use OCA\ocUsageCharts\Entity\Storage\StorageUsage;

/**
 * Class FromDateConverter
 * @package OCA\ocUsageCharts\Storage\DataConverters
 */
class FromDateConverter implements DataConverterInterface
{
    /**
     * @var DateTime
     */
    private $fromDate;

    /**
     * @param DateTime $fromDate
     */
    public function __construct(DateTime $fromDate)
    {
        $this->fromDate = $fromDate;
    }
    /**
     * @param [Storage]
     * @return JsonSerializable
     */
    public function convert(array $storageEntities)
    {
        $dataSequences = array(
            'title' => 'Storage Usage last Month',
            'x' => 'date',
            'y' => 'usage',
            'datasequences' => array()
        );

        /* @var StorageUsage $storageUsage */
        foreach($storageEntities as $storageUsage)
        {
            if ( $this->fromDate > $storageUsage->getDate())
            {
                continue;
            }
            $dataSequences['datasequences'][] = $this->formatStorageUsage($storageUsage);
        }
        return json_encode($dataSequences);
    }

    /**
     * @param StorageUsage $storageUsage
     * @return array
     */
    private function formatStorageUsage(StorageUsage $storageUsage)
    {
        $data = array(
            'title' => $storageUsage->getUsername(),
            'date'  => $storageUsage->getDate()->format("Y-m-d"),
            'usage' => $storageUsage->getUsage()
        );
        return $data;
    }
}