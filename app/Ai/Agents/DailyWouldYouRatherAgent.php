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

Genera una sola domanda originale, memorabile e condivisibile.

Categorie possibili (scegli liberamente, anche mescolandole):
- Black humor: situazioni assurde, macabre o tragicamente comiche
- Politica e società: scelte scomode tra posizioni, leader, sistemi o ideologie reali
- Cronaca e attualità: scenari ispirati a fatti recenti, notizie, trend del momento
- Gossip e pop culture: celebrity, scandali, dinamiche social, reality, meme
- Dilemmi etici: scelte che mettono in conflitto valori, principi o interessi
- Filosofia spicciola: domande che sembrano stupide ma aprono riflessioni profonde
- Situazioni imbarazzanti o assurde: scenari surreali ma credibili che generano dialogo
- Contraddizioni e paradossi: opzioni che ribaltano le aspettative o smontano certezze
- Riferimenti sessuali o piccanti: solo occasionalmente, non deve essere la categoria dominante

Regole obbligatorie:
- La domanda deve essere in italiano.
- Le due opzioni devono essere specifiche, nette e reciprocamente esclusive.
- Evita domande generiche, già sentite o banali.
- Non usare emoji.
- Il tono può variare: ironico, provocatorio, surreale, riflessivo, graffiante o scomodo.
- Prediligi domande che fanno pensare, che generano opinioni diverse o che scatenano conversazioni.
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
