<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Rapido\Http;

use Omniship\Common\Component;
use Omniship\Common\EventBag;
use Omniship\Common\TrackingBag;
use Omniship\Common\TrackingMultipleBag;
use Rapido\Response\Tracking;

class TrackingParcelsResponse extends AbstractResponse
{

    /**
     * @return TrackingMultipleBag
     */
    public function getData()
    {
        $results = new TrackingMultipleBag();
        if(!is_null($this->getCode())) {
            return $results;
        }

        if(is_array($this->data)) {
            /** @var Tracking $track */
            foreach ($this->data AS $bol_id => $track) {
                $result = new TrackingBag();
                $result->push([
                    'id' => md5($track->toJson()),
                    'name' => $track->getPlace(),
                    'events' => new EventBag(),
                    'shipment_date' => $track->getDate(),
                    'destination_service_area' => new Component(['id' => md5($track->toJson()), 'name' => $track->getPlace()])
                ]);
                $results->put($bol_id, $result);
            }
        }

        return $results;
    }

}