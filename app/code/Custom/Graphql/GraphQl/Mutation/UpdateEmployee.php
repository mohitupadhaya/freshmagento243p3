<?php

namespace Custom\Graphql\GraphQl\Mutation;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Custom\Graphql\Model\EmployeeFactory;
use Custom\Graphql\Model\ResourceModel\Employee as EmployeeResource;
use Magento\Framework\Exception\LocalizedException;

class UpdateEmployee implements ResolverInterface
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
            if (empty($args['employee_id'])) {
                throw new LocalizedException(__('Employee ID is required'));
            }

            if (empty($args['input']) || !is_array($args['input'])) {
                throw new LocalizedException(__('Input data is required'));
            }

            // Load existing employee
            $employee = $this->employeeFactory->create();
            $this->employeeResource->load($employee, $args['employee_id']);

            if (!$employee->getId()) {
                throw new LocalizedException(__('Employee not found'));
            }

            // Update fields
            $employee->addData($args['input']);

            // Save changes
            $this->employeeResource->save($employee);

            return [
                'success'  => true,
                'message'  => 'Employee updated successfully',
                'employee' => $employee->getData()
            ];
        } catch (LocalizedException $e) {
            return [
                'success'  => false,
                'message'  => $e->getMessage(),
                'employee' => null
            ];
        } catch (\Exception $e) {
            return [
                'success'  => false,
                'message'  => 'Something went wrong while updating employee',
                'employee' => null
            ];
        }
    }
}
