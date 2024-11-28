<?php

namespace Petkopara\MultiSearchBundle\Service;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Petkopara\MultiSearchBundle\Condition\ConditionBuilder;
use Petkopara\MultiSearchBundle\Condition\EntityConditionBuilder;
use Petkopara\MultiSearchBundle\Condition\FormConditionBuilder;
use RuntimeException;

/**
 * Description of MultiSearchUpdater
 *
 * @author Petkov Petkov
 */
class MultiSearchBuilderService
{

    /**
     *
     */
    public function searchForm(
        QueryBuilder  $queryBuilder,
        FormInterface $form
    ): QueryBuilder
    {
        $conditionBuilder = new FormConditionBuilder($queryBuilder, $form);

        return $conditionBuilder->getQueryBuilderWithConditions();
    }

    /**
     *
     * @throws RuntimeException
     */
    public function searchEntity(
        QueryBuilder $queryBuilder,
        string       $entityName,
        string       $searchTerm,
        array        $searchFields = [],
                     $comparisonType = ConditionBuilder::COMPARISION_TYPE_WILDCARD
    ): QueryBuilder
    {
        if (!in_array($comparisonType, [ConditionBuilder::COMPARISION_TYPE_WILDCARD, ConditionBuilder::COMPARISION_TYPE_EQUALS])) {
            throw new RuntimeException("The condition type should be wildcard or equals");
        }

        $conditionBuilder = new EntityConditionBuilder($queryBuilder, $entityName, $searchTerm, $searchFields, $comparisonType);

        return $conditionBuilder->getQueryBuilderWithConditions();
    }

}
