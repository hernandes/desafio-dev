<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Support\Cnab;
use PHPUnit\Framework\TestCase;

class CnabTest extends TestCase
{

    private Cnab|null $cnab = null;
    private array $rows = [];

    public function setUp(): void
    {
        $this->cnab = Transaction::cnab();
        $this->rows = $this->cnab->parse('CNAB.txt');
    }

    public function test_parse_with_exact_number_rows()
    {
        $this->assertCount(21, $this->rows);
    }

    public function test_throws_with_non_existing_file()
    {
        $this->expectException(\RuntimeException::class);

        $this->cnab->parse('abc.txt');
    }

    public function test_throws_with_non_passed_template()
    {
        $this->expectException(\RuntimeException::class);

        $this->cnab->template([])->parse('CNAB.txt');
    }

    public function test_first_row_data()
    {
        $except = [
            'tipo' => 3,
            'data' => '2019-03-01',
            'valor' => -142,
            'cpf' => '09620676017',
            'cartao' => '4753****3153',
            'hora' => '15:34:53',
            'dono' => 'JOÃO MACEDO',
            'loja' => 'BAR DO JOÃO'
        ];

        $this->assertEquals($except, $this->rows[0]);
    }

}
