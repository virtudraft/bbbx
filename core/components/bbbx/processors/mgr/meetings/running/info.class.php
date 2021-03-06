
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
 * @subpackage processor
 */
class MeetingInfoProcessor extends modProcessor
{

    public function initialize()
    {
        $meetingID = $this->getProperty('meetingID', false);
        if (empty($meetingID)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_meetingID');
        }
        $moderatorPW = $this->getProperty('moderatorPW', false);
        if (empty($moderatorPW)) {
            return $this->modx->lexicon('bbbx.meeting_err_ns_moderatorPW');
        }
        $this->unsetProperty('action');

        return true;
    }

    public function process()
    {
        $messages = $this->modx->bbbx->getMeetingInfo($this->getProperty('meetingID'), $this->getProperty('moderatorPW'));
        $isError  = $this->modx->bbbx->getError();
        if (!empty($isError)) {
            return $this->failure($isError);
        }
        $info = '';
        if (!empty($messages)) {
            unset($messages['returncode']);
            $info .= '<table class="bbbx-table-console">';
            foreach ($messages as $key => $val) {
                if ($key == 'attendees' ||
                        $key == 'metadata' ||
                        $key == 'messageKey' ||
                        $key == 'message'
                ) {
                    continue;
                }
                $info .= '<tr><td>'.$key.'</td><td class="bbbx-td-colon">:</td><td class="bbbx-td-value">'.$this->toString($val).'</td></tr>';
            }
            if (isset($messages['attendees']['attendee'])) {
                $info .= '<tr><td>attendees</td><td class="bbbx-td-colon">:</td><td>';
                if (!isset($messages['attendees']['attendee'][0])) {
                    $messages['attendees']['attendee'] = array($messages['attendees']['attendee']);
                }
                foreach ($messages['attendees']['attendee'] as $idx => $attendee) {
                    if (empty($attendee)) {
                        continue;
                    }
                    $info .= '<table>';
                    $i = 0;
                    foreach ($attendee as $key => $val) {
                        $info .= '<tr><td>'.($i === 0 ? $idx + 1 : '').'</td><td>'.$key.'</td><td class="bbbx-td-colon">:</td><td class="bbbx-td-value">'.$this->toString($val).'</td></tr>';
                        $i++;
                    }
                    $info .= '</table>';
                }
                $info .= '</td></tr>';
            }
            $info .= '<tr><td>metadata</td><td class="bbbx-td-colon">:</td><td>';
            foreach ($messages['metadata'] as $key => $val) {
                $info .= '<span>'.$key.': '.$this->toString($val)."</span><br />\n";
            }
            $info .= '</td></tr>';
            $info .= '<tr><td>messageKey</td><td class="bbbx-td-colon">:</td><td class="bbbx-td-value">';
            foreach ($messages['messageKey'] as $key => $val) {
                $info .= '<span>'.$key.': '.$this->toString($val)."</span><br />\n";
            }
            $info .= '</td></tr>';
            $info .= '<tr><td>message</td><td class="bbbx-td-colon">:</td><td class="bbbx-td-value">';
            foreach ($messages['message'] as $key => $val) {
                $info .= '<span>'.$key.': '.$this->toString($val)."</span><br />\n";
            }
            $info .= '</td></tr>';
            $info .= "</table>";
            $this->modx->log(modX::LOG_LEVEL_INFO, $info);
        } else {
            $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->bbbx->getError());
        }

        return $this->success($info);
    }

    private function toString($text)
    {
        if (is_string($text)) {
            return $text;
        } elseif (is_array($text)) {
            $o = '';
            foreach ($text as $k => $v) {
                $o .= $k.': '.$this->toString($v)."<br>\n";
            }
            return $o;
        } elseif (is_object($text)) {
            return $this->toString(json_decode(json_encode($text), true));
        }
        return;
    }

}

return 'MeetingInfoProcessor';
