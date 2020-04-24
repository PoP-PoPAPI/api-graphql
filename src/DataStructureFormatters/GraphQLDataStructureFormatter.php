<?php

declare(strict_types=1);

namespace PoP\GraphQLAPI\DataStructureFormatters;

use PoP\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoP\ComponentModel\Feedback\Tokens;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    public const NAME = 'graphql';
    public static function getName()
    {
        return self::NAME;
    }

    public function getFormattedData($data)
    {
        $ret = [];

        // Add errors
        $errors = $warnings = $deprecations = $notices = $traces = [];
        if ($data['dbErrors']) {
            $errors = $this->reformatDBEntries($data['dbErrors']);
        }
        if ($data['schemaErrors']) {
            $errors = array_merge(
                $errors,
                $this->reformatSchemaEntries($data['schemaErrors'])
            );
        }
        if ($data['queryErrors']) {
            $errors = array_merge(
                $errors,
                $this->reformatQueryEntries($data['queryErrors'])
            );
        }
        if ($errors) {
            $ret['errors'] = $errors;
        }

        /**
         * "Warnings", "deprecations", and "logEntries" top-level entries:
         * since they are not part of the spec, place them under the top-level entry "extensions":
         *
         * > This entry is reserved for implementors to extend the protocol however they see fit,
         * > and hence there are no additional restrictions on its contents.
         *
         * @see http://spec.graphql.org/June2018/#sec-Response-Format
         */

        // Add warnings
        if ($data['dbWarnings']) {
            $warnings = $this->reformatDBEntries($data['dbWarnings']);
        }
        if ($data['schemaWarnings']) {
            $warnings = array_merge(
                $warnings,
                $this->reformatSchemaEntries($data['schemaWarnings'])
            );
        }
        if ($warnings) {
            $ret['extensions']['warnings'] = $warnings;
        }

        // Add notices
        if ($data['dbNotices']) {
            $notices = $this->reformatDBEntries($data['dbNotices']);
        }
        if ($data['schemaNotices']) {
            $notices = array_merge(
                $notices,
                $this->reformatSchemaEntries($data['schemaNotices'])
            );
        }
        if ($notices) {
            $ret['extensions']['notices'] = $notices;
        }

        // Add traces
        if ($data['dbTraces']) {
            $traces = $this->reformatDBEntries($data['dbTraces']);
        }
        if ($data['schemaTraces']) {
            $traces = array_merge(
                $traces,
                $this->reformatSchemaEntries($data['schemaTraces'])
            );
        }
        if ($traces) {
            $ret['extensions']['traces'] = $traces;
        }

        // Add deprecations
        if ($data['dbDeprecations']) {
            $deprecations = $this->reformatDBEntries($data['dbDeprecations']);
        }
        if ($data['schemaDeprecations']) {
            $deprecations = array_merge(
                $deprecations,
                $this->reformatSchemaEntries($data['schemaDeprecations'])
            );
        }
        if ($deprecations) {
            $ret['extensions']['deprecations'] = $deprecations;
        }

        // Logs
        if ($data['logEntries']) {
            $ret['extensions']['logs'] = $data['logEntries'];
        }

        if ($resultData = parent::getFormattedData($data)) {
            // // GraphQL places the queried data under entries 'data' => query => results
            // // Replicate this structure. Because we don't have a query name here,
            // // replace it with the queried URL path, which is known to the client
            // $path = RoutingUtils::getURLPath();
            // // If there is no path, it is the single point of entry (homepage => root)
            // if (!$path) {
            //     $path = '/';
            // }
            // $ret['data'] = [
            //     $path => $resultData,
            // ];
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    /**
     * Indicate if to add the extensions naturally available to PoP.
     * Overridable for GraphQL
     *
     * @return boolean
     */
    protected function addNativeExtensions(): bool
    {
        return true;
    }

    protected function reformatDBEntries($entries)
    {
        $ret = [];
        foreach ($entries as $dbKey => $id_items) {
            foreach ($id_items as $id => $items) {
                foreach ($items as $item) {
                    $entry = [];
                    if ($message = $item[Tokens::MESSAGE]) {
                        $entry['message'] = $message;
                    }
                    if ($name = $item[Tokens::NAME]) {
                        $entry['name'] = $name;
                    }
                    if ($extensions = array_merge(
                        $this->addNativeExtensions() ?
                            [
                                'type' => 'dataObject',
                                'entityDBKey' => $dbKey,
                                'id' => $id,
                                'path' => $item[Tokens::PATH],
                            ] :
                            [],
                        $item[Tokens::EXTENSIONS] ?? []
                    )) {
                        $entry['extensions'] = $extensions;
                    }
                    $ret[] = $entry;
                }
            }
        }
        return $ret;
    }

    protected function reformatSchemaEntries($entries)
    {
        $ret = [];
        foreach ($entries as $dbKey => $items) {
            foreach ($items as $item) {
                $entry = [];
                if ($message = $item[Tokens::MESSAGE]) {
                    $entry['message'] = $message;
                }
                if ($name = $item[Tokens::NAME]) {
                    $entry['name'] = $name;
                }
                if ($extensions = array_merge(
                    $this->addNativeExtensions() ?
                        [
                            'type' => 'schema',
                            'entityDBKey' => $dbKey,
                            'path' => $item[Tokens::PATH],
                        ] :
                        [],
                    $item[Tokens::EXTENSIONS] ?? []
                )) {
                    $entry['extensions'] = $extensions;
                }
                $ret[] = $entry;
            }
        }
        return $ret;
    }

    protected function reformatQueryEntries($entries)
    {
        $ret = [];
        foreach ($entries as $message => $extensions) {
            $entry = [
                'message' => $message,
            ];
            $this->addExtensions($entry, $extensions);
            $ret[] = $entry;
        }
        return $ret;
    }

    protected function addExtensions(array &$entry, array $extensions): void
    {
        if ($extensions = array_merge(
            $this->addNativeExtensions() ?
                [
                    'type' => 'query',
                ] :
                [],
            $extensions
        )) {
            $entry['extensions'] = $extensions;
        };
    }
}
