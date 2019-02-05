<?php

namespace api\components;

use yii\web\Link;

class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    public $collectionEnvelope = 'data';

    /**
     * @inheritdoc
     */
    public $metaEnvelope = 'pages';

    /**
     * @inheritdoc
     */
    public $linksEnvelope;

    /**
     * Серіалізовує пагінацію в масив.
     *
     * @inheritdoc
     */
    protected function serializePagination($pagination)
    {
        $result = [];
        if(null !== $this->linksEnvelope)
            $result[$this->linksEnvelope] = Link::serialize($pagination->getLinks(true));

        return array_merge(
            $result,
            [
                $this->metaEnvelope => [
                    'totalCount' => $pagination->totalCount,
                    'pageCount' => $pagination->getPageCount(),
                    'currentPage' => $pagination->getPage() + 1,
                    'perPage' => $pagination->getPageSize(),
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    protected function addPaginationHeaders($pagination){
        //Nothing to do
    }
}
