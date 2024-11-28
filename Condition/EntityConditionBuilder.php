<?php

/*
 *  EntityConditionBuilder.php 
 */

namespace Petkopara\MultiSearchBundle\Condition;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of EntityConditionBuilder
 *
 * @author Petko Petkov <petkopara@gmail.com>
 */
class EntityConditionBuilder extends ConditionBuilder
{

    /**
     *
     */
    public function __construct(
        QueryBuilder $queryBuilder,
        string       $entityName,
        string       $searchTerm,
        array        $searchFields = [],
        string       $comparisonType = self::COMPARISION_TYPE_WILDCARD
    )
    {
        $this->entityManager = $queryBuilder->getEntityManager();

        $this->queryBuilder = $queryBuilder;
        $this->searchTerm = $searchTerm;
        $this->searchComparisonType = $comparisonType;
        $this->entityName = $entityName;


        /** @var $metadata \Doctrine\ORM\Mapping\ClassMetadata */
        $metadata = $this->entityManager->getClassMetadata($this->entityName);

        $this->idName = $metadata->getSingleIdentifierFieldName();

        if (count($searchFields) > 0) {
            $this->searchColumns = $searchFields;
        } else {
            foreach ($metadata->fieldMappings as $field) {
                $this->searchColumns[] = $field['fieldName'];
            }
        }
    }

}
