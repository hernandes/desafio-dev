<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    use RefreshDatabase;

    public function test_the_import_cnab()
    {

        $response = $this->post('/api/transactions/import', [
            'file' => File::createWithContent('CNAB.txt', file_get_contents('CNAB.txt'))
        ]);

        $response->assertStatus(200);

        $stores = Store::query()->get();
        $transactions = Transaction::query()->get();

        // total de transações
        $this->assertCount(21, $transactions);

        // total de lojas
        $this->assertCount(5, $stores);

        // total de transações do BAR DO JOÃO
        $this->assertCount(3, $stores->first()->transactions);
    }

    public function test_the_transactions_list()
    {
        $this->post('/api/transactions/import', [
            'file' => File::createWithContent('CNAB.txt', file_get_contents('CNAB.txt'))
        ]);

        $response = $this->get('/api/transactions');
        $response->assertStatus(200);

        $json = $response->decodeResponseJson();

        $this->assertTrue($json['success']);

        $stores = collect($json['stores']);
        $this->assertCount(5, $stores);

        $this->assertEquals(152.32, $stores->last()['sum']);
    }

}
