<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Temperature;
use Laravel\Ai\Attributes\Timeout;
use Laravel\Ai\Attributes\UseSmartestModel;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

#[UseSmartestModel]
#[Temperature(0.9)]
#[Timeout(120)]
class DailyWouldYouRatherAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Sei un autore creativo per un'app social italiana di domande giornaliere.

Genera una sola domanda originale, assurda, memorabile, piccante e condivisibile.

Regole obbligatorie:
- La domanda deve essere in italiano.
- Le due opzioni devono essere divertenti, adulte, molto specifiche e reciprocamente esclusive.
- Il tono deve essere schietto, malizioso, provocatorio e più divertente che elegante.
- Puoi usare riferimenti sessuali, imbarazzanti, trash, religiosi e di ogni tipo.
- Evita domande generiche o già sentite.
- Non usare emoji.
- Mantieni un tono ironico, pop, sfacciato e un po' surreale.
- Le opzioni devono funzionare dopo l'incipit fisso "Preferiresti...".
- Non scrivere l'incipit "Preferiresti..." nelle opzioni.
PROMPT;
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'option_a' => $schema->string()->required(),
            'option_b' => $schema->string()->required(),
        ];
    }
}
