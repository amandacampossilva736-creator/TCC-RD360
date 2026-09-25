<?php

require_once __DIR__ . '/conexao.php';

$tabela = $_GET['tabela'] ?? '';

$map = [

    'obras' => [
        'nome' => 'obras',
        'cabecalho' => [
            'ID',
            'Projeto',
            'Nota',
            'Título',
            'Município',
            'ID Investimento',
            'Quantidade Postes',
            'UC Previsto',
            'Data Energização'
        ],
        'sql' => '
            SELECT
                o.id_obra,
                o.projeto_obra,
                o.nota,
                o.titulo,
                m.nome,
                o.id_investimento,
                o.qtd_postes,
                o.uc_previsto,
                o.data_energizacao
            FROM obra o
            INNER JOIN municipio m
                ON o.fk_municipio_id_municipio = m.id_municipio
            ORDER BY o.id_obra
        '
    ],

    'asbuilt' => [
        'nome' => 'as_built',
        'cabecalho' => [
            'Nº As Built',
            'Responsável',
            'Obra',
            'Status',
            'Data Fiscalização',
            'Observações'
        ],
        'sql' => '
            SELECT
                a.n_as_built,
                a.responsavel,
                o.titulo,
                s.nome_status,
                a.data_fiscalizacao,
                a.observacoes
            FROM as_built a
            INNER JOIN obra o
                ON a.fk_obra_id_obra = o.id_obra
            INNER JOIN status s
                ON a.fk_status_id_status = s.id_status
            ORDER BY a.n_as_built
        '
    ],

    'pontos' => [
        'nome' => 'pontos_coletados',
        'cabecalho' => [
            'ID Ponto',
            'Nota',
            'Nota da Obra',
            'Obra',
            'Latitude',
            'Longitude',
            'Tipo Poste',
            'Material Poste',
            'Altura Poste',
            'Esforço Poste',
            'Equipamento',
            'Potência',
            'Placa',
            'CIA',
            'Estrutura Primária',
            'Estrutura Secundária'
        ],
        'sql' => '
            SELECT
                pc.id_ponto,
                pc.nota_obra,
                o.titulo,
                pc.latitude,
                pc.longitude,
                pg.tipo_poste,
                pg.material_poste,
                pg.altura_poste,
                pg.esforco_poste,
                eq.tipo,
                eq.potencia,
                eq.placa,
                eq.cia,
                ep.tipo_estrutura,
                es.tipo_estrutura
            FROM pontos_coletados pc

            INNER JOIN poste_geolocalizacao pg
                ON pc.fk_poste_geolocalizacao_nota = pg.nota
               AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao

            INNER JOIN obra o
                ON pg.fk_obra_id_obra = o.id_obra

            LEFT JOIN equipamentos eq
                ON eq.fk_poste_geolocalizacao_nota = pg.nota
               AND eq.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao

            LEFT JOIN estrutura_primaria ep
                ON ep.fk_pontos_coletados_id_ponto = pc.id_ponto

            LEFT JOIN estrutura_secundaria es
                ON es.fk_pontos_coletados_id_ponto = pc.id_ponto

            ORDER BY pc.id_ponto
        '
    ],

    'vaos' => [
        'nome' => 'vaos',
        'cabecalho' => [
            'ID Vão',
            'Ponto Origem',
            'Obra Origem',
            'Ponto Destino',
            'Obra Destino',
            'Distância',
            'Condutor'
        ],
        'sql' => '
            SELECT
                v.id_vao,
                po.nota,
                po.obra,
                pd.nota,
                pd.obra,
                v.distancia,
                v.tipo_condutor

            FROM vao v

            INNER JOIN (
                SELECT
                    pc.id_ponto,
                    pc.nota_obra AS nota,
                    o.titulo AS obra

                FROM pontos_coletados pc

                INNER JOIN poste_geolocalizacao pg
                    ON pc.fk_poste_geolocalizacao_nota = pg.nota
                   AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao

                INNER JOIN obra o
                    ON pg.fk_obra_id_obra = o.id_obra

            ) po
                ON po.id_ponto =
                   v.fk_pontos_coletados_id_ponto_origem

            INNER JOIN (
                SELECT
                    pc.id_ponto,
                    pc.nota_obra AS nota,
                    o.titulo AS obra

                FROM pontos_coletados pc

                INNER JOIN poste_geolocalizacao pg
                    ON pc.fk_poste_geolocalizacao_nota = pg.nota
                   AND pc.fk_poste_geolocalizacao_id_geolocalizacao = pg.id_geolocalizacao

                INNER JOIN obra o
                    ON pg.fk_obra_id_obra = o.id_obra

            ) pd
                ON pd.id_ponto =
                   v.fk_pontos_coletados_id_ponto_destino

            ORDER BY v.id_vao
        '
    ]
];




if (!isset($map[$tabela])) {

    http_response_code(400);

    exit('Tipo de exportação inválido.');
}




if (!class_exists('ZipArchive')) {

    http_response_code(500);

    exit(
        'A extensão ZIP do PHP não está habilitada. ' .
        'Ative extension=zip no php.ini e reinicie o Apache.'
    );
}




$q = mysqli_query($conn, $map[$tabela]['sql']);

