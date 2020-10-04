<?php

require_once './class/Word.php';
require_once './class/Load.php';

$house = new Word(
    'house',
    'дом',
    'haʊs',
    'A house is a single-unit residential building, which may range in complexity from a rudimentary hut to a complex, structure of wood, masonry, concrete or other material, outfitted with plumbing, electrical, and heating, ventilation, and air conditioning systems.',
    [
        'Montag had rarely seen that many house lights.',
        'The house was so small, the furnishings so very simple.',
    ]
);

$tree = new Word(
    'tree',
    'дерево',
    'tri',
    'In botany, a tree is a perennial plant with an elongated stem, or trunk, supporting branches and leaves in most species. In some usages, the definition of a tree may be narrower, including only woody plants with secondary growth, plants that are usable as lumber or plants above a specified height. In wider definitions, the taller palms, tree ferns, bananas, and bamboos are also trees.',
    [
        'Fruit grows on trees.', 
        'An apple fell from the tree.', 
        'The cherry tree bloomed.',
    ]
);

$house->save();
$tree->save();

$load = new Load();

$wordObj = $load->getWord('tree');
$wordObj->print();
