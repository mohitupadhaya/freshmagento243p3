<?php
namespace Custom\Graphql\GraphQl\Mutation;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Custom\Graphql\Model\EmployeeFactory;

class DeleteEmployee implements ResolverInterface
{
    private $employeeFactory;

    public function __construct(EmployeeFactory $employeeFactory)
    {
        $this->employeeFactory = $employeeFactory;
    }

    public function resolve($field, $context, $info, array $value = null, array $args = null)
    {
    //     $employee = $this->employeeFactory->create()->load($args['employee_id']);
    //     if ($employee->getId()) {
    //         $employee->delete();
    //         return true;
    //     }
    //     return false;
    // }

         try {
                if (!isset($args['employee_id'])) {
                    throw new LocalizedException(__('Employee ID is required'));
                }

                $employee = $this->employeeFactory->create()->load($args['employee_id']);
               
                $employee->delete();
                if (!$employee->getId()) {
                    return [
                        'success' => false,
                        'message' => 'Employee not found',
                        'employee' => null,
                    ];
                    // throw new LocalizedException(__('Employee not found'));
                }

                // $this->employeeResource->delete($employee);

                return [
                    'success'  => true,
                    'message'  => 'Employee deleted successfully',
                    'employee' => null
                ];
    } catch (\Exception $e) {
        return [
            'success'  => false,
            'message'  => "",
            'employee' => null
        ];
    }
}
}
