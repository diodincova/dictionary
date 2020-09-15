<?php
require_once './Word.php';

$house = new Word();

$house->name = 'house';
$house->translation = 'дом';
$house->transcription = 'haʊs';
$house->description = 'A house is a single-unit residential building, which may range in complexity from a rudimentary hut to a complex, structure of wood, masonry, concrete or other material, outfitted with plumbing, electrical, and heating, ventilation, and air conditioning systems.';
$house->examples = [
                    '1) Montag had rarely seen that many house lights.', 
                    '2) The house was so small, the furnishings so very simple.'
                   ];

echo $house->name . PHP_EOL;
echo $house->transcription . PHP_EOL;
echo $house->translation . PHP_EOL;
echo $house->description . PHP_EOL;
echo $house->examples[0] . PHP_EOL;
echo $house->examples[1] . PHP_EOL;
