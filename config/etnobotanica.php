<?php

/*
|--------------------------------------------------------------------------
| Configuración propia de la plataforma Etnobotánica
|--------------------------------------------------------------------------
|
| plantas_precaucion: especies que requieren una advertencia reforzada en su
| ficha porque contienen compuestos tóxicos o psicoactivos, o porque su uso
| está contraindicado en ciertas personas. La clave es el nombre científico
| (o el género, para cubrir todas sus especies) y el valor es el mensaje
| específico que se muestra.
|
| Lista preliminar elaborada por el equipo de la fase 2 a partir de los
| riesgos conocidos de cada especie. Debe ser revisada y ampliada por el
| experto del proyecto antes de considerarse definitiva.
|
*/

return [

    'plantas_precaucion' => [
        'Brugmansia'               => 'Planta altamente tóxica: todas sus partes contienen alcaloides que pueden causar intoxicación grave. No debe ingerirse.',
        'Datura'                   => 'Planta altamente tóxica: todas sus partes contienen alcaloides que pueden causar intoxicación grave. No debe ingerirse.',
        'Ruta graveolens'          => 'Puede ser tóxica en dosis altas, es abortiva y su savia puede causar quemaduras en la piel expuesta al sol. No debe usarse en el embarazo.',
        'Artemisia absinthium'     => 'Contiene tuyona, que en dosis altas o uso prolongado es tóxica para el sistema nervioso. No debe usarse en el embarazo ni en niños.',
        'Kalanchoe'                => 'Contiene compuestos que actúan sobre el corazón y pueden ser tóxicos si se ingieren en cantidad. Evite su consumo sin orientación médica.',
        'Cannabis'                 => 'Planta de uso regulado por la ley colombiana, con efectos psicoactivos. Su uso medicinal requiere orientación médica.',
        'Erythroxylum coca'        => 'Planta de uso regulado por la ley colombiana. Su uso tradicional no debe confundirse con el consumo de sus derivados.',
        'Ricinus communis'         => 'Sus semillas son altamente tóxicas. No deben ingerirse.',
        'Nicotiana'                => 'Contiene nicotina, tóxica si se ingiere. No debe usarse en niños ni en el embarazo.',
    ],

];
