<?php

namespace Chuva\Php\WebScrapping;

use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

class Writer {
    public function write($data): void {
        $filePath = __DIR__ . '/data.xlsx';
        $maxAuthors = 0;
        
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToFile($filePath);

        foreach ($data as $row) {
            $maxAuthors = max($maxAuthors, count($row->authors));
        }

        $headerCells = [
            WriterEntityFactory::createCell('ID'),
            WriterEntityFactory::createCell('Title'),
            WriterEntityFactory::createCell('Type'),
        ];

        for ($i = 1; $i <= $maxAuthors; $i++) {
            $headerCells[] = WriterEntityFactory::createCell("Author $i");
            $headerCells[] = WriterEntityFactory::createCell("Institution $i");
        }

        $headerRow = WriterEntityFactory::createRow($headerCells);
        $writer->addRow($headerRow);

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