<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class Writer {
    public function write($data): void {
        $filePath = __DIR__ . '/data.xlsx';
        
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        foreach ($data as $row) {
            $cells = [
                WriterEntityFactory::createCell($row->id),
                WriterEntityFactory::createCell($row->title),
                WriterEntityFactory::createCell($row->type),
            ];

            foreach ($row->authors as $author) {
                $cells[] = WriterEntityFactory::createCell($author->name);
                $cells[] = WriterEntityFactory::createCell($author->institution);
            }

            $singleRow = WriterEntityFactory::createRow($cells);
            $writer->addRow($singleRow);
        }

        $writer->close();
    }
}