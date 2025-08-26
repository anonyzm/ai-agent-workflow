<?php

namespace App\Temporal;

use Temporal\DataConverter\PayloadConverterInterface;
use Temporal\Api\Common\V1\Payload;
use Temporal\DataConverter\Type;
use Temporal\DataConverter\EncodingKeys;

class JsonConverter implements PayloadConverterInterface    
{
    public function toPayload($value): ?Payload 
    {
        $json = $this->marshal($value);
        return new Payload([
            'data' => $json,
        ])->setMetadata(['encoding' => EncodingKeys::METADATA_ENCODING_JSON]);
    }

    public function fromPayload(Payload $payload, Type $type)
    {
        return $this->unmarshal($payload->getData());
    }

    public function getEncodingType(): string
    {
        return EncodingKeys::METADATA_ENCODING_JSON;
    }

    //-------------------------private methods-------------------------

    private function marshal($value): string
    {
        $dataType = gettype($value);
        $entity = [
            'serializedType' => $dataType,
        ];
        switch($dataType) {
            case 'resource':
            case 'unknown type':
                throw new \InvalidArgumentException("Error serializing value of type `{$dataType}`");
            case 'object': $entity['serializedData'] = $this->marshalObject($value);
                break;
            default: $entity['serializedData'] = $value;
        }

        return json_encode($entity);
    }

    /**
     * @param array $config
     * @return mixed|null
     */
    private function unmarshal(string $json)  
    {
        $config = json_decode($json, true);
        $entity = null;
        $dataType = $config['serializedType'];
        switch($dataType) {
            case 'object': $entity = $this->unmarshalObject($config['serializedData']);
                break;
            default: $entity = $config['serializedData'];
        }

        return $entity;
    }

    /**
     * @param object $object
     * @return array
     */
    private function marshalObject($object): array 
    {
        $entity = [
            'class' => get_class($object),
        ];
        
        $attributes = array_keys(get_object_vars($object));

        foreach ($attributes as $attribute) {
            $entity['attributes'][$attribute] = $this->marshal($object->$attribute);
        }

        return $entity;
    }

    /**
     * @param array $config
     * @return mixed
     */
    private function unmarshalObject(array $config): object
    {
        $className = $config['class'];
        $attributes = $config['attributes'];

        try {
            $object = new $className();
            foreach ($attributes as $attribute => $value) {
                $object->$attribute = $this->unmarshal($value);
            }
        }
        catch (\Throwable $e) {
            $object = null;
        }

        return $object;
    }
}