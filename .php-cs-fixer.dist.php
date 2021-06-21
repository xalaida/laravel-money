<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor'])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Nevadskiy\PhpCsFixerRules\Style::new()->lib($finder);
