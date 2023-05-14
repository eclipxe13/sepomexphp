<?php

declare(strict_types=1);

namespace Eclipxe\SepomexPhp\Importer;

use Generator;

final class RenameRules
{
    /** @var RenamesRule[] */
    private array $rules;

    public function __construct(RenamesRule ...$rules)
    {
        $this->rules = array_values($rules);
    }

    public static function createDefault(): self
    {
        return new self(
            new RenamesRule(from: 'Coahuila de Zaragoza', to: 'Coahuila'),
            new RenamesRule(from: 'Michoacán de Ocampo', to: 'Michoacán'),
            new RenamesRule(from: 'Veracruz de Ignacio de la Llave', to: 'Veracruz'),
            // new StatesRenamesRule(from: 'México', to: 'Estado de México'),
        );
    }

    /** @return RenamesRule[] */
    public function rules(): array
    {
        return $this->rules();
    }

    public function rulesAsNames(): Generator
    {
        foreach ($this->rules as $rule) {
            yield $rule->from => $rule->to;
        }
    }
}
