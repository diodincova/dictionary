<?php
require_once './Word.php';
require_once './load.php';

$house = new Word();

$house->name = 'house';
$house->translation = 'дом';
$house->transcription = 'haʊs';
$house->description = 'A house is a single-unit residential building, which may range in complexity from a rudimentary hut to a complex, structure of wood, masonry, concrete or other material, outfitted with plumbing, electrical, and heating, ventilation, and air conditioning systems.';
$house->examples = [
    '1) Montag had rarely seen that many house lights.', 
    '2) The house was so small, the furnishings so very simple.',
];

$tree = new Word();

$tree->name = 'tree';
$tree->translation = 'дерево';
$tree->transcription = 'tri';
$tree->description = 'In botany, a tree is a perennial plant with an elongated stem, or trunk, supporting branches and leaves in most species. In some usages, the definition of a tree may be narrower, including only woody plants with secondary growth, plants that are usable as lumber or plants above a specified height. In wider definitions, the taller palms, tree ferns, bananas, and bamboos are also trees.';
$tree->examples = [
    '1) Fruit grows on trees.', 
    '2) An apple fell from the tree.', 
    '3) The cherry tree bloomed.',
];   

$house->save();
$tree->save();

loadDictionaryWords();
