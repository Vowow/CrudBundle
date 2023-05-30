<?php

namespace Sherlockode\CrudBundle\Filter;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Sherlockode\CrudBundle\Provider\ExpressionBuilder;

class StringFilter implements FilterInterface
{
    const TYPE_EQUAL = 'equal';

    const TYPE_CONTAINS = 'contains';

    const TYPE_NOT_CONTAINS = 'not_contains';

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return 'string' === $type;
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param array        $data
     *
     * @return void
     */
    public function apply(QueryBuilder $query, string $field, $data): void
    {
        if ('' === $data['value']) {
            return;
        }

        $query->andWhere($this->getExpression($query, $field, $data));
    }

    /**
     * @param QueryBuilder $query
     * @param string       $field
     * @param array        $data
     *
     * @return Comparison
     */
    private function getExpression(QueryBuilder $query, string $field, array $data)
    {
        $expressionBuilder = new ExpressionBuilder($query);

        switch ($data['type']) {
            case self::TYPE_EQUAL:
                return $expressionBuilder->equals($field, $data['value']);
            case self::TYPE_CONTAINS:
                return $expressionBuilder->like($field, '%' . $data['value'] . '%');
            case self::TYPE_NOT_CONTAINS:
                return $expressionBuilder->notLike($field, '%' . $data['value'] . '%');
        }
    }
}
