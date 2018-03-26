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
use Rapido\Response\Tracking;

class TrackingParcelResponse extends AbstractResponse
{

    /**
     * @return TrackingBag
     */
    public function getData()
    {
        $result = new TrackingBag();
        if(!is_null($this->getCode())) {
            return $result;
        }

        if(!empty($this->data)) {
            /** @var Tracking $track */
            $track = $this->data;
            $result->push([
                'id' => ($id = md5($track->toJson())),
                'name' => $track->getPlace(),
                'events' => new EventBag(),
                'shipment_date' => $track->getDate(),
                'destination_service_area' => new Component(['id' => $id, 'name' => $track->getPlace()])
            ]);
        }
        return $result;
    }

}