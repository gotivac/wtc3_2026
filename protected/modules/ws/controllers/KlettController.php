<?php
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('max_execution_time', 0);

class KlettController extends CController
{

    public $action;

    public function actions()
    {
        return array(
            'wtc-ws' => array(
                'class' => 'CWebServiceAction',
                'serviceOptions' => array(
                    'soapVersion' => '1.2',

                    'generatorConfig' => array(
                        'class' => 'CWsdlGenerator',
                        /*                        'operationBodyStyle'=>array('use'=>'literal'),
                                                'bindingStyle'=>'document',
                        */
                    ),


                ),
                //'wsdlUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/wsdl/Schema.wsdl',
                'wsdlUrl' => 'https://wtc3.schenker.co.rs/wsdl/Schema.wsdl',
                // 'wsdlUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/ws/klett/wtc-ws/1/?wsdl',


            ),
        );
    }

    /**
     * @param string $message
     * @return string
     * @soap
     **/
    public function TestService(string $message)
    {
        $this->appendToLog($message);
        return $message;
    }


    /*** FUCTION THAT LOGS ALL REQUESTS ***/

    public function appendToLog($message, $partnerID = false)
    {
        $log_path = dirname(__FILE__) . '/../log/KlettSendOrder.log';
        $log = fopen($log_path, 'a');
        fwrite($log, '*** ' . date('Y-m-d H:i:s') . ' *** IP: ' . $_SERVER['REMOTE_ADDR'] . ' *** PARTNER ID: ' . $partnerID . "\n\n");
        fwrite($log, $message);
        fwrite($log, "\n\n*********************************** END ***************************************\n\n\n");
        return fclose($log);

    }