if (!$q) {
    http_response_code(500);
    exit('Erro ao consultar os dados: ' . htmlspecialchars($conn->error));
}

$linhas = mysqli_fetch_all($q, MYSQLI_NUM);

function xml($valor)
{
    return htmlspecialchars(
        (string)($valor ?? ''),
        ENT_XML1 | ENT_QUOTES,
        'UTF-8'
    );
}




function valorExcel($valor)
{
    if ($valor === null) {
        return '';
    }

    return (string)$valor;
}




function criarCelula($valor)
{
    return '<c t="inlineStr">
                <is>
                    <t>' . xml(valorExcel($valor)) . '</t>
                </is>
            </c>';
}




$larguras = [

    'obras' => [
        10, 18, 30, 22, 20, 20, 15, 20
    ],

    'asbuilt' => [
        15, 30, 25, 30, 18, 22, 45
    ],

    'pontos' => [
        12, 18, 30, 18, 18, 18, 20,
        20, 15, 18, 15, 22, 24
    ],

    'vaos' => [
        12, 20, 25, 20, 25, 18
    ]
];

$largurasAtual =
    $larguras[$tabela];




$cols = '';

foreach ($largurasAtual as $i => $largura) {

    $numero = $i + 1;

    $cols .=
        '<col min="' . $numero .
        '" max="' . $numero .
        '" width="' . $largura .
        '" customWidth="1"/>';
}




$sheetData = '';

$sheetData .= '<row r="1">';

foreach ($map[$tabela]['cabecalho'] as $cabecalho) {

    $sheetData .=
        '<c t="inlineStr" s="1">
            <is>
                <t>' . xml($cabecalho) . '</t>
            </is>
        </c>';
}

$sheetData .= '</row>';




$linhaExcel = 2;

foreach ($linhas as $linha) {

    $sheetData .=
        '<row r="' . $linhaExcel . '">';

    foreach ($linha as $valor) {

        $sheetData .= criarCelula($valor);
    }

    $sheetData .= '</row>';

    $linhaExcel++;
}




$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">

<Default Extension="rels"
    ContentType="application/vnd.openxmlformats-package.relationships+xml"/>

<Default Extension="xml"
    ContentType="application/xml"/>

<Override PartName="/xl/workbook.xml"
    ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>

<Override PartName="/xl/worksheets/sheet1.xml"
    ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>

<Override PartName="/xl/styles.xml"
    ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>

</Types>';


$rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">

<Relationship
    Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument"
    Target="xl/workbook.xml"/>

</Relationships>';


$workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">

<Relationship
    Id="rId1"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet"
    Target="worksheets/sheet1.xml"/>

<Relationship
    Id="rId2"
    Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles"
    Target="styles.xml"/>

</Relationships>';


$workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<workbook
xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"
xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">

<sheets>

<sheet
name="RD360"
sheetId="1"
r:id="rId1"/>

</sheets>

</workbook>';


$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<styleSheet
xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">

<fonts count="2">

<font>
<sz val="11"/>
<name val="Arial"/>
</font>

<font>
<b/>
<sz val="11"/>
<name val="Arial"/>
</font>

</fonts>

<fills count="2">

<fill>
<patternFill patternType="none"/>
</fill>

<fill>
<patternFill patternType="gray125"/>
</fill>

</fills>

<borders count="1">

<border>
<left/>
<right/>
<top/>
<bottom/>
<diagonal/>
</border>

</borders>

<cellStyleXfs count="1">

<xf numFmtId="0"
fontId="0"
fillId="0"
borderId="0"/>

</cellStyleXfs>

<cellXfs count="2">

<xf numFmtId="0"
fontId="0"
fillId="0"
borderId="0"/>

<xf numFmtId="0"
fontId="1"
fillId="0"
borderId="0"
applyFont="1"/>

</cellXfs>

</styleSheet>';


$sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>

<worksheet
xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">

<cols>
' . $cols . '
</cols>

<sheetData>
' . $sheetData . '
</sheetData>

</worksheet>';




$arquivoTemporario =
    tempnam(sys_get_temp_dir(), 'rd360_') . '.xlsx';


$zip = new ZipArchive();

if ($zip->open($arquivoTemporario, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {

    http_response_code(500);

    exit('Não foi possível criar o arquivo Excel.');
}




$zip->addFromString(
    '[Content_Types].xml',
    $contentTypes
);

$zip->addFromString(
    '_rels/.rels',
    $rels
);

$zip->addFromString(
    'xl/workbook.xml',
    $workbook
);

$zip->addFromString(
    'xl/_rels/workbook.xml.rels',
    $workbookRels
);

$zip->addFromString(
    'xl/styles.xml',
    $styles
);

$zip->addFromString(
    'xl/worksheets/sheet1.xml',
    $sheet
);

$zip->close();




$nomeArquivo =
    'RD360_' .
    $map[$tabela]['nome'] .
    '.xlsx';

header(
    'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);

header(
    'Content-Disposition: attachment; filename="' .
    $nomeArquivo .
    '"'
);

header(
    'Content-Length: ' .
    filesize($arquivoTemporario)
);

header('Cache-Control: no-cache, must-revalidate');

readfile($arquivoTemporario);

unlink($arquivoTemporario);

exit;

?>