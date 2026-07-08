<?php

namespace Custom\Graphql\GraphQl\Mutation;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Custom\Graphql\Model\EmployeeFactory;
use Custom\Graphql\Model\ResourceModel\Employee as EmployeeResource;
use Magento\Framework\Exception\LocalizedException;

class CreateEmployee implements ResolverInterface
{
    private $employeeFactory;
    private $employeeResource;

    public function __construct(
       EmployeeFactory $employeeFactory,
        EmployeeResource $employeeResource
    ) {
       $this->employeeFactory  = $employeeFactory;
        $this->employeeResource = $employeeResource;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ) {
        

        try {
        if (!isset($args['input'])) {
            throw new LocalizedException(__('Input data is required'));
        }

        $employee = $this->employeeFactory->create();
        $employee->setData($args['input']);
        $this->employeeResource->save($employee);

        return [
            'success'  => true,
            'message'  => 'Employee created successfully',
            'employee' => $employee->getData()
        ];
    } catch (\Exception $e) {
        return [
            'success'  => false,
            'message'  => $e->getMessage(),
            'employee' => null
        ];
    }
    }
}