    /**
     * @param string $loadlistNo
     * @param string $orderYear
     * @param string $partnerID
     * @param string $warehouseID
     * @return object GetOrderResultResult
     * @soap
     **/
    public function GetOrderResult(string $loadlistNo, string $orderYear, string $partnerID, string $warehouseID)
    {

        $orders_klett = OrderKlett::model()->findAllByAttributes(array(
            'LoadListNo' => $loadlistNo,
            'OrderYear' => $orderYear,/*
            'SenderID' => $partnerID,
            'WarehouseID' => $warehouseID*/
        ));


        if (count($orders_klett) == 0) {
            $error = array(
                'code_text' => 'M003',
                'message_text' => 'Ne postoji nalog sa datom identifikacijom!',
            );
            $domtree = $this->GetOrderResultError($error, $loadlistNo, $orderYear, $partnerID, $warehouseID);

            $result = new GetOrderResultResult();
            $result->GetOrderResultResult = $domtree->saveXML();
            header('Content-type: text/xml');
            return $result;
            Yii::app()->end();
        }

        $web_orders = WebOrder::model()->findAllByAttributes(array('load_list' => $loadlistNo));
        $order_request = OrderRequest::model()->findByAttributes(array('load_list' => $loadlistNo));

        if ($order_request == null && count($web_orders) == 0) {
            $error = array(
                'code_text' => 'M003',
                'message_text' => 'Ne postoji nalog sa datom identifikacijom!',
            );
            $domtree = $this->GetOrderResultError($error, $loadlistNo, $orderYear, $partnerID, $warehouseID);

            $result = new GetOrderResultResult();
            $result->GetOrderResultResult = $domtree->saveXML();
            header('Content-type: text/xml');
            return $result;
            Yii::app()->end();
        }


        foreach ($web_orders as $web_order) {
            if ($web_order->status == 0) {
                $error = array(
                    'code_text' => 'M002',
                    'message_text' => 'Ne postoji spreman izveštaj za traženi nalog!',
                );
                $domtree = $this->GetOrderResultError($error, $loadlistNo, $orderYear, $partnerID, $warehouseID);

                $result = new GetOrderResultResult();
                $result->GetOrderResultResult = $domtree->saveXML();
                header('Content-type: text/xml');
                return $result;
                Yii::app()->end();
            }
        }


        $elements = array();

        foreach ($web_orders as $web_order) {
            $order_klett = $web_order->orderKlett;


            $order_items = array();


            foreach ($order_klett->orderKlettItems as $klett_item) {
                $real_quantity = 0;
                foreach ($web_order->webOrderProducts as $web_order_product) {


                    $product = Product::model()->findByPk($web_order_product->product_id);
                    if ($product->product_barcode == $klett_item->Barcode) {


                        $pick_web = PickWeb::model()->findByAttributes(array('web_order_id'=>$web_order->id,'web_order_product_id'=>$web_order_product->id,'product_barcode'=>$klett_item->Barcode));

                        if ($pick_web != null) {
                            $real_quantity = $pick_web->quantity;
                        }

                    }

                }
                $order_items[] = array(
                    'Product' => array(
                        'ProductCode' => $klett_item->ProductCode,
                        'ProductName' => $klett_item->ProductName,
                        'ProductDescr' => $klett_item->ProductDescr,
                        'Barcode' => $klett_item->Barcode,
                        'QttOnPalett' => $klett_item->QttOnPalett,
                        'QttInPack' => $klett_item->QttInPack,
                        'PackBarcode' => $klett_item->PackBarcode,
                        'PalletBarcode' => $klett_item->PalletBarcode,
                        'UnitWeight' => $klett_item->UnitWeight
                    ),
                    'Quantity' => $klett_item->Quantity,
                    'RealQuantity' => number_format($real_quantity, 2,".",""),
                    'MsrUnit' => $klett_item->MsrUnit,
                    'DamagedQuantity' => '',
                    'DamageDescr' => '',
                    'PalettCount' => 0,
                    'PackCount' => 0,
                    'ItemWeight' => number_format($real_quantity * $klett_item->UnitWeight, 2,".",""),


                );
            }


            $elements[] = array(
                'order_header' => array(
                    'OrderNo' => substr($web_order->order_number,0,13),
                    'OrderYear' => $orderYear,
                    'LoadListNo' => $loadlistNo
                ),
                'order_body' => array(
                    'OrderDate' => $order_klett->OrderDate,
                    'OrderTypeID' => $order_klett->OrderTypeID,
                    'WMSOrderNo' => $order_klett->WMSOrderNo,
                    'RefInvNo' => '',
                    'IsSum' => $order_klett->IsSum,
                    'StockFrom' => $order_klett->StockFrom,
                    'StockTo' => $order_klett->StockTo,
                    'OrderStatusID' => $web_order->status == 1 ? '07' : '02',
                    'VehicleData' => $order_klett->VehicleData,
                    'DriverData' => $order_klett->DriverData,
                    'PalettCount' => 0,
                    'PackCount' => 1,
                    'OrderWeight' => $web_order->totalWeight,
                    'IsWeb' => $order_klett->IsWeb,
                    'DeliveryType' => $order_klett->DeliveryType,
                    'IsUrgent' => $order_klett->IsUrgent,
                    'CVParty' => json_decode($order_klett->CVParty, true),
                    'ShipToParty' => json_decode($order_klett->ShipToParty, true),


                ),
                'order_items' => $order_items
            );
        }
        if ($order_request !== null) {
            $activity = Activity::model()->findByAttributes(array('order_request_id' => $order_request->id));
            if ($activity != null) {
                if ($activity->system_acceptance_datetime == null) {
                    $error = array(
                        'code_text' => 'M002',
                        'message_text' => 'Ne postoji spreman izveštaj za traženi nalog!',
                    );
                    $domtree = $this->GetOrderResultError($error, $loadlistNo, $orderYear, $partnerID, $warehouseID);

                    $result = new GetOrderResultResult();
                    $result->GetOrderResultResult = $domtree->saveXML();
                    header('Content-type: text/xml');
                    return $result;
                    Yii::app()->end();
                }

                foreach ($order_request->orderClients as $order_client) {
                    $order_klett = OrderKlett::model()->findByAttributes(array('order_main_id' => $order_client->id));
                    $activity_order = ActivityOrder::model()->findByAttributes(array('order_client_id' => $order_client->id));


                    $order_items = array();


                    foreach ($order_klett->orderKlettItems as $klett_item) {


                        $real_quantity = 0;
                        $palett_count = 0;
                        $pack_count = 0;
                        foreach ($activity_order->activityPaletts as $activity_palett) {
                            foreach ($activity_palett->hasProducts as $has_product) {
                                if (strtoupper($has_product->product_barcode) == strtoupper($klett_item->Barcode)) {
                                    $real_quantity += $has_product->quantity;
                                    $pack_count += $has_product->packages;
                                    $palett_count++;
                                }
                            }
                        }




                        $order_items[] = array(
                            'Product' => array(
                                'ProductCode' => $klett_item->ProductCode,
                                'ProductName' => $klett_item->ProductName,
                                'ProductDesct' => $klett_item->ProductDescr,
                                'Barcode' => $klett_item->Barcode,
                                'QttOnPalett' => $klett_item->QttOnPalett,
                                'QttInPack' => $klett_item->QttInPack,
                                'PackBarcode' => $klett_item->PackBarcode,
                                'PalletBarcode' => $klett_item->PalletBarcode,
                                'UnitWeight' => $klett_item->UnitWeight
                            ),
                            'Quantity' => $klett_item->Quantity,
                            'RealQuantity' => number_format($real_quantity, 2,".",""),
                            'MsrUnit' => $klett_item->MsrUnit,
                            'DamagedQuantity' => '',
                            'DamageDescr' => '',
                            'PalettCount' => $palett_count,
                            'PackCount' => 0,
                            'ItemWeight' => number_format($real_quantity * $klett_item->UnitWeight, 2,".",""),

                        );
                    }

                    $elements[] = array(
                        'order_header' => array(
                            'OrderNo' => substr($order_client->order_number,0,13),
                            'OrderYear' => $orderYear,
                            'LoadListNo' => $loadlistNo
                        ),
                        'order_body' => array(
                            'OrderDate' => $order_klett->OrderDate,
                            'OrderTypeID' => $order_klett->OrderTypeID,
                            'WMSOrderNo' => $activity_order->id,
                            'RefInvNo' => '',
                            'IsSum' => $order_klett->IsSum,
                            'StockFrom' => $order_klett->StockFrom,
                            'StockTo' => $order_klett->StockTo,
                            'OrderStatusID' => $activity->direction == 'in' ? ($activity->system_acceptance_datetime != NULL ? '07' : '02') : ($activity->truck_dispatch_time != null ? '07' : '02'),
                            'VehicleData' => $activity->license_plate,
                            'DriverData' => $activity->driver_data,
                            'PalettCount' => count($activity_order->activityPaletts),
                            'PackCount' => $activity_order->totalPickedPackages,
                            'OrderWeight' => $activity_order->totalWeight,
                            'IsWeb' => $order_klett->IsWeb,
                            'DeliveryType' => $order_klett->DeliveryType,
                            'IsUrgent' => $order_klett->IsUrgent,
                            'CVParty' => json_decode($order_klett->CVParty, true),
                            'ShipToParty' => json_decode($order_klett->ShipToParty, true),

                        ),
                        'order_items' => $order_items
                    );


                }
            } else {
                $error = array(
                    'code_text' => 'M002',
                    'message_text' => 'Ne postoji spreman izveštaj za traženi nalog!',
                );
                $domtree = $this->GetOrderResultError($error, $loadlistNo, $orderYear, $partnerID, $warehouseID);

                $result = new GetOrderResultResult();
                $result->GetOrderResultResult = $domtree->saveXML();
                header('Content-type: text/xml');
                return $result;
                Yii::app()->end();
            }

        }


        $domtree = new DOMDocument('1.0', 'UTF-8');


        $xmlRoot = $domtree->createElement("DocMessage");
        $docMessage = $domtree->appendChild($xmlRoot);
        $header = $domtree->createElement('Header');
        $docMessage->appendChild($header);

        $senderID = $domtree->createElement('SenderID', '103239684');
        $header->appendChild($senderID);
        $senderName = $domtree->createElement('SenderName', 'KLETT');
        $header->appendChild($senderName);
        $recipientID = $domtree->createElement('RecipientID', $partnerID);
        $header->appendChild($recipientID);
        $warehouseID = $domtree->createElement('WarehouseID', $warehouseID);
        $header->appendChild($warehouseID);

        $body = $domtree->createElement('Body');
        $docMessage->appendChild($body);

        foreach ($elements as $element) {
            $warehouseOrder = $domtree->createElement('WarehouseOrder');
            $body->appendChild($warehouseOrder);
            $orderHeader = $domtree->createElement('OrderHeader');
            foreach ($element['order_header'] as $item => $value) {
                $orderHeader->setAttribute($item, $value);
            }


            foreach ($element['order_body'] as $item => $value) {
                if (is_array($value)) {
                    $tmp_item = $domtree->createElement($item);
                    $orderHeader->appendChild($tmp_item);
                    foreach ($value as $k => $v) {
                        if (is_array($v)) {
                            $v = '';
                        }
                        $t_item = $domtree->createElement($k, $v);
                        $tmp_item->appendChild($t_item);
                    }

                } else {
                    $tmp_item = $domtree->createElement($item, $value);
                    $orderHeader->appendChild($tmp_item);
                }
            }
            $warehouseOrder->appendChild($orderHeader);

            foreach ($element['order_items'] as $cnt => $item) {
                $orderItem = $domtree->createElement('OrderItem');
                $orderItem->setAttribute('ItemNo', $cnt + 1);

                foreach ($item as $key => $val) {
                    if (is_array($val)) {
                        $section = $domtree->createElement($key);
                        $orderItem->appendChild($section);
                        foreach ($val as $k => $v) {
                            $tmp_item = $domtree->createElement($k, $v);
                            $section->appendChild($tmp_item);
                        }
                        $orderItem->appendChild($section);

                    } else {
                        $tmp_item = $domtree->createElement($key, $val);
                        $orderItem->appendChild($tmp_item);
                    }
                }
                $warehouseOrder->appendChild($orderItem);

            }


        }


        $result = new GetOrderResultResult();
        $result->GetOrderResultResult = $domtree->saveXML();
        header('Content-type: text/xml');
        return $result;


    }

