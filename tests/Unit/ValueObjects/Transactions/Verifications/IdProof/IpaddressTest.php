<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\ValueObjects\Transactions\Verifications\IdProof;

use Noardcode\LaravelSignhost\Tests\TestCase;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\Verifications\IdProof\Ipaddress;

class IpaddressTest extends TestCase
{
    public function test_correct_values_are_returned()
    {
        $ipaddress = new Ipaddress(
            '1.2.3.4'
        );

        $this->assertEquals('IPAddress', $ipaddress->getType());
        $this->assertEquals('1.2.3.4', $ipaddress->getIpAddress());
    }
}
