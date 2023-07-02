<?php

class DocumentManager
{
    protected static $documentPaths = [];

    public static function initDocumentPaths()
    {
        self::$documentPaths = [
            'sepa-mandate' => \Auth::user()->getFolder() . '/sepa_mandate',
            'cash-accounting-report' => \Auth::user()->getS3TmpFolder() . '/pdf-export',
            'vat-report-eu' => \Auth::user()->getS3TmpFolder() . '/pdf-export',
            'address-labels' => \Auth::user()->getS3TmpFolder() . '/pdf-export',
            'export-backup' => \Auth::user()->getS3TmpFolder() . '/pdf-export',
        ];
    }

    public static function getDocumentPath($type)
    {
        // Initialize document paths if not done yet
        if (empty(self::$documentPaths)) {
            self::initDocumentPaths();
        }

        return self::$documentPaths[$type];
    }

    // after save (formatting) "public static" is gone
    public static function print($type, $data = array(), $filename = null) {

    }
}