<?php

/**
 * BBBx
 *
 * Copyright 2016 by goldsky <goldsky@virtudraft.com>
 *
 * This file is part of BBBx, a BigBlueButton and MODX integration add on.
 *
 * BBBx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation version 3,
 *
 * BBBx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * BBBx; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package bbbx
 * @subpackage build
 */

/**
 * @param   string  $filename   filename
 * @return  string  file content
 */
function getSnippetContent($filename)
{
    $o = file_get_contents($filename);
    $o = str_replace('<?php', '', $o);
    $o = str_replace('?>', '', $o);
    $o = trim($o);
    return $o;
}

$snippets = array();

$snippet    = $modx->newObject('modSnippet');
$snippet->fromArray(array(
    'id'                  => 0,
    'property_preprocess' => 1,
    'name'                => 'bbbx.getMeetings',
    'description'         => 'Get scheduled meetings',
    'snippet'             => getSnippetContent($sources['source_core'].'/elements/snippets/getmeetings.snippet.php'),
        ), '', true, true);
$properties = include $sources['properties'].'bbbx.getmeetings.snippet.properties.php';
$snippet->setProperties($properties);
unset($properties);
$snippets[] = $snippet;

$snippet    = $modx->newObject('modSnippet');
$snippet->fromArray(array(
    'id'                  => 0,
    'property_preprocess' => 1,
    'name'                => 'bbbx.getRecordings',
    'description'         => 'Get recordings for identified meetings',
    'snippet'             => getSnippetContent($sources['source_core'].'/elements/snippets/getrecordings.snippet.php'),
        ), '', true, true);
$properties = include $sources['properties'].'bbbx.getrecordings.snippet.properties.php';
$snippet->setProperties($properties);
unset($properties);
$snippets[] = $snippet;

return $snippets;
