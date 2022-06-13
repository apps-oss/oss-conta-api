<?php
// FIX, i dont know why, but things doesnt work without this line
ob_start();

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AuthTest extends CIUnitTestCase
{

    use FeatureTestTrait;

    public function testLogin()
    {
        $result = $this->post('/login', [
            'user'  => 'admin',
            'password' => 'admin$',
        ]);

        $this->assertTrue($result->isOk());

        // var_dump($result);
    }
}
