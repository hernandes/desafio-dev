<?php
namespace App\Models;

use App\Support\Cnab;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Transaction extends Model
{

    protected static $types = [
        '1' => [
            'name' => 'Débito',
            'positive' => true
        ],
        '2' => [
            'name' => 'Boleto',
            'positive' => false
        ],
        '3' => [
            'name' => 'Financiamento',
            'positive' => false
        ],
        '4' => [
            'name' => 'Crédito',
            'positive' => true
        ],
        '5' => [
            'name' => 'Recebimento Empréstimo',
            'positive' => true
        ],
        '6' => [
            'name' => 'Vendas',
            'positive' => true
        ],
        '7' => [
            'name' => 'Recebimento TED',
            'positive' => true
        ],
        '8' => [
            'name' => 'Recebimento DOC',
            'positive' => true
        ],
        '9' => [
            'name' => 'Aluguel',
            'positive' => false
        ]
    ];

    protected $fillable = [
        'type', 'transaction_at', 'value', 'document', 'card', 'store_id'
    ];

    protected $casts = [
        'transaction_at' => 'datetime',
        'value' => 'float'
    ];

    protected $appends = [
        'type_name'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    protected function typeName(): Attribute
    {
        return new Attribute(fn ($data, $attributes) => static::$types[$attributes['type']]['name']);
    }

    public static function cnab(): Cnab
    {
        $template = [
            [
                'id' => 'tipo',
                'start' => 0,
                'length' => 1,
                'handle' => fn ($data) => (int)$data
            ], [
                'id' => 'data',
                'start' => 1,
                'length' => 8,
                'handle' => fn ($data) => Carbon::createFromFormat('Ymd', $data)->format('Y-m-d')
            ], [
                'id' => 'valor',
                'start' => 9,
                'length' => 10,
                'handle' => fn ($data, $row) => ($data / 100) * (static::$types[$row['tipo']]['positive'] ? 1 : -1)
            ], [
                'id' => 'cpf',
                'start' => 19,
                'length' => 11
            ], [
                'id' => 'cartao',
                'start' => 30,
                'length' => 12
            ], [
                'id' => 'hora',
                'start' => 42,
                'length' => 6,
                'handle' => fn ($data) => Carbon::createFromFormat('His', $data)->format('H:i:s')
            ], [
                'id' => 'dono',
                'start' => 48,
                'length' => 14
            ], [
                'id' => 'loja',
                'start' => 62,
                'length' => 19
            ]
        ];

        return (new Cnab)->template($template);
    }

    public static function import($file)
    {
        DB::transaction(function () use ($file) {
            $transactions = collect(static::cnab()->parse($file));
            $transactions->each(function ($t) {
                $store = Store::query()->firstOrCreate([
                    'name' => $t['loja']
                ], [
                    'owner' => $t['dono']
                ]);

                static::query()->create([
                    'type' => $t['tipo'],
                    'transaction_at' => $t['data'] . ' ' . $t['hora'],
                    'value' => $t['valor'],
                    'document' => $t['cpf'],
                    'card' => $t['cartao'],
                    'store_id' => $store->id
                ]);
            });
        });
    }

    public static function groupedByStore(): Collection
    {
        $out = [];
        $stores = static::with('store')
            ->get()
            ->groupBy('store_id');

        foreach ($stores as $transactions) {
            $out[] = [
                'store' => $transactions->first()->store,
                'transactions' => $transactions->sortBy('transaction_at'),
                'sum' => $transactions->sum('value')
            ];
        }

        return collect($out);
    }

}