    private function GetOrderResultError(array $error, string $loadlistNo, string $orderYear, string $partnerID, string $warehouseID)
    {
        $domtree = new DOMDocument('1.0', 'UTF-8');


        $xmlRoot = $domtree->createElement("DocMessage");
        $docMessage = $domtree->appendChild($xmlRoot);
        $header = $domtree->createElement('Header');
        $docMessage->appendChild($header);

        $senderID = $domtree->createElement('SenderID', 'DBSCHENKER');
        $header->appendChild($senderID);
        $senderName = $domtree->createElement('SenderName', '');
        $header->appendChild($senderName);

        $body = $domtree->createElement('Body');
        $docMessage->appendChild($body);
        $responseMessage = $domtree->createElement('ResponseMessage');
        $body->appendChild($responseMessage);
        $result = $domtree->createElement('Result');
        $result->setAttribute('LoadListNo', $loadlistNo);
        $result->setAttribute('OrderYear', $orderYear);
        $result->setAttribute('WarehouseID', $warehouseID);
        $responseMessage->appendChild($result);
        $code = $domtree->createElement('Code', $error['code_text']);
        $result->appendChild($code);
        $message = $domtree->createElement('Message', $error['message_text']);
        $result->appendChild($message);


        return $domtree;

    }

    /**
     * @param string $docMessage
     * @param string $partnerID
     * @return object SendOrderResult
     * @soap
     **/
    public function SendOrder(string $docMessage, string $partnerID)
    {


        $this->appendToLog($docMessage, $partnerID);

        @$xml = new SimpleXMLElement($docMessage);


        /*** Validacija naspram xsd fajla, trenutni je neispravan! ****
         *
         * $xsd = dirname(__FILE__).'/../schema/Klett/WMSDocMessage.xsd';
         *
         * $dom_xml = new DOMDocument();
         * $dom_xml->loadXML($docMessage);
         *
         *
         * echo $dom_xml->schemaValidate($xsd);
         * die();
         */


        /*** Ovaj deo je primer kako se cita DocMessage
         *
         *
         * echo 'Order number: ' . $xml->Body->WarehouseOrder->OrderHeader['OrderNo'] . "\n";
         * echo 'Order year: ' . $xml->Body->WarehouseOrder->OrderHeader['OrderYear']. "\n";
         * echo 'Order date: ' . $xml->Body->WarehouseOrder->OrderHeader->OrderDate . "\n";
         * foreach($xml->Body->WarehouseOrder->OrderItem as $item) {
         * echo 'Order item number: ' . $item['ItemNo'] . " - Product Code: ";
         * echo $item->Product->ProductCode ."\n";
         * }
         *
         * die();
         *
         *
         */

        $array = json_decode(json_encode((array)$xml), TRUE);

        $header = (array)$xml->Header;

        $client = Client::model()->findByAttributes(array('tax_number' => $header['SenderID']));

        if ($client === null) {

            $code_text = '9999';
            $message_text = 'Klijent nepoznat.';

            $domtree = new DOMDocument('1.0', 'UTF-8');

            $xmlRoot = $domtree->createElement("ResponseMessage");
            $responseMessage = $domtree->appendChild($xmlRoot);
            $result = $domtree->createElement('Result');
            $result->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
            $result->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
            $result->setAttribute('WarehouseID', $xml->Header->WarehouseID);
            $responseMessage->appendChild($result);
            $code = $domtree->createElement('Code', $code_text);
            $result->appendChild($code);
            $message = $domtree->createElement('Message', $message_text);
            $result->appendChild($message);
            $result = new SendOrderResult();
            $result->SendOrderResult = $domtree->saveXML();
            header('Content-type: text/xml');
            return $result;
        }

        /**  search existing in normal orders */
        $existing = OrderRequest::model()->findByAttributes(array('load_list' => $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']));

        /** if not found, search existing in web orders */
        if ($existing === null) {
            $existing = WebOrder::model()->findByAttributes(array('load_list' => $xml->Body->WarehouseOrder->OrderHeader['LoadListNo'], 'client_id' => $client->id));
        }
        if ($existing) {
            $code_text = '9999';
            $message_text = 'Neuspešno upisivanje naloga! Postoje greške.';
            $domtree = new DOMDocument('1.0', 'UTF-8');

            $xmlRoot = $domtree->createElement("ResponseMessage");
            $responseMessage = $domtree->appendChild($xmlRoot);
            $result = $domtree->createElement('Result');
            $result->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
            $result->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
            $result->setAttribute('WarehouseID', $xml->Header->WarehouseID);
            $responseMessage->appendChild($result);
            $code = $domtree->createElement('Code', $code_text);
            $result->appendChild($code);
            $message = $domtree->createElement('Message', $message_text);
            $result->appendChild($message);

            $errorMessage = $domtree->createElement('ErrorMessage');
            $errorMessage->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
            $errorMessage->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
            $code = $domtree->createElement('Code', 'X009');
            $errorMessage->appendChild($code);
            $location = $domtree->createElement('Location', 'Snimanje naloga - provera šeme. Rubrika: LoadListNo');
            $errorMessage->appendChild($location);
            $message = $domtree->createElement('Message', 'Nalog ' . $xml->Body->WarehouseOrder->OrderHeader['LoadListNo'] . ' već postoji u bazi podataka');
            $errorMessage->appendChild($message);

            $responseMessage->appendChild($errorMessage);

            $result = new SendOrderResult();
            $result->SendOrderResult = $domtree->saveXML();
            header('Content-type: text/xml');
            return $result;
        }

        $orders_klett = array();

        foreach ($xml->Body->children() as $warehouse_order) {

            $attributes = $header;

            $warehouse_order = json_decode(json_encode($warehouse_order), true);


            foreach ($warehouse_order['OrderHeader']['@attributes'] as $k => $v) {
                $attributes[$k] = is_array($v) ? json_encode($v) : $v;
            }


            foreach ($warehouse_order['OrderHeader'] as $k => $v) {
                if ($k != '@attributes') {
                    $attributes[$k] = is_array($v) ? json_encode($v) : $v;
                }
            }


            $model = new OrderKlett;





            $model->attributes = $attributes;


            if ($model->save()) {

                $orders_klett[] = $model;

                /*** ORDER ITEM ***********/


                foreach ($warehouse_order as $order_element_key => $order_element_value) {

                    if ($order_element_key == 'OrderItem') {


                        if (isset($order_element_value['@attributes'])) {    // kada postoji samo jedan OrderItem

                            $item_attributes = array();
                            foreach ($order_element_value as $k => $v) {


                                if (is_array($v)) {
                                    foreach ($v as $k1 => $v1) {
                                        if (is_array($v1)) {
                                            $item_attributes[$k1] = "";
                                        } else {
                                            $item_attributes[$k1] = $v1;
                                        }
                                    }
                                } else {
                                    $item_attributes[$k] = $v;
                                }
                            }

                            $item_attributes['order_klett_id'] = $model->id;
                            $order_klett_item = new OrderKlettItem;
                            $order_klett_item->attributes = $item_attributes;
                            if (!$order_klett_item->save()) {

                                break;
                            }

                        } else {


                            foreach ($order_element_value as $item) {

                                $item_attributes = array();
                                foreach ($item as $k => $v) {
                                    if (is_array($v)) {
                                        foreach ($v as $k1 => $v1) {
                                            if (is_array($v1)) {
                                                $item_attributes[$k1] = "";
                                            } else {
                                                $item_attributes[$k1] = $v1;
                                            }
                                        }
                                    } else {
                                        $item_attributes[$k] = $v;
                                    }
                                }

                                $item_attributes['order_klett_id'] = $model->id;

                                $order_klett_item = new OrderKlettItem;
                                $order_klett_item->attributes = $item_attributes;

                                if (!$order_klett_item->save()) {

                                    break;
                                }


                            }
                        }

                    }

                }

                if (!isset($order_klett_item)) {
                    foreach ($orders_klett as $order_klett_delete) {

                        if ($order_klett_delete->order_main_id != NULL) {
                            $order_client_delete = OrderClient::model()->findByPk($order_klett_delete->order_main_id);

                            if ($order_client_delete !== null) {
                                if ($order_client_delete->orderRequest) {

                                    $order_client_delete->orderRequest->delete();
                                }

                                $order_client_delete->delete();

                            }
                        }

                        $web_order_delete = WebOrder::model()->findByAttributes(array('order_klett_id' => $order_klett_delete->id));
                        if ($web_order_delete) {
                            $web_order_delete->delete();
                        }

                        $order_klett_delete->delete();


                    }
                    $code_text = '9999';
                    $message_text = 'Neuspešno upisivanje naloga! Nalog bez proizvoda.';
                    break;
                }

                if ($order_klett_item->hasErrors()) {

                    foreach ($orders_klett as $order_klett_delete) {

                        if ($order_klett_delete->order_main_id != NULL) {
                            $order_client_delete = OrderClient::model()->findByPk($order_klett_delete->order_main_id);

                            if ($order_client_delete !== null) {
                                if ($order_client_delete->orderRequest) {

                                    $order_client_delete->orderRequest->delete();
                                }
                                $order_client_delete->delete();

                            }
                        }

                        $web_order_delete = WebOrder::model()->findByAttributes(array('order_klett_id' => $order_klett_delete->id));
                        if ($web_order_delete) {
                            $web_order_delete->delete();
                        }

                        $order_klett_delete->delete();


                    }
                    $code_text = '9999';
                    $message_text = 'Neuspešno upisivanje naloga! Postoje greške.';
                    break;
                }

                /********* TRANSPORTATION UNITS ********
                 *
                 *
                 * if (isset($array['Body']['TransportationUnits'])) {
                 * foreach ($array['Body']['TransportationUnits']['TransportationUnit'] as $transportation_unit) {
                 * $attributes = array();
                 * foreach ($transportation_unit as $k => $v) {
                 * if (is_array($v)) {
                 * foreach ($this->listArray($v) as $k1 => $v1) {
                 * echo 'GO ' . $k1 . "\n";
                 * if ($k1 == 'id') {
                 * $k1 = 'transporation_unit_id';
                 * }
                 * $attributes[$k1] = $v1;
                 * }
                 * } else {
                 * $attributes[$k] = $v;
                 * }
                 * }
                 *
                 *
                 * $attributes['order_klett_id'] = $model->id;
                 *
                 *
                 * $order_klet_trans_unit = new OrderKlettTransUnit;
                 * $order_klet_trans_unit->attributes = $attributes;
                 * if (!$order_klet_trans_unit->save()) {
                 *
                 * throw new CHttpException('9999', 'Proizvod nije ispravan');
                 * }
                 *
                 * }
                 * }
                 ***/


                if ($model->isWebOrder()) {

                    $web_order = $this->exportWebOrder($model->id, $client->id);
                    if ($web_order) {
                        $code_text = '0000';
                        $message_text = 'Uspelo upisivanje u bazu!';
                    } else {
                        $code_text = '9999';
                        $message_text = 'Neuspešno upisivanje naloga! Postoje greške.';
                    }


                } else {
                    /** if order client is already created */
                    if (isset($order_client) && $order_client) {
                        $order_request_id = $order_client->order_request_id;
                    } else {
                        $order_request_id = false;
                    }

                    $order_client = $this->exportOrder($model->id, $client->id, $order_request_id);

                    if ($order_client) {
                        $model->order_main_id = $order_client->id;
                        $model->save();
                        $code_text = '0000';
                        $message_text = 'Uspelo upisivanje u bazu!';
                    } else {
                        $code_text = '9999';
                        $message_text = 'Neuspešno upisivanje naloga! Postoje greške.';
                        if (isset(Yii::app()->session['product_error_description'])) {
                            $message_text .= ' ' . Yii::app()->session['product_error_description'];
                            unset(Yii::app()->session['product_error_description']);

                        }
                    }
                }


            } else {
                $code_text = '9999';
                $message_text = 'Neuspešno upisivanje naloga! Postoje greške.';
                break;
            }


        }


        //     $time_slot = $this->createTimeSlot($model,$client);

        $domtree = new DOMDocument('1.0', 'UTF-8');

        $xmlRoot = $domtree->createElement("ResponseMessage");
        $responseMessage = $domtree->appendChild($xmlRoot);
        $result = $domtree->createElement('Result');
        $result->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
        $result->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
        $result->setAttribute('WarehouseID', $xml->Header->WarehouseID);
        $responseMessage->appendChild($result);
        $code = $domtree->createElement('Code', $code_text);
        $result->appendChild($code);
        $message = $domtree->createElement('Message', $message_text);
        $result->appendChild($message);
/*
        if (isset($duplicate) && $duplicate) {
            $errorMessage = $domtree->createElement('ErrorMessage');
            $errorMessage->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
            $errorMessage->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
            $code = $domtree->createElement('Code', 'X009');
            $errorMessage->appendChild($code);
            $location = $domtree->createElement('Location', 'Snimanje naloga - provera šeme. Rubrika: WarehouseOrder');
            $errorMessage->appendChild($location);
            $message = $domtree->createElement('Message', 'Nalog ' . $attributes['OrderNo'] . ' već postoji u bazi podataka');
            $errorMessage->appendChild($message);

            $responseMessage->appendChild($errorMessage);
        }
*/
        if (isset($model) && $model->hasErrors()) {
            $errors = $model->getErrors();
            foreach ($errors as $attribute => $error) {

                $errorMessage = $domtree->createElement('ErrorMessage');
                $errorMessage->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
                $errorMessage->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
                $code = $domtree->createElement('Code', 'X001');
                $errorMessage->appendChild($code);
                $location = $domtree->createElement('Location', 'Snimanje naloga - provera šeme. Rubrika: ' . $attribute);
                $errorMessage->appendChild($location);
                $message = $domtree->createElement('Message', implode('; ', $error));
                $errorMessage->appendChild($message);

                $responseMessage->appendChild($errorMessage);
            }
        }

        if (isset($order_klett_item) && $order_klett_item->hasErrors()) {
            $errors = $order_klett_item->getErrors();
            foreach ($errors as $attribute => $error) {

                $errorMessage = $domtree->createElement('ErrorMessage');
                $errorMessage->setAttribute('LoadListNo', $xml->Body->WarehouseOrder->OrderHeader['LoadListNo']);
                $errorMessage->setAttribute('OrderYear', $xml->Body->WarehouseOrder->OrderHeader['OrderYear']);
                $code = $domtree->createElement('Code', 'X001');
                $errorMessage->appendChild($code);
                $location = $domtree->createElement('Location', 'Snimanje naloga - provera šeme. Rubrika: Artikal ' . $order_klett_item->ProductCode . ' - ' . $order_klett_item->ProductName);
                $errorMessage->appendChild($location);
                $message = $domtree->createElement('Message', implode('; ', $error));
                $errorMessage->appendChild($message);

                $responseMessage->appendChild($errorMessage);
            }
        }

        $result = new SendOrderResult();
        $result->SendOrderResult = $domtree->saveXML();
        header('Content-type: text/xml');
        return $result;
        /*
        header('Content-type: text/xml');
        return $domtree->saveXML();
        */
    }

    public
    function exportWebOrder($id, $client_id)
    {
        $model = OrderKlett::model()->findByPk($id);
        $client = Client::model()->findByPk($client_id);

        if (!$model || !$client) {
            return false;
        }
        $web_order = new WebOrder;
        $web_order->attributes = array(
            'order_klett_id' => $model->id,
            'order_number' => $model->OrderNo,
            'client_id' => $client_id,
            'customer_data' => $model->CVParty,
            'load_list' => $model->LoadListNo,
            'delivery_type' => $model->deliveryType,
            'delivery_date' => $model->DeliveryDate,

        );
        if (!$web_order->save()) {
            return false;
        }

        foreach ($model->orderKlettItems as $item) {
            $product = Product::model()->findByAttributes(array('internal_product_number' => $item->ProductCode));
            if (!$product) {
                $product = $this->exportProduct($item, $client_id);
                if (!$product) {
                    $web_order->delete();
                    return false;
                }
            }

            $web_order_product = new WebOrderProduct;
            $web_order_product->attributes = array(
                'web_order_id' => $web_order->id,
                'product_id' => $product->id,
                'quantity' => (int)$item->Quantity,
            );
            if (!$web_order_product->save()) {
                $web_order->delete();
                return false;
            }


        }

        return $web_order;


    }

    public
    function exportProduct($item, $client_id)
    {

        $product = new Product;
        $product->attributes = array(
            'client_id' => $this->getKlettClientId(),
            'product_type_id' => $this->getKlettProductTypeId(),
            'internal_product_number' => $item->ProductCode,
            'external_product_number' => $item->ProductCode,
            'product_barcode' => $item->Barcode,
            'title' => $item->ProductName,
            'weight' => $item->UnitWeight,

        );
        if ($product->save()) {

            $package = Package::model()->findByAttributes(array('product_count' => $item->QttInPack, 'load_carrier_count' => $item->QttOnPalett));
            if (!$package) {
                $package = $this->exportPackage($item);
                if (!$package) {
                    $product->delete();
                    return false;
                }
                $product_has_package = new ProductHasPackage;
                $product_has_package->attributes = array(
                    'product_id' => $product->id,
                    'package_id' => $package->id,
                    'is_default' => 1
                );
                if (!$product_has_package->save()) {
                    $product->delete();
                    return false;
                }
            }
            $product->package_id = $package->id;
            $product->save();

            return $product;
        } else {
          /** OVDE VRATITI INFORMACIJU O GRESCI */
        }

        return false;
    }

    public
    function getKlettClientId()
    {
        $klett = Client::model()->find(array('condition' => 'title LIKE "%klett%"'));
        return $klett->id;
    }

    public
    function getKlettProductTypeId()
    {
        $product_type = ProductType::model()->find(array('condition' => 'title LIKE "%klett%"'));
        return $product_type->id;
    }

    public
    function exportPackage($item)
    {
        $package = new Package;
        $product_count = (int)$item->QttInPack;
        $load_carrier_count = (int)$item->QttOnPalett;

        $package->attributes = array(
            'title' => 'KLETT_' . $product_count . '_x_' . $load_carrier_count . '_' . round($product_count * $item->UnitWeight, 3),
            'product_count' => $product_count,
            'load_carrier_count' => $load_carrier_count,
            'gross_weight' => round($product_count * $item->UnitWeight, 3)
        );
        if ($package->save()) {
            return $package;
        }
        return false;
    }

    public function exportOrder($id, $client_id, $order_request_id)
    {

        $model = OrderKlett::model()->findByPk($id);
        $client = Client::model()->findByPk($client_id);

        if (!$model || !$client) {

            return false;
        }


        if ($order_request_id === false) {
            $order_request = new OrderRequest;
            $order_request->attributes = array(
                'urgent' => $model->IsUrgent,
                'activity_type_id' => $model->OrderTypeID == '02' ? 2 : 1,
                'direction' => $model->OrderTypeID == '02' ? 'out' : 'in',
                'location_id' => $client->location ? $client->location_id : null,
                'load_list' => $model->LoadListNo,


            );

            if (!$order_request->save()) {

                return false;
            }
        } else {
            $order_request = OrderRequest::model()->findByPk($order_request_id);
        }

        if ($order_request === null) {

            return false;
        }

        $customerSupplier = json_decode($model->CVParty, true);
        if ($customerSupplier['CVPartyType'] == 1) {
            $customer_supplier = Client::model()->findByAttributes(array('tax_number' => $customerSupplier['CVPartyTaxNo']));
        } else {
            $customer_supplier = null;
        }
        if ($customer_supplier === null) {
            $customer_supplier = new Client;
            $customer_supplier->attributes = array(
                'title' => isset($customerSupplier['CVPartyName']) && !is_array($customerSupplier['CVPartyName']) ? $customerSupplier['CVPartyName'] : '',
                'official_title' => isset($customerSupplier['CVPartyID']) && !is_array($customerSupplier['CVPartyID']) ? $customerSupplier['CVPartyID'] : '',
                'tax_number' => isset($customerSupplier['CVPartyTaxNo']) && !is_array($customerSupplier['CVPartyTaxNo']) ? $customerSupplier['CVPartyTaxNo'] : '',
                'location_id' => $client->location_id,
                'section_id' => $client->section_id,
                'postal_code' => isset($customerSupplier['CVPartyPostalCode']) && !is_array($customerSupplier['CVPartyPostalCode']) ? $customerSupplier['CVPartyPostalCode'] : '',
                'city' => isset($customerSupplier['CVPartyCity']) && !is_array($customerSupplier['CVPartyCity']) ? $customerSupplier['CVPartyCity'] : '',
                'address' => isset($customerSupplier['CVPartyAddress']) && !is_array($customerSupplier['CVPartyAddress']) ? $customerSupplier['CVPartyAddress'] : '',
                'country' => isset($customerSupplier['CVPartyCountry']) && !is_array($customerSupplier['CVPartyCountry']) ? $customerSupplier['CVPartyCountry'] : '',
                'phone' => isset($customerSupplier['CVPartyPhoneNo']) && !is_array($customerSupplier['CVPartyPhoneNo']) ? $customerSupplier['CVPartyPhoneNo'] : '',
                'company_number' => isset($customerSupplier['CVPartyCompanyNo']) && !is_array($customerSupplier['CVPartyCompanyNo']) ? $customerSupplier['CVPartyCompanyNo'] : '',


            );

            if (!$customer_supplier->save()) {
                $customer_supplier = $client;
            }

        }

        $order_client = new OrderClient;
        $order_client->attributes = array(
            'order_request_id' => $order_request->id,
            'order_number' => $model->OrderNo,
            'client_id' => $client_id,
            'customer_supplier_id' => $customer_supplier->id,
            'delivery_type' => $model->deliveryType,
            'delivery_date' => $model->DeliveryDate,


        );

        if (!$order_client->save()) {
            $order_request->delete();

            return false;
        }

        foreach ($model->orderKlettItems as $item) {
            $product = Product::model()->findByAttributes(array('product_barcode' => trim($item->Barcode)));
            if (!$product) {
                $product = $this->exportProduct($item, $client_id);
                if (!$product) {
                    Yii::app()->session['product_error_description'] = 'Greška kod proizvoda: ' . $item->ProductCode . ' - ' . $item->ProductName . ' - ' . $item->Barcode;
                    $order_client->delete();
                    $order_request->delete();

                    return false;
                }
            }

            $order_product = new OrderProduct;
            $order_product->attributes = array(
                'order_client_id' => $order_client->id,
                'product_id' => $product->id,
                'package_id' => $product->package_id,
                'quantity' => (int)$item->Quantity,
                'paletts' => (int)$item->PalettCount,
            );
            if (!$order_product->save()) {
                $order_client->delete();
                $order_request->delete();

                return false;
            }


        }

        return $order_client;


    }

    public
    function createTimeSlot($order_klett, $client)
    {
        $time_slot = new TimeSlot;
        $time_slot->attributes = array(
            'activity_type_id' => $order_klett->OrderTypeID == '02' ? 2 : 1,
            'location_id' => $client->location_id,
            'section_id' => $client->section_id,
            'truck_type_id' => 1,
            'license_plate' => 'Nepoznato',
            'urgent' => $order_klett->IsUrgent,
            'notes' => $order_klett->Remark,
        );
        if ($time_slot->save()) {

        }
    }

    public
    function listArray($array)

    {
        return iterator_to_array(
            new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array)));
    }

    protected function to_xml(SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                to_xml($new_object, $value);
            } else {
                // if the key is an integer, it needs text with it to actually work.
                if ($key != 0 && $key == (int)$key) {
                    $key = "key_$key";
                }

                $object->addChild($key, $value);
            }
        }
    }


}