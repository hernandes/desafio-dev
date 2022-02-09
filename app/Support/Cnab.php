<?php
namespace App\Support;

use Generator;
use RuntimeException;

class Cnab
{

    private $template = [];

    public function template(array $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function parse(string $file): array
    {
        if (! file_exists($file)) {
            throw new RuntimeException("Arquivo $file não encontrado!");
        }

        if (empty($this->template)) {
            throw new RuntimeException('Nenhum template informado!');
        }

        if (! $data = file_get_contents($file)) {
            throw new RuntimeException('arquivo CNAB vazio!');
        }

        $rows = [];
        $lines = explode("\n", trim($data));
        foreach ($lines as $line) {
            $columns = [];
            foreach ($this->template as $template) {
                $data = substr($line, $template['start'], $template['length']);
                $data = isset($template['handle']) ? $template['handle']($data) : trim($data);
                $columns[$template['id'] ?? $template['label']] = $data;
            }

            $rows[] = $columns;
        }

        return $rows;
    }

    public function cursor(string $file): Generator
    {
        foreach ($this->parse($file) as $row) {
            yield $row;
        }
    }

}
