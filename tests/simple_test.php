<?php
require_once __DIR__ . '/../environment.php';

require_once __DIR__ . '/../www/uarpc.php';

class SimpleTest {
    private $uarpc;

    public function __construct() {
        new uarpc('test_uarpc_', '');
        $this->uarpc = new UARPC_base(1);
    }

    public function run() {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method, 0, 4) === 'test') {
                echo "Running $method...\n";
                try {
                    $this->$method();
                    echo "\033[32mOK\033[0m\n";
                } catch (Exception $e) {
                    echo "\033[31mFAIL\033[0m\n";
                    echo $e->getMessage() . "\n";
                }
            }
        }
    }

    public function testAddRole() {
        $roleID = $this->uarpc->roles->add('Test Role', 'This is a test role');
        if (!is_int($roleID) || $roleID <= 0) {
            throw new Exception('Failed to add role');
        }
    }

    public function testAddPermission() {
        $permissionID = $this->uarpc->permissions->add('test-permission', 'This is a test permission');
        if (!is_int($permissionID) || $permissionID <= 0) {
            throw new Exception('Failed to add permission');
        }
    }

    public function testAssignPermissionToRole() {
        $roleID = $this->uarpc->roles->add('Test Role 2');
        $permissionID = $this->uarpc->permissions->add('test-permission-2');
        $result = $this->uarpc->permissions->assign($permissionID, $roleID);
        if (!$result) {
            throw new Exception('Failed to assign permission to role');
        }
    }

    public function testAssignRoleToUser() {
        $roleID = $this->uarpc->roles->add('Test Role 3');
        $result = $this->uarpc->roles->assign($roleID, 1);
        if (!$result) {
            throw new Exception('Failed to assign role to user');
        }
    }

    public function testHavePermission() {
        $roleID = $this->uarpc->roles->add('Test Role 4');
        $permissionID = $this->uarpc->permissions->add('test-permission-4');
        $this->uarpc->permissions->assign($permissionID, $roleID);
        $this->uarpc->roles->assign($roleID, 1);
        if( !$this->uarpc->havePermission('test-permission-4', 1) ){
            throw new Exception('User should have permission but does not');
        }
    }
}

$test = new SimpleTest();
$test->run();
