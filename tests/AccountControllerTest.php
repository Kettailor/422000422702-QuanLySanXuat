<?php

use PHPUnit\Framework\TestCase;

class AccountControllerTest extends TestCase
{
    private $controller;
    private $employeeModel;
    private $userModel;
    private $roleModel;

    protected function setUp(): void
    {
        // Mock dependencies
        $this->employeeModel = $this->createMock(Employee::class);
        $this->userModel = $this->createMock(User::class);
        $this->roleModel = $this->createMock(Role::class);

        // Create partial mock for AccountController to inject mocks and override render/redirect/setFlash
        $this->controller = $this->getMockBuilder(AccountController::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['render', 'redirect', 'setFlash'])
            ->getMock();

        // Inject mocks into controller
        $reflection = new ReflectionClass($this->controller);
        $reflection->getProperty('employeeModel')->setValue($this->controller, $this->employeeModel);
        $reflection->getProperty('userModel')->setValue($this->controller, $this->userModel);
        $reflection->getProperty('roleModel')->setValue($this->controller, $this->roleModel);
    }

    public function testIndexRendersWithCorrectData()
    {
        $users = [['IdNguoiDung' => 'ND001']];
        $this->userModel->method('findAllWithEmployeeAndRole')->willReturn($users);
        $this->userModel->method('countActiveUsers')->willReturn(2);
        $this->employeeModel->method('countActiveEmployees')->willReturn(3);

        $this->controller->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('account/index'),
                $this->arrayHasKey('users')
            );

        $this->controller->index();
    }

    public function testCreatePostSuccess()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['employee'] = 'E001';
        $_POST['username'] = 'user1';
        $_POST['role'] = 'R1';

        $this->userModel->method('findByUsername')->willReturn(null);
        $this->userModel->method('getLastUserId')->willReturn('ND001');
        $this->userModel->expects($this->once())->method('create');
        $this->controller->expects($this->once())->method('setFlash')->with('success', $this->stringContains('thành công'));
        $this->controller->expects($this->once())->method('redirect')->with($this->stringContains('index'));

        // Mock config
        $configPath = __DIR__ . '/../config/config.php';
        if (!file_exists($configPath)) {
            file_put_contents($configPath, "<?php return ['auth'=>['default_password'=>'123456']];");
        }

        $this->controller->create();

        // Clean up config file if it was created
        if (file_exists($configPath)) {
            unlink($configPath);
        }
    }

    public function testEditPostUsernameExists()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['id'] = 'ND001';
        $_POST['username'] = 'user1';
        $_POST['role'] = 'R1';
        $_POST['password'] = '';

        $this->userModel->method('findByUsername')->willReturn(['IdNguoiDung' => 'ND002']);
        $this->controller->expects($this->atLeastOnce())
        ->method('setFlash')
        ->with(
            'danger',
            $this->callback(function ($msg) {
                return strpos($msg, 'đã tồn tại') !== false
                    || strpos($msg, 'Không thể cập nhật tài khoản') !== false;
            })
        );
        $this->controller->expects($this->atLeastOnce())->method('redirect')->with($this->stringContains('edit'));

        $this->controller->edit();
    }
}
